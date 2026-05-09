<?php
/**
 * Filters, body classes, and small helper functions.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add `has-bottom-nav` to the body so the matching CSS rule pads the
 * page enough that the fixed mobile bottom-nav doesn't cover content.
 *
 * @param string[] $classes
 * @return string[]
 */
function yum2_body_classes( $classes ) {
	if ( ! is_admin() ) {
		$classes[] = 'has-bottom-nav';
	}
	if ( is_home() || ( is_archive() && in_array( get_post_type(), array( '', 'post' ), true ) ) || is_search() ) {
		$classes[] = 'blog-index';
	}
	if ( is_singular( 'post' ) ) {
		$classes[] = 'single-post-yum2';
	}
	return $classes;
}
add_filter( 'body_class', 'yum2_body_classes' );

/**
 * Reading time, in minutes, for the given post.
 *
 * Word count / 200 (the lazy adult-reading-rate average) rounded up,
 * floor of 1. Returns a translated "X min read" string.
 *
 * @param int|WP_Post|null $post
 * @return string
 */
function yum2_reading_time( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return '';
	}
	$words   = str_word_count( wp_strip_all_tags( (string) $post->post_content ) );
	$minutes = max( 1, (int) ceil( $words / 200 ) );
	return sprintf(
		/* translators: %d: minutes */
		_n( '%d min read', '%d min read', $minutes, 'youumatter2' ),
		$minutes
	);
}

/**
 * Fallback excerpt: when the post has no manual excerpt, return the
 * first sentence of the content (capped at ~30 words) instead of WP's
 * default 55-word truncation that often cuts mid-thought.
 *
 * @param string  $excerpt
 * @param WP_Post $post
 * @return string
 */
function yum2_excerpt_fallback( $excerpt, $post = null ) {
	if ( '' !== trim( (string) $excerpt ) ) {
		return $excerpt;
	}
	$post = get_post( $post );
	if ( ! $post ) {
		return $excerpt;
	}
	$plain = wp_strip_all_tags( strip_shortcodes( (string) $post->post_content ) );
	$plain = trim( preg_replace( '/\s+/', ' ', $plain ) );
	if ( '' === $plain ) {
		return $excerpt;
	}
	if ( preg_match( '/^(.{20,}?[.!?])(\s|$)/u', $plain, $m ) ) {
		$first = trim( $m[1] );
	} else {
		$first = $plain;
	}
	return wp_trim_words( $first, 30, '…' );
}
add_filter( 'get_the_excerpt', 'yum2_excerpt_fallback', 10, 2 );

/**
 * Newsletter form handler. POST target for template-parts/footer/newsletter.php.
 *
 * Phase 3 stores the email in a capped option array and redirects back
 * with ?subscribed=1. MailerLite wiring lands in a later phase; the
 * markup contract (action name + nonce field name) stays the same.
 */
