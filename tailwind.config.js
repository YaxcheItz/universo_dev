/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'universo': {
          'dark': '#0f0e17',
          'secondary': '#1a1825',
          'border': '#3e3a52',
          'purple': '#7c3aed',
          'cyan': '#06b6d4',
          'success': '#10b981',
          'warning': '#f59e0b',
          'text': '#e2e1e7',
          'text-muted': '#9d99ac',
        }
      },
      fontFamily: {
        'sans': ['Inter', 'system-ui', 'sans-serif'],
        'mono': ['Fira Code', 'monospace'],
      },
    },
  },
  plugins: [],
}
