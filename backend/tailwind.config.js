import colors from 'tailwindcss/colors';

export default {
  content: [
    './app/Filament/**/*.php',
    './resources/views/**/*.blade.php',
    './vendor/filament/**/*.blade.php',
  ],
  theme: {
    extend: {
      colors: {
        // Primary Emerald Gradient (Growth/Success)
        emerald: {
          50: '#f0fdf4',
          100: '#dcfce7',
          200: '#bbf7d0',
          300: '#86efac',
          350: '#68dba9', // Lime lighter
          400: '#4ade80',
          500: '#27d384', // Light
          600: '#16a34a',
          700: '#0f9361', // Base
          800: '#166534',
          900: '#006948', // Deep
        },
        // Amber (Warnings/Highlights)
        amber: {
          50: '#fffbeb',
          100: '#fef3c7',
          200: '#fde68a',
          300: '#fcd34d',
          350: '#ffc580', // Pale
          400: '#fbbf24',
          500: '#fe932c', // Light
          600: '#d67800', // Base
          700: '#b45309',
          800: '#8b5a00',
          900: '#904d00', // Deep
        },
        // Violet (System/Special)
        violet: {
          50: '#f5f3ff',
          100: '#ede8ff', // Pale
          200: '#ddd2ff',
          300: '#d2bbff', // Light
          400: '#c4b5fd',
          500: '#a78bfa',
          600: '#9d5dff', // Base
          700: '#7c3aed',
          800: '#6d28d9',
          900: '#712ae2', // Deep
        },
        // Surface Scale (No pure greys)
        surface: {
          0: '#faf8ff', // Base
          50: '#f2f3ff', // Container Low
          100: '#e8e8f0', // Container Mid
          150: '#d9d9e8', // Container High
          200: '#cac9d8', // Outline Variant
          inverse: '#131b2e', // On Surface
        },
        // Semantic
        error: {
          50: '#fef2f2',
          100: '#fee2e2',
          300: '#fca5a5',
          500: '#ef4444',
          600: '#dc2626',
          700: '#b91c1c',
          900: '#c5192d', // Primary error
        },
        success: {
          50: '#f0fdf4',
          100: '#dcfce7',
          500: '#10b981',
          600: '#059669',
          700: '#047857',
          900: '#2d7c2d',
        },
        warning: {
          50: '#fffbeb',
          100: '#fef3c7',
          500: '#eab308',
          600: '#ca8a04',
          700: '#a16207',
          900: '#cc7c1a',
        },
        info: {
          50: '#f0f9ff',
          100: '#e0f2fe',
          500: '#0ea5e9',
          600: '#0284c7',
          700: '#0369a1',
          900: '#0066cc',
        },
      },
      fontFamily: {
        manrope: ['Manrope', 'system-ui', 'sans-serif'], // Headlines
        inter: ['Inter', 'system-ui', 'sans-serif'], // Body
        mono: ['Courier New', 'monospace'], // Table data
      },
      fontSize: {
        // Headlines (Manrope, Bold)
        'display-lg': ['48px', { lineHeight: '3.5rem', fontWeight: '700' }],
        'display-md': ['40px', { lineHeight: '2.5rem', fontWeight: '700' }],
        'headline-lg': ['28px', { lineHeight: '1.75rem', fontWeight: '700' }],
        'headline-md': ['24px', { lineHeight: '1.5rem', fontWeight: '700' }],
        'headline-sm': ['20px', { lineHeight: '1.25rem', fontWeight: '700' }],
        // Body (Inter, Regular)
        'body-lg': ['16px', { lineHeight: '1.5rem', fontWeight: '400' }],
        'body-md': ['14px', { lineHeight: '1.5rem', fontWeight: '400' }],
        'body-sm': ['12px', { lineHeight: '1.5rem', fontWeight: '400' }],
        'label-sm': ['11px', { lineHeight: '0.6875rem', fontWeight: '500' }],
      },
      spacing: {
        0: '0',
        1: '4px',
        2: '8px',
        3: '12px',
        4: '16px',
        5: '20px',
        6: '24px',
        8: '32px',
        10: '40px',
      },
      borderRadius: {
        sm: '8px',
        md: '12px',
        lg: '16px',
        xl: '20px',
        '2xl': '24px',
      },
      boxShadow: {
        // Ambient shadows (Soft natural light)
        sm: '0 2px 8px rgba(19, 27, 46, 0.04)',
        md: '0 4px 16px rgba(19, 27, 46, 0.06)',
        lg: '0 8px 32px rgba(19, 27, 46, 0.08)',
        xl: '0 16px 48px rgba(19, 27, 46, 0.12)',
        // Float shadows (Elevation)
        'lift-sm': '0 4px 16px rgba(19, 27, 46, 0.06)',
        'lift-md': '0 8px 32px rgba(19, 27, 46, 0.10)',
        'lift-lg': '0 12px 40px rgba(19, 27, 46, 0.14)',
      },
      backdropBlur: {
        xs: '4px',
        sm: '8px',
        md: '12px',
        lg: '16px',
        xl: '24px',
        '2xl': '32px',
        '3xl': '40px',
      },
      backgroundImage: {
        'gradient-emerald': 'linear-gradient(135deg, #006948 0%, #27d384 100%)',
        'gradient-amber': 'linear-gradient(135deg, #904d00 0%, #fe932c 100%)',
        'gradient-violet': 'linear-gradient(135deg, #712ae2 0%, #d2bbff 100%)',
        'gradient-emerald-lime': 'linear-gradient(135deg, #0f9361 0%, #68dba9 100%)',
      },
      animation: {
        'pulse-soft': 'pulse-soft 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite',
        'pulse-fast': 'pulse-fast 1s cubic-bezier(0.4, 0, 0.6, 1) infinite',
        'entrance': 'entrance 300ms cubic-bezier(0.34, 1.56, 0.64, 1)',
      },
      keyframes: {
        'pulse-soft': {
          '0%, 100%': { opacity: '1' },
          '50%': { opacity: '0.4' },
        },
        'pulse-fast': {
          '0%, 100%': { opacity: '1' },
          '50%': { opacity: '0.5' },
        },
        'entrance': {
          'from': { opacity: '0', transform: 'translateY(-10px)' },
          'to': { opacity: '1', transform: 'translateY(0)' },
        },
      },
    },
  },
  plugins: [],
};