function yum2_handle_subscribe() {
	$nonce = isset( $_POST['_yum2_subscribe_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_yum2_subscribe_nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'yum2_subscribe' ) ) {
		wp_die( esc_html__( 'Invalid request.', 'youumatter2' ), '', array( 'response' => 400 ) );
	}

	$email    = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$redirect = isset( $_POST['_yum2_redirect'] ) ? esc_url_raw( wp_unslash( $_POST['_yum2_redirect'] ) ) : home_url( '/' );

	if ( ! is_email( $email ) ) {
		wp_safe_redirect( add_query_arg( 'subscribed', '0', $redirect ) );
		exit;
	}

	$list = get_option( 'yum2_pending_subscribers', array() );
	if ( ! is_array( $list ) ) {
		$list = array();
	}
	if ( ! in_array( $email, $list, true ) ) {
		$list[] = $email;
		if ( count( $list ) > 500 ) {
			$list = array_slice( $list, -500 );
		}
		update_option( 'yum2_pending_subscribers', $list, false );
	}

	wp_safe_redirect( add_query_arg( 'subscribed', '1', $redirect ) . '#newsletter' );
	exit;
}
add_action( 'admin_post_yum2_subscribe', 'yum2_handle_subscribe' );
add_action( 'admin_post_nopriv_yum2_subscribe', 'yum2_handle_subscribe' );

/**
 * Repair the_custom_logo() output for SVG attachments.
 *
 * WordPress core uses wp_get_attachment_image_src() for the custom logo,
 * which returns false for SVGs (no raster dimensions), so the markup
 * comes back empty. We rebuild it with a clean <img> the .custom-logo
 * CSS rules can size.
 *
 * @param string $html Original markup from get_custom_logo().
 * @return string
 */
function yum2_fix_svg_custom_logo( $html ) {
	$logo_id = (int) get_theme_mod( 'custom_logo' );
	if ( ! $logo_id ) {
		return $html;
	}

	$mime = (string) get_post_mime_type( $logo_id );
	if ( 'image/svg+xml' !== $mime && 'image/svg' !== $mime ) {
		return $html;
	}

	$src = wp_get_attachment_url( $logo_id );
	if ( ! $src ) {
		return $html;
	}

	$alt = (string) get_post_meta( $logo_id, '_wp_attachment_image_alt', true );
	if ( '' === $alt ) {
		$alt = get_bloginfo( 'name' );
	}

	return sprintf(
		'<a href="%1$s" class="custom-logo-link" rel="home"><img class="custom-logo" src="%2$s" alt="%3$s" loading="eager" decoding="async" /></a>',
		esc_url( home_url( '/' ) ),
		esc_url( $src ),
		esc_attr( $alt )
	);
}
add_filter( 'get_custom_logo', 'yum2_fix_svg_custom_logo' );

/* ------------------------------------------------------------------
 * Single-post helpers (Phase 4b)
 * ----------------------------------------------------------------*/

/**
 * Parse rendered post content for h2/h3 headings, inject deterministic
 * IDs, and return both the modified HTML and a TOC array.
 *
 * Implementation note: a regex pass beats DOMDocument here. WP content
 * sometimes contains tags that DOMDocument's strict HTML parser chokes
 * on, and getElementById() needs a DTD we can't easily provide. The
 * regex is heading-only, runs once per cache miss, and fails quietly.
 *
 * Cached on the post id + content hash so the sidebar TOC, mobile TOC,
 * and content output share work.
 *
 * @param string|null $content Pre-fetched HTML. Defaults to apply_filters('the_content', ...).
 * @param int|null    $post_id Defaults to current loop post.
 * @return array{html:string, toc:array<int,array{id:string,text:string,level:int}>}
 */
function yum2_post_toc( $content = null, $post_id = null ) {
	static $cache = array();
	static $busy  = false;

	$post_id = $post_id ?: get_the_ID();
	if ( ! $post_id ) {
		return array( 'html' => (string) $content, 'toc' => array() );
	}

	if ( null === $content ) {
		// Re-entry guard: yum2_apply_toc_ids hooks the_content, and calling
		// apply_filters('the_content') here would trigger it. Skip the
		// recursion and fetch the raw content instead; we'll inject IDs.
		if ( $busy ) {
			return array( 'html' => '', 'toc' => array() );
		}
		$busy    = true;
		$content = apply_filters( 'the_content', get_post_field( 'post_content', $post_id ) );
		$busy    = false;
	}

	$content = (string) $content;

	$cache_key = $post_id . '|' . md5( $content );
	if ( isset( $cache[ $cache_key ] ) ) {
		return $cache[ $cache_key ];
	}

	if ( '' === trim( $content ) ) {
		$cache[ $cache_key ] = array( 'html' => $content, 'toc' => array() );
		return $cache[ $cache_key ];
	}

	$toc      = array();
	$used_ids = array();

	$updated = preg_replace_callback(
		'#<(h[23])([^>]*)>(.*?)</\1>#is',
		function ( $m ) use ( &$toc, &$used_ids ) {
			$tag   = strtolower( $m[1] );
			$attrs = $m[2];
			$inner = $m[3];

			$text = trim( wp_strip_all_tags( $inner ) );
			if ( '' === $text ) {
				return $m[0];
			}

			// Reuse existing id when present.
			$existing_id = '';
			if ( preg_match( '#\bid=(["\'])(.*?)\1#i', $attrs, $idm ) ) {
				$existing_id = $idm[2];
			}

			$id = $existing_id ?: sanitize_title( $text );
			if ( '' === $id ) {
				$id = 'section-' . ( count( $toc ) + 1 );
			}
			$base = $id;
			$n    = 2;
			while ( in_array( $id, $used_ids, true ) ) {
				$id = $base . '-' . $n;
				$n++;
			}
			$used_ids[] = $id;

			$toc[] = array(
				'id'    => $id,
				'text'  => $text,
				'level' => 'h3' === $tag ? 3 : 2,
			);

			if ( $existing_id === $id ) {
				return $m[0];
			}

			$attrs_clean = '' !== $existing_id
				? preg_replace( '#\sid=(["\']).*?\1#i', '', $attrs )
				: $attrs;

			return '<' . $tag . ' id="' . esc_attr( $id ) . '"' . $attrs_clean . '>' . $inner . '</' . $tag . '>';
		},
		$content
	);

	if ( null === $updated ) {
		$updated = $content;
	}

	$cache[ $cache_key ] = array( 'html' => $updated, 'toc' => $toc );
	return $cache[ $cache_key ];
}

/**
 * Inject the mid-CTA partial into post content after the second h2.
 * Falls back to appending after the last h2 (or to nothing) on shorter posts.
 *
 * Hooked late on `the_content` so the typography plugin still wraps the result.
 *
 * @param string $content
 * @return string
 */
function yum2_inject_mid_cta( $content ) {
	if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}
	if ( '' === trim( (string) $content ) ) {
		return $content;
	}

	try {
		if ( ! preg_match_all( '#</h2>#i', $content, $matches, PREG_OFFSET_CAPTURE ) ) {
			return $content;
		}
		if ( empty( $matches[0] ) ) {
			return $content;
		}

		$target_index = count( $matches[0] ) >= 2 ? 1 : 0;
		$insert_at    = $matches[0][ $target_index ][1] + strlen( '</h2>' );

		ob_start();
		get_template_part( 'template-parts/post/mid-cta' );
		$cta_html = trim( (string) ob_get_clean() );
		if ( '' === $cta_html ) {
			return $content;
		}

		return substr( $content, 0, $insert_at ) . $cta_html . substr( $content, $insert_at );
	} catch ( \Throwable $e ) {
		if ( function_exists( 'error_log' ) ) {
			error_log( '[youumatter2] mid-cta inject failed: ' . $e->getMessage() );
		}
		return $content;
	}
}
add_filter( 'the_content', 'yum2_inject_mid_cta', 12 );

