# youumatter2 - Setup Guide

A step-by-step checklist for getting the youumatter2 theme live. Each step says **what** to do, **where** in WP admin, and **why** it matters. Work through it top-to-bottom on a fresh install and the site will be ready to launch.

---

## 1. Activate the theme

**Where:** Appearance > Themes
**What:** Hover over "youumatter2" and click **Activate**.
**Why:** Nothing else loads until the theme is the active one.

---

## 2. Create the base pages

**Where:** Pages > Add New (do this six times)
**What:** Create six pages, each with these exact slugs (set the slug under "Permalink" in the page sidebar):

| Title | Slug | Notes |
|---|---|---|
| Home | `home` | Will be the static front page. Leave the editor blank. |
| About | `about` | Auto-applies the About Layout. Leave the editor blank. |
| Contact | `contact` | Auto-applies the Contact Layout. Leave the editor blank. |
| FAQ | `faq` | Auto-applies the FAQ Layout. Leave the editor blank. |
| Blog | `blog` | Will be the posts page. Leave the editor blank. |
| Privacy Policy | `privacy-policy` | Type the policy in Gutenberg. Renders via the default page template. |
| Terms of Service | `terms` | Type the terms in Gutenberg. Renders via the default page template. |

**Why:** About / Contact / FAQ have custom layouts that auto-apply when the page slug matches. Privacy and Terms render through the generic `page.php` and need real content. Home and Blog are the "front page" and "posts page" hooks for WordPress's reading settings.

> **Tip:** if you want a different slug (e.g. `our-story` instead of `about`), open the page editor and pick "About Layout" from **Page Attributes > Template** in the sidebar. The layout works regardless of slug.

---

## 3. Set the static front page

**Where:** Settings > Reading
**What:**
- "Your homepage displays" -> **A static page**.
- Homepage: **Home**.
- Posts page: **Blog**.

**Why:** Without this, WordPress shows the latest posts on `/` instead of the designed homepage.

---

## 4. Site Identity (logo, title, tagline)

**Where:** Appearance > Customize > Site Identity
**What:**
- **Site Title:** `youumatter2` (or whatever brand name to display in browser tabs).
- **Tagline:** a one-liner used as the SEO description fallback.
- **Site Icon:** a square ~512x512 PNG (the favicon).
- **Logo:** upload Sanya's logo. Size guideline: ~200x60 PNG or SVG. Tall logos get auto-constrained to a max-height of 36-40px in the header. SVGs are supported via a built-in filter.

**Why:** The logo replaces the text wordmark in the header, mobile drawer, and footer. The tagline is a hidden but important SEO field.

---

## 5. Menus

**Where:** Appearance > Menus
**What:**
1. Create a new menu, name it "Main".
2. Add menu items: **Home**, **About**, **Blog**, **FAQ**, **Contact** (in that order).
3. Under "Menu Settings", check both **Primary Menu** and **Mobile Menu** locations. Save.
4. Optional: create a second menu called "Footer" (a small subset, e.g. About + Blog + Contact + FAQ) and assign to the **Footer Menu** location.
5. Optional: a third menu called "Legal" (Privacy Policy + Terms of Service) assigned to **Legal Menu** for the bottom-row footer.

**Why:** The theme has 4 menu locations. The mobile drawer falls back to Primary if Mobile is empty.

---

## 6. Customizer fields

**Where:** Appearance > Customize. Three sections matter:

### Contact & Social
Fill every field that has a real value. The site reads through `yum2_get_contact()` so blank fields silently use sensible defaults from `inc/config.php`.

| Field | Example |
|---|---|
| Phone (E.164) | `+919953855858` |
| Phone (display format) | `+91 99538 55858` |
| WhatsApp number (digits only) | `919953855858` |
| Contact email | `youumatter2@gmail.com` |
| Calendly event URL | `https://calendly.com/sanya-oberoi/intro` |
| Instagram URL | `https://www.instagram.com/youumatter2withsanya/` |
| Instagram handle | `@youumatter2withsanya` |
| LinkedIn URL | `https://www.linkedin.com/in/sanya-oberoi-...` |
| YouTube / Facebook / X URL | (only fill if the account exists) |
| Clinic address | `Pitampura, New Delhi` |
| Clinic hours | `Mon to Sat · 10:00 AM to 7:00 PM` |
| Currently accepting new clients | checked |

### Header & Footer
- Show "Book a Session" button in header (default on)
- Show "Accepting new clients" pill in header (default on)
- Footer tagline: short line under the wordmark
- Footer copyright line: use `%year%` to insert the current year (e.g. `(c) %year% youumatter2. Sanya Oberoi. All rights reserved.`)
- Show newsletter strip in footer (default on)
- Show gentle-invitation section on home page (default on)

### Homepage Sections
Five toggles to hide individual home sections (Inside a session, Testimonials, From the blog, FAQ, Instagram). Default: all on.

### About page
Three editable stat triples (value / suffix / label). Set a value to `0` to hide that stat row.

**Why:** Centralised contact data is read everywhere. Toggles let Sanya hide a section without code changes if she ever wants to.

---

## 7. Author profile

