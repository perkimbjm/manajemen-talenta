<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
 <meta charset="utf-8">
 <meta
  name="viewport"
  content="width=device-width, initial-scale=1"
 >
 <meta
  name="csrf-token"
  content="{{ csrf_token() }}"
 >

 <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

 <!-- Fonts -->
 <link
  rel="preconnect"
  href="https://fonts.bunny.net"
 >
 <link
 href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&family=Roboto+Mono:ital,wght@0,100..700;1,100..700&display=swap"
  rel="stylesheet"
 >

 @wireUiStyles

 <!-- Scripts -->
  <link
   href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css"
   rel="stylesheet"
  >
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
 class="font-sans antialiased bg-base-200 text-base-content"
 data-theme="light"
 x-init
 x-data
>

 <x-toastify.provider />

 <div
  id="root"
  class="min-h-screen"
 >
  {{ $slot }}
 </div>


 @livewire('wire-elements-modal')
 <x-dialog
  z-index="z-50"
  blur="md"
  align="center"
  x-data="{ open: false }"
 />

 <livewire:dynamic-modal />
 <livewire:dynamic-modal-render />
 <livewire:confirm-dialog />

 <x-notifications z-index="z-50" />
 <wireui:scripts />
</body>

</html>