/**
 * Apply IDs from yum2_post_toc to the rendered content. Runs after the
 * mid-CTA injector so the CTA's heading (if any) doesn't get a TOC ID.
 *
 * @param string $content
 * @return string
 */
function yum2_apply_toc_ids( $content ) {
	if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}
	try {
		$result = yum2_post_toc( $content );
		return $result['html'];
	} catch ( \Throwable $e ) {
		if ( function_exists( 'error_log' ) ) {
			error_log( '[youumatter2] toc-id inject failed: ' . $e->getMessage() );
		}
		return $content;
	}
}
add_filter( 'the_content', 'yum2_apply_toc_ids', 14 );

/**
 * Breadcrumb trail. Returns array of segments [['label'=>'','url'=>''], ...].
 * The last segment has no URL (current page).
 *
 * @return array<int,array{label:string,url:string}>
 */
function yum2_breadcrumb() {
	$crumbs = array(
		array( 'label' => __( 'Home', 'youumatter2' ), 'url' => home_url( '/' ) ),
	);

	$blog_url = get_permalink( (int) get_option( 'page_for_posts' ) );
	if ( ! $blog_url ) {
		$blog_url = home_url( '/' );
	}

	if ( is_singular( 'post' ) ) {
		$crumbs[] = array( 'label' => __( 'Blog', 'youumatter2' ), 'url' => $blog_url );
		$cats     = get_the_category();
		if ( ! empty( $cats ) ) {
			$crumbs[] = array(
				'label' => $cats[0]->name,
				'url'   => get_category_link( $cats[0] ),
			);
		}
		$crumbs[] = array( 'label' => get_the_title(), 'url' => '' );
	} elseif ( is_category() ) {
		$crumbs[] = array( 'label' => __( 'Blog', 'youumatter2' ), 'url' => $blog_url );
		$crumbs[] = array( 'label' => single_cat_title( '', false ), 'url' => '' );
	} elseif ( is_tag() ) {
		$crumbs[] = array( 'label' => __( 'Blog', 'youumatter2' ), 'url' => $blog_url );
		$crumbs[] = array(
			/* translators: %s: tag name */
			'label' => sprintf( __( '#%s', 'youumatter2' ), single_tag_title( '', false ) ),
			'url'   => '',
		);
	} elseif ( is_singular( 'page' ) ) {
		$crumbs[] = array( 'label' => get_the_title(), 'url' => '' );
	}

	return $crumbs;
}

/**
 * Custom callback for wp_list_comments. Renders the rounded-card design.
 *
 * @param WP_Comment $comment
 * @param array      $args
 * @param int        $depth
 */
