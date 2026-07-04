@props(['name', 'class' => 'w-5 h-5'])

@php
$icons = [
    'check' => [
        'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor',
        'path' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />',
    ],
    'trash' => [
        'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor',
        'path' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />',
    ],
    'pencil' => [
        'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor',
        'path' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />',
    ],
    'plus' => [
        'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor',
        'path' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />',
    ],
    'document' => [
        'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor',
        'path' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />',
    ],
    'user' => [
        'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor',
        'path' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />',
    ],
    'users' => [
        'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor',
        'path' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />',
    ],
    'chevron-right' => [
        'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor',
        'path' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />',
    ],
    'chevron-down' => [
        'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor',
        'path' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />',
    ],
    'link' => [
        'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor',
        'path' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />',
    ],
    'download' => [
        'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor',
        'path' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />',
    ],
    'x' => [
        'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor',
        'path' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />',
    ],
    'mail' => [
        'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor',
        'path' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />',
    ],
    'clock' => [
        'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor',
        'path' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />',
    ],
    'check-circle' => [
        'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor',
        'path' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />',
    ],
    'clipboard' => [
        'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor',
        'path' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />',
    ],
    'code' => [
        'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor',
        'path' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" />',
    ],
    'shield-check' => [
        'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor',
        'path' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />',
    ],
    'lock' => [
        'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor',
        'path' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 0h10.5a1.5 1.5 0 011.5 1.5v6a1.5 1.5 0 01-1.5 1.5H6.75a1.5 1.5 0 01-1.5-1.5v-6a1.5 1.5 0 011.5-1.5z" />',
    ],
    'layers' => [
        'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor',
        'path' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0l4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0l-5.571 3-5.571-3" />',
    ],
    'pen' => [
        'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor',
        'path' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />',
    ],
];

$icon = $icons[$name] ?? $icons['document'];
@endphp

<svg xmlns="http://www.w3.org/2000/svg"
     viewBox="{{ $icon['viewBox'] }}"
     fill="{{ $icon['fill'] }}"
     stroke="{{ $icon['stroke'] ?? 'none' }}"
     class="{{ $class }}"
     aria-hidden="true">{!! $icon['path'] !!}</svg>
