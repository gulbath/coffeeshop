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
        espresso: {
          900: '#1C100B',
          800: '#2C1A1D',
          700: '#3D2314',
          600: '#5A3622',
        },
        cream: {
          100: '#FAF6F0',
          200: '#F5EBE6',
          300: '#E8D8CE',
        },
        gold: {
          500: '#D4AF37',
          600: '#B89028',
        }
      }
    },
  },
  plugins: [],
}