function yum2_comment_callback( $comment, $args, $depth ) {
	$author = trim( (string) get_comment_author( $comment ) );
	if ( '' === $author ) {
		$author = __( 'Anonymous', 'youumatter2' );
	}
	$parts    = preg_split( '/\s+/', $author );
	$initials = strtoupper( ( isset( $parts[0][0] ) ? $parts[0][0] : '' ) . ( isset( $parts[1][0] ) ? $parts[1][0] : '' ) );
	if ( '' === $initials ) {
		$initials = strtoupper( substr( $author, 0, 2 ) );
	}

	$ago = sprintf(
		/* translators: %s: human-readable time difference, e.g. "3 days" */
		__( '%s ago', 'youumatter2' ),
		human_time_diff( get_comment_time( 'U', false, $comment ), current_time( 'timestamp' ) )
	);
	?>
	<li <?php comment_class( 'bg-white border border-forest/15 rounded-[20px] p-5 md:p-6 list-none', $comment ); ?> id="comment-<?php comment_ID(); ?>">
		<article>
			<header class="flex items-center gap-3 mb-3">
				<div aria-hidden class="shrink-0 size-10 rounded-full bg-sage-light text-forest flex items-center justify-center" style="font-family:'Newsreader',serif;font-size:13px;font-weight:600;">
					<?php echo esc_html( $initials ); ?>
				</div>
				<div class="min-w-0">
					<p class="text-forest" style="font-size:14px;font-weight:600;">
						<?php echo esc_html( $author ); ?>
					</p>
					<p class="text-forest/65" style="font-size:12px;">
						<time datetime="<?php echo esc_attr( get_comment_time( 'c', false, false, $comment ) ); ?>">
							<?php echo esc_html( $ago ); ?>
						</time>
					</p>
				</div>
			</header>
			<div class="text-ink" style="font-family:'Newsreader',serif;font-size:16px;line-height:1.65;">
				<?php
				if ( '0' === $comment->comment_approved ) {
					?>
					<p class="italic text-terracotta mb-2"><?php esc_html_e( 'Your comment is awaiting moderation.', 'youumatter2' ); ?></p>
					<?php
				}
				comment_text();
				?>
			</div>
			<footer class="mt-4 pt-3 border-t border-forest/10 flex items-center gap-4 text-forest/65" style="font-size:12.5px;font-weight:600;">
				<button
					type="button"
					class="inline-flex items-center gap-1.5 hover:text-forest transition-colors"
					aria-label="<?php esc_attr_e( 'Like (placeholder)', 'youumatter2' ); ?>"
				>
					<?php echo yum2_icon( 'heart', array( 'size' => 13, 'stroke' => 1.8 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span>0</span>
				</button>
				<span class="inline-flex items-center gap-1.5 opacity-60" aria-hidden>
					<?php echo yum2_icon( 'reply', array( 'size' => 13, 'stroke' => 1.8 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php esc_html_e( 'Reply', 'youumatter2' ); ?>
				</span>
			</footer>
		</article>
	<?php // No closing </li>; wp_list_comments closes it. ?>
<?php }

/**
 * Comment form: drop URL field, drop cookies-consent, reorder so the
 * comment textarea comes first and name+email row is below.
 *
 * @param array $fields
 * @return array
 */
function yum2_comment_form_fields( $fields ) {
	unset( $fields['url'] );
	unset( $fields['cookies'] );

	$commenter = wp_get_current_commenter();

	$fields['author'] = sprintf(
		'<p class="comment-form-author flex-1"><label class="sr-only" for="author">%s</label><input id="author" name="author" type="text" value="%s" placeholder="%s" required class="block w-full bg-cream border border-forest/15 rounded-[14px] px-5 py-3.5 text-ink placeholder:text-forest/65 outline-none focus:border-forest transition-colors" style="font-size:14px;"></p>',
		esc_html__( 'Your name', 'youumatter2' ),
		esc_attr( $commenter['comment_author'] ),
		esc_attr__( 'Your name', 'youumatter2' )
	);

	$fields['email'] = sprintf(
		'<p class="comment-form-email flex-1"><label class="sr-only" for="email">%s</label><input id="email" name="email" type="email" value="%s" placeholder="%s" required class="block w-full bg-cream border border-forest/15 rounded-[14px] px-5 py-3.5 text-ink placeholder:text-forest/65 outline-none focus:border-forest transition-colors" style="font-size:14px;"></p>',
		esc_html__( 'Email (kept private)', 'youumatter2' ),
		esc_attr( $commenter['comment_author_email'] ),
		esc_attr__( 'Email (kept private)', 'youumatter2' )
	);

	return $fields;
}
add_filter( 'comment_form_default_fields', 'yum2_comment_form_fields' );

/**
 * Wrap the comment textarea in our styled markup.
 */
function yum2_comment_form_textarea( $field ) {
	return sprintf(
		'<p class="comment-form-comment"><label class="sr-only" for="comment">%s</label><textarea id="comment" name="comment" rows="3" placeholder="%s" required class="w-full bg-cream border border-forest/15 rounded-[14px] px-5 py-4 text-ink placeholder:text-forest/65 outline-none focus:border-forest transition-colors resize-none" style="font-family:\'Newsreader\',serif;font-size:15px;line-height:1.55;"></textarea></p>',
		esc_html__( 'Share a thought', 'youumatter2' ),
		esc_attr__( 'Share a thought…', 'youumatter2' )
	);
}
add_filter( 'comment_form_field_comment', 'yum2_comment_form_textarea' );
