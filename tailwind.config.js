/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './src/**/*.{js,jsx,ts,tsx}',
    './blocks/**/*.{js,jsx,ts,tsx}',
    './integrity-properties.php',
    './inc/**/*.php'
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#008001',
          '50': '#edfded',
          '100': '#d5f9d5',
          '200': '#aef2af',
          '300': '#7be67d',
          '400': '#4ad44c',
          '500': '#2abb2c',
          '600': '#1c9b1e',
          '700': '#16781a',
          '800': '#146018',
          '900': '#0f4f15',
          '950': '#042b07',
        },
        secondary: {
          DEFAULT: '#75c93d',
          '50': '#f4fbeb',
          '100': '#e6f6d4',
          '200': '#ceecac',
          '300': '#aedc79',
          '400': '#8bc94f',
          '500': '#75c93d',
          '600': '#59a527',
          '700': '#427e1f',
          '800': '#36641d',
          '900': '#2d531b',
          '950': '#172e0b',
        },
      },
    },
  },
  plugins: [
    require('@tailwindcss/container-queries'),
  ],
}
