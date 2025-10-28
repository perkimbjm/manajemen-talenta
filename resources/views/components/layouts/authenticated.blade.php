<x-layouts.app :title="@$title">
 <livewire:layout.sidebar />
 <div class="sidebar-forced:ml-0 md:ml-64 lg:ml-72">
  @if (isset($header))
   <x-header
    :class="$header->attributes->get('class', '')"
    :sticky="$header->attributes->get('sticky')"
   >
    {{ $header }}
   </x-header>
  @endif
  @if (isset($subheader))
   <x-subheader :class="$subheader->attributes->get('class', '')" :sticky="$subheader->attributes->get('sticky')">
    {{ $subheader }}
   </x-subheader>
  @endif

  <!-- Page Heading -->

  <!-- Page Content -->
  <main class="min-h-screen">
   {{ $slot }}
  </main>
 </div>

 <div class="fixed bottom-3 left-4 z-50 transition-transform sidebar-open:translate-x-64 md:hidden">
  <label
   class="swap-rotate btn swap border border-gray-200 bg-base-100 p-1 text-base-content/75 shadow-xl sidebar-open:swap-active hover:text-base-content"
   x-on:click="() => {
     $dispatch('togglesidebar')
   }"
  >

   <!-- hamburger icon -->
   <i class="swap-off i-mdi-menu h-8 w-8"></i>
   <i class="swap-on i-mdi-close h-8 w-8"></i>
  </label>
 </div>

 {{-- <div class="fixed bottom-[5rem] left-[17rem] z-50 transition-transform max-md:hidden">
  <label
   class="min-h-0 p-1 border border-gray-200 shadow-xl swap-rotate btn swap bg-base-100 text-base-content/75 hover:text-base-content"
   x-on:click="() => {
     $dispatch('togglesidebar', {
       force: true
     })
   }"
  >

   <!-- hamburger icon -->
   <i class="w-6 h-6 swap-off i-mdi-menu"></i>
   <i class="w-6 h-6 swap-on i-mdi-close"></i>
  </label>
 </div> --}}
</x-layouts.app>
