<x-layouts.app :title="@$title">
 <div class="flex flex-col items-center justify-center gap-4 py-6">
  <a
   href="{{ route('landing') }}"
   class="flex items-center gap-3"
   x-vision
  >
   {{-- <x-application-logo-white-outline class="w-[198px] object-contain" /> --}}
   <img
    src="{{ asset('images/logo.png') }}"
    alt="Logo"
    class="w-10"
   />
   <h3 class="text-2xl font-bold text-primary">MATA ASN-KU</h3>
  </a>

  <div class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md dark:bg-gray-800 sm:max-w-md sm:rounded-lg">
   {{ $slot }}
  </div>
 </div>
</x-layouts.app>
