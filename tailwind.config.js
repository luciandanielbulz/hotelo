// tailwind.config.mjs
export default {
    content: [
      "./resources/**/*.blade.php",
      "./resources/**/*.js",
      "./resources/**/*.vue",
      "./node_modules/flowbite/**/*.js",
    ],
    theme: {
      extend: {
        colors: {
          // Flowbite v4 Design Tokens
          'neutral-primary': '#ffffff',
          'neutral-primary-medium': '#f9fafb',
          'neutral-secondary-soft': '#f3f4f6',
          'neutral-tertiary': '#e5e7eb',
          'neutral-tertiary-medium': '#d1d5db',
          'brand': '#3b82f6',
          'heading': '#111827',
          'body': '#6b7280',
          'fg-brand': '#2563eb',
        },
        borderColor: {
          'default': '#e5e7eb',
          'default-medium': '#d1d5db',
        },
        borderRadius: {
          'base': '0.5rem',
        },
      },
    },
    plugins: [
      require('flowbite/plugin')
    ],
  };
