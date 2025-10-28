<?php

use Livewire\Volt\Component;
use App\Models\Menu;

new class extends Component {
    public $show = false;

    public function with(): array
    {
        $user = auth()->user();
        $menus = Menu::sidebar();

        return compact('user', 'menus');
    }
};

?>

<!--tw class="i-mdi-view-dashboard" -->

<aside
 class="sidebar"
 x-data="{
     show: false,
     forced: false,
 }"
 x-on:showsidebar.window="show = true"
 x-on:hidesidebar.window="(ev) => {
  if(ev.detail.force) {
    force = true
  }
  show = false
 }"
 x-on:togglesidebar.window="(ev) => {
   if(!forced && ev.detail.force === true) {
     show = false
     forced = true
   } else {
     show = !show
   }
 }"
 x-bind:open="show"
 x-bind:close="!show"
 x-bind:forced="forced",
>
 <div
  class="fixed inset-0 z-50 bg-gray-900/50"
  x-cloak
  x-show="show"
  x-transition.opacity
  x-on:click="show = false"
 ></div>
 <nav
  class="sidebar-overlay fixed left-0 top-0 z-50 flex h-screen w-64 flex-col overflow-hidden bg-gradient-to-b from-slate-900 via-slate-800 to-slate-900 font-[sans-serif] text-white shadow-2xl transition-all duration-300 ease-in-out max-md:-translate-x-64 lg:w-72"
  x-bind:class="{
      '-translate-x-64 lg:-translate-x-72': !show && forced,
      'max-md:!-translate-x-0': show
  }"
 >
  <header class="sticky top-0 z-10 bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-5 shadow-lg">
   <a
    href="{{ route('landing') }}"
    class="flex items-center gap-3 transition-transform duration-200 hover:scale-105"
    x-vision
   >
    <div class="relative">
     <img
      src="{{ asset('images/logo.png') }}"
      alt="Logo"
      class="w-10 h-10 rounded-lg shadow-lg"
     />
     <div class="absolute -inset-1 bg-gradient-to-r from-blue-400 to-purple-400 rounded-lg blur opacity-25"></div>
    </div>
    <div>
     <h3 class="text-xl font-bold text-white tracking-wide">MATA ASN-KU</h3>
     <p class="text-xs text-blue-100 opacity-75">Management System</p>
    </div>
   </a>
  </header>

  <!-- Navigation Menu -->
  <div class="flex-1 overflow-y-auto px-3 py-4 scrollbar-thin scrollbar-thumb-slate-600 scrollbar-track-transparent">
   <nav class="space-y-1">
    @foreach ($menus as $menu)
     @if (!@$menu['roles'] || $user->hasAnyRole($menu['roles']))
      <x-sidebar.menu
       :menu="$menu"
       :user="$user"
      />
     @endif
    @endforeach
   </nav>
  </div>

  <!-- User Profile Section -->
  <div class="sticky bottom-0 z-20 mt-auto border-t border-slate-600/50 bg-gradient-to-r from-slate-800/90 to-slate-700/90 backdrop-blur-sm">
   <div class="relative p-4">
    <div class="flex items-center gap-3 mb-3">
     <div class="relative">
      <div class="size-12 rounded-full ring-2 ring-blue-400/50 overflow-hidden">
       <img
        alt="{{ $user->name }}"
        src="https://api.dicebear.com/9.x/fun-emoji/svg?seed={{ $user->name }}&backgroundType=gradientLinear,solid&mouth=lilSmile,faceMask,plain,smileTeeth&eyes=shades,glasses,plain,closed"
        class="w-full h-full object-cover"
       />
      </div>
      <div class="absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-green-400 border-2 border-slate-800 rounded-full"></div>
     </div>
     
     <div class="flex-1 min-w-0">
      <p class="text-sm font-semibold text-white truncate" title="{{ $user->name }}">
       {{ $user->name }}
      </p>
      <p class="text-xs text-slate-300 truncate" title="{{ $user->email }}">
       {{ $user->email }}
      </p>
      <div class="flex items-center gap-1 mt-1">
       <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
       <span class="text-xs text-green-400">Online</span>
      </div>
     </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="flex gap-2">
     <a href="#" class="flex-1 bg-slate-700/50 hover:bg-slate-600/50 rounded-lg px-3 py-2 text-xs text-center text-slate-300 hover:text-white transition-colors duration-200">
      <i class="i-mdi-account-edit w-4 h-4 mx-auto mb-1"></i>
      Profile
     </a>
     
     <form
      action="{{ route('logout') }}"
      method="POST"
      class="flex-1"
      x-on:submit.prevent="(ev) => {
        $dispatch('confirm-modal', {
          content: 'Apakah anda yakin akan logout?',
          confirmText: 'Yakin',
          confirmAction: () => $el.submit(),
        })
     }"
     >
      @csrf
      <button
       type="submit"
       class="w-full bg-red-600/20 hover:bg-red-600/30 rounded-lg px-3 py-2 text-xs text-red-300 hover:text-red-200 transition-colors duration-200"
      >
       <i class="i-mdi-logout w-4 h-4 mx-auto mb-1"></i>
       Logout
      </button>
     </form>
    </div>
   </div>
  </div>
 </nav>
</aside>
