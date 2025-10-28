<header
 class="sticky top-0 z-50 overflow-hidden rounded-b-[35px] bg-gradient-to-b from-primary-light to-primary-dark px-4 py-4 transition-all scroll-animation-2 sm:px-10"
 x-bind:style="{
     '--animation-name-1': 'to-shadow',
     '--animation-name-2': 'to-compact',
     '--animation-range-start': 0,
     '--animation-range-end': '140px',
 }"
>
 <div class='relative mx-auto flex max-w-screen-xl flex-wrap items-center gap-4 px-4 sm:px-6'>
  <a
   href="javascript:void(0)"
   class="flex items-center gap-2"
  >
   <img
    src="{{ asset('images/logo.png') }}"
    alt="Logo"
    class="w-8"
   />
   <h3 class="text-2xl font-bold text-white">MATA ASN-KU</h3>
  </a>

  <div
   id="collapseMenu"
   class='z-50 max-lg:fixed max-lg:hidden max-lg:before:fixed max-lg:before:inset-0 max-lg:before:z-50 max-lg:before:bg-black max-lg:before:opacity-50 lg:!block'
  >
   <button
    id="toggleClose"
    class="fixed right-4 top-2 z-[100] flex items-center justify-center rounded-full bg-white p-3 lg:hidden"
   >
    <span class="i-mdi-close h-5 w-5"></span>
   </button>

   <ul
    class='z-50 gap-x-6 max-lg:fixed max-lg:left-0 max-lg:top-0 max-lg:h-full max-lg:w-1/2 max-lg:min-w-[300px] max-lg:space-y-3 max-lg:overflow-auto max-lg:bg-white max-lg:p-6 max-lg:shadow-md lg:ml-12 lg:flex'
   >
    <li class='mb-6 hidden max-lg:block'>
     <a href="javascript:void(0)"><img
       src="https://readymadeui.com/readymadeui.svg"
       alt="logo"
       class='w-36'
      />
     </a>
    </li>
   </ul>
  </div>

  <div class='ml-auto flex'>
   @guest
    <a
     href="{{ route('login') }}"
     x-vision
     class='btn btn-primary btn-sm'
    >Login</a>
   @else
    <a
     href="{{ route('dashboard') }}"
     class="btn btn-primary btn-sm mr-4"
     wire:navigate
    >
     Dashboard
    </a>
    <button
     type="button"
     x-on:click.prevent="(ev) => {
        $wireui.confirmDialog({
            title: 'Logout!',
            description: 'Apakah anda yakin akan logout?',
            icon: 'warning',
            accept: { 
              label: 'Yakin', 
              execute: () => $wire.logout()
          },
        })
       }"
     class='btn btn-accent btn-sm text-white'
    >Logout</button>
   @endguest
   <button
    id="toggleOpen"
    class='ml-7 flex items-center lg:hidden'
   >
    <span class="i-lucide-menu h-6 w-6"></span>
   </button>
  </div>
 </div>
</header>
