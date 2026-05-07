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
    },
  },
  plugins: [],
};
