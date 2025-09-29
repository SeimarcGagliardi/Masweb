import defaultTheme from 'tailwindcss/defaultTheme'
import forms from '@tailwindcss/forms'
import typography from '@tailwindcss/typography'

/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class',
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', ...defaultTheme.fontFamily.sans],
      },
      colors: {
        // Tema sobrio: slate per neutri, brand blu “calmo” per CTA
        brand: {
          50:  '#eff6ff',
          100: '#dbeafe',
          200: '#bfdbfe',
          300: '#93c5fd',
          400: '#60a5fa',
          500: '#3b82f6', // brand
          600: '#2563eb', // brand-strong
          700: '#1d4ed8',
          800: '#1e40af',
          900: '#1e3a8a',
        },
      },
    },
  },
  safelist: [
    // Evita problemi con classi dinamiche: tieni una base di colori usati
    'bg-brand-600','hover:bg-brand-700','text-brand-600','border-brand-600',
    'bg-emerald-600','hover:bg-emerald-700','text-emerald-600',
    'bg-amber-600','hover:bg-amber-700','text-amber-600',
    'bg-rose-600','hover:bg-rose-700','text-rose-600',
  ],
  plugins: [forms, typography],
}
