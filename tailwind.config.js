/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./views/**/*.twig"],
  theme: {
    extend: {
      colors: {
        "bit-blue": "#000958",
        "bit-gray": "#1D2225",
        "bit-light-gray": "#778085",
        "bit-light-puple": "#B8B5C1",
        "bit-gray-pruple": "#ADACAF",
        "bit-bg-gray":"#EEEEEE"
      }
    },
  },

}