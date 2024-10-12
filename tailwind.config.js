/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  content: [
   "./resources/**/*.blade.php",
   "./resources/**/*.js",
   "./resources/**/*.vue",
   "./node_modules/flowbite/**/*.js"
  ],
  theme: {
    extend: {},
  },
  safelist: [
    {
      pattern: /^datatable-.*/,
    },
  ],
  plugins: [
    require('flowbite/plugin')({
        charts: true,
    }),
  ],
}

