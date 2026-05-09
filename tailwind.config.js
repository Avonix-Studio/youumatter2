/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './*.php',
    './template-parts/**/*.php',
    './inc/**/*.php',
  ],
  theme: {
    extend: {
      colors: {
        forest: '#2B5329',
        cream: '#F2EDE3',
        'sage-light': '#D1E5D0',
        terracotta: '#C07A5A',
        ink: '#1a1a1a',
        muted: '#6b6b6b',
      },
      fontFamily: {
        heading: ['Newsreader', 'Georgia', 'serif'],
        body: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
      },
      typography: ({ theme }) => ({
        yum2: {
          css: {
            '--tw-prose-body': theme('colors.ink'),
            '--tw-prose-headings': theme('colors.forest'),
            '--tw-prose-lead': theme('colors.muted'),
            '--tw-prose-links': theme('colors.forest'),
            '--tw-prose-bold': theme('colors.ink'),
            '--tw-prose-counters': theme('colors.terracotta'),
            '--tw-prose-bullets': theme('colors.terracotta'),
            '--tw-prose-hr': theme('colors.sage-light'),
            '--tw-prose-quotes': theme('colors.ink'),
            '--tw-prose-quote-borders': theme('colors.terracotta'),
            '--tw-prose-captions': theme('colors.muted'),
            '--tw-prose-code': theme('colors.forest'),
            '--tw-prose-pre-code': theme('colors.cream'),
            '--tw-prose-pre-bg': theme('colors.forest'),
            '--tw-prose-th-borders': theme('colors.sage-light'),
            '--tw-prose-td-borders': theme('colors.sage-light'),

            fontFamily: theme('fontFamily.body').join(', '),
            fontSize: '1.0625rem',
            lineHeight: '1.75',

            'h1, h2, h3, h4, h5, h6': {
              fontFamily: theme('fontFamily.heading').join(', '),
              fontWeight: '600',
              letterSpacing: '-0.015em',
            },

            h2: {
              fontSize: '2rem',
              lineHeight: '1.2',
              marginTop: '3rem',
              marginBottom: '1rem',
              scrollMarginTop: '6rem',
            },

            h3: {
              fontSize: '1.5rem',
              lineHeight: '1.3',
              marginTop: '2rem',
              marginBottom: '0.75rem',
              scrollMarginTop: '6rem',
            },

            p: {
              marginTop: '1.25em',
              marginBottom: '1.25em',
            },

            blockquote: {
              fontFamily: theme('fontFamily.heading').join(', '),
              fontStyle: 'italic',
              fontWeight: '400',
              fontSize: '1.25rem',
              lineHeight: '1.5',
              borderLeftWidth: '3px',
              borderLeftColor: theme('colors.terracotta'),
              paddingLeft: '1.5rem',
              paddingTop: '0.5rem',
              paddingBottom: '0.5rem',
              marginTop: '2rem',
              marginBottom: '2rem',
              quotes: 'none',
            },
            'blockquote p:first-of-type::before': { content: 'none' },
            'blockquote p:last-of-type::after': { content: 'none' },

            'ul > li::marker': { color: theme('colors.terracotta') },
            'ol > li::marker': { color: theme('colors.terracotta') },

            a: {
              color: theme('colors.forest'),
              textDecoration: 'underline',
              textDecorationColor: 'rgba(43, 83, 41, 0.3)',
              textUnderlineOffset: '4px',
              fontWeight: '500',
              '&:hover': {
                textDecorationColor: theme('colors.forest'),
              },
            },

            code: {
              backgroundColor: 'rgba(209, 229, 208, 0.4)',
              padding: '0.15rem 0.4rem',
              borderRadius: '0.25rem',
              fontSize: '0.9em',
              fontWeight: '500',
            },
            'code::before': { content: 'none' },
            'code::after': { content: 'none' },

            pre: {
              backgroundColor: theme('colors.forest'),
              color: theme('colors.cream'),
              borderRadius: '1rem',
              padding: '1.5rem',
              marginTop: '2rem',
              marginBottom: '2rem',
            },

            'img, figure': {
              borderRadius: '1rem',
              marginTop: '2rem',
              marginBottom: '2rem',
            },

            figcaption: {
              fontStyle: 'italic',
              fontSize: '0.875rem',
              color: theme('colors.muted'),
              marginTop: '0.5rem',
            },

            hr: {
              borderTopWidth: '1px',
              marginTop: '3rem',
              marginBottom: '3rem',
            },

            strong: {
              fontWeight: '600',
              color: theme('colors.ink'),
            },
          },
        },
      }),
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
  ],
};
