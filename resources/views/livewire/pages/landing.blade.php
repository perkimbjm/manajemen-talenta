<div class="text-[15px] text-black">

 <x-landing-header />

 @if (auth('web')->hasUser())
  <x-landing-hero-authenticated />
 @else
  <x-landing-hero />
  <x-landing-features />
 @endif

 <x-landing-footer />

 @script
  <script>
   const toggleOpen = document.getElementById('toggleOpen');
   const toggleClose = document.getElementById('toggleClose');
   const collapseMenu = document.getElementById('collapseMenu');

   function handleClick() {
    if (collapseMenu.style.display === 'block') {
     collapseMenu.style.display = 'none';
    } else {
     collapseMenu.style.display = 'block';
    }
   }

   toggleOpen.addEventListener('click', handleClick);
   toggleClose.addEventListener('click', handleClick);
  </script>
 @endscript
</div>