**Where:** Users > Profile (logged in as Sanya)
**What:**
- **Display name publicly as:** "Sanya Oberoi"
- **Biographical info:** the bio used on the single-post author card. Aim for 1-2 sentences. Falls back to a sensible default if blank.

**Why:** Single posts render this as the author byline + author-bio card. The bio also feeds into structured data (BlogPosting schema).

---

## 8. Discussion (comments)

**Where:** Settings > Discussion
**What:**
- "Allow people to submit comments on new posts" - **on**.
- "Comment author must fill out name and email" - **on**.
- "Users must be registered and logged in to comment" - **off**.
- "Email me whenever - Anyone posts a comment" - **on** (so Sanya can moderate quickly).
- "Comment must be manually approved" - **on** for the first month, then optionally off.

**Why:** The custom comment design we ship matches WordPress core comment fields. Manual approval is a soft anti-spam guard.

---

## 9. Categories

**Where:** Posts > Categories
**What:** Create these five (suggested, can be edited later):
- Anxiety
- Relationships
- Self-Growth
- For Parents
- Therapy 101

**Why:** The blog index filter pills are dynamic - whatever categories exist (with at least one published post) appear as filter chips automatically.

---

## 10. First post + sticky

**Where:** Posts > Add New
**What:** Write the first essay. In the post sidebar:
- Set a Category.
- Set a Featured Image (recommended, but optional - posts without one show a sage-light gradient placeholder).
- Add Tags (used in the post sidebar and as `#hashtag` chips below the article).
- Under "Visibility", check **Stick to the top of the blog**.

**Why:** The blog index featured-card pulls the most recent **sticky** post. Without one it falls back to the most recent regular post.

---

## 11. Behold.so Instagram (optional, post-launch)

**Where:** Sign up at https://behold.so, link Sanya's Instagram, get the embed snippet.
**What:** Open `template-parts/home/instagram-feed.php`. Replace the `$tiles` array (and the static placeholder loop) with Behold's `<div id="behold-feed">` + the script tag. The TODO comment at the top of the file marks the exact swap point.

**Why:** Phase 5a ships a static placeholder grid that looks correct visually. Behold replaces it with live Instagram once Sanya creates the account.

---

## 12. Theme Check (recommended once)

**Where:** Plugins > Add New > search "Theme Check" > install + activate.
**What:** Appearance > Theme Check > Check it.
**Why:** Reports any WP standards violations. Most warnings are docblock/tag-list nits, not blocking. Useful to run once before going live.

---

## 13. Translation (.pot file, optional)

**What:** Strings throughout the theme are wrapped in `__()` / `_e()` with the `youumatter2` text domain, so translation is ready to go. The repo doesn't ship a `.pot` file. To generate one, install [WP-CLI](https://wp-cli.org/) and run from the theme directory:

```bash
wp i18n make-pot . languages/youumatter2.pot --domain=youumatter2
```

**Why:** Only needed if Sanya wants to localise the site. English-only sites can skip.

---

## 14. Performance + caching

**Cloudflare (recommended):**
- Front the site with Cloudflare. Set page-rule cache TTL to **1 year** for `/wp-content/themes/youumatter2/assets/*`.
- Enable Brotli compression.
- Enable Auto Minify (HTML / CSS / JS).

**Hostinger:**
- Enable LiteSpeed cache (or whatever the host's built-in cache is).
- Object cache (Redis / Memcached) helps if available.

**WordPress side:**
- Don't install heavy page builders. The theme is hand-built; page builders fight it.
- If a caching plugin is needed, **WP Super Cache** or **W3 Total Cache** are fine. Avoid plugins that re-process CSS / JS - we already minify in the build step.

**Why:** The theme ships a minified main.css (~70 KB) and editor.css (~63 KB). Real fonts are ~220 KB total. With caching, cold loads should hit Lighthouse 90+ across all pillars.

---

## 15. Final pre-launch checklist

- [ ] All Customizer fields filled.
- [ ] Logo uploaded.
- [ ] Menus assigned to Primary + Mobile.
- [ ] Static homepage + Posts page set.
- [ ] At least one published post (sticky if possible).
- [ ] Privacy Policy and Terms have real content.
- [ ] Visit `/non-existent-url/` -> 404 page renders cleanly.
- [ ] Visit `/about/`, `/contact/`, `/faq/` -> all render with their custom layouts.
- [ ] Submit a test comment on a post -> renders correctly.
- [ ] Click every "Book a Session" button -> Calendly popup opens.
- [ ] Click every WhatsApp button -> opens wa.me with prefilled message.
- [ ] Mobile: hamburger opens drawer, drawer closes on link tap, bottom-nav visible on `<md` viewports.

---

## Need to change something?

- **Section copy on the homepage** (the 9 reasons, FAQ questions, testimonials, etc.) is hardcoded as PHP arrays at the top of each file under `template-parts/home/`. Open the file, edit the array, save.
- **About page sections** are under `template-parts/about/`.
- **FAQ groups** live in one place: `inc/template-functions.php` -> `yum2_faq_groups()`. The same data feeds the rendered FAQ and the FAQPage schema.
- **Default contact values** (used when Customizer fields are blank) are in `inc/config.php` -> `yum2_contact_defaults()`.

For anything more structural, contact the developer.
