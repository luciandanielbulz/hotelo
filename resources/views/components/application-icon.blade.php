<svg {{ $attributes->merge(['viewBox' => '0 0 32 32', 'fill' => 'currentColor']) }} xmlns="http://www.w3.org/2000/svg">
  <!-- Main Document Rectangle -->
  <rect x="4" y="2" width="18" height="24" rx="2" ry="2" fill="currentColor" stroke="none"/>
  
  <!-- Inner Document -->
  <rect x="6" y="4" width="14" height="20" rx="1" ry="1" fill="white"/>
  
  <!-- Document Lines -->
  <rect x="8" y="7" width="8" height="1" rx="0.5" fill="currentColor"/>
  <rect x="8" y="10" width="10" height="1" rx="0.5" fill="currentColor"/>
  <rect x="8" y="13" width="6" height="1" rx="0.5" fill="currentColor"/>
  
  <!-- Document Corner Fold -->
  <path d="M 18 2 L 18 6 L 22 6 Z" fill="currentColor" opacity="0.8"/>
  
  <!-- Checkmark Circle Background -->
  <circle cx="18" cy="18" r="4" fill="currentColor"/>
  
  <!-- Checkmark -->
  <path d="M 16 18 L 17.5 19.5 L 20 17" stroke="white" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
</svg>
