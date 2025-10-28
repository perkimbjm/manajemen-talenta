<div class="relative h-[calc(100vh-14rem)] text-white">
 <div
  class="absolute inset-x-0 -top-16 -z-10 h-[calc(100vh-10rem)] scroll-animation-2"
  x-bind:style="{
      '--animation-name-1': 'to-top',
      '--animation-name-2': 'to-transparent',
      '--animation-range-start': 0,
      '--animation-range-end': '100vh',
      '--top': '70vh',
      '--opacity': '0.5',
  }"
 >
  <img
   src="{{ asset('images/bg-1.png') }}"
   alt=""
   class="h-full w-full max-lg:object-cover"
  >
 </div>
 <div class="relative z-10 mx-auto mt-4 max-w-screen-xl px-4 text-center sm:px-6">
  <h1 class="mb-6 text-2xl font-extrabold">BKD DIKLAT KOTA BANJARMASIN</h1>
  <div class="mb-6 flex items-center justify-center gap-4">
   <img
    src="{{ asset('images/logo.png') }}"
    alt="Logo"
    class="w-28"
   />
   <h3 class="text-6xl font-bold text-primary-light">MATA ASN-KU</h3>
   <div class="w-28"></div>
  </div>
  <div class="mb-6 flex flex-col text-2xl font-extrabold md:text-4xl">
   <h2 class="flex flex-wrap justify-center gap-x-2">
    <div>
     <span class="text-primary-light">MA</span>HINTIP <span class="text-primary-light">TA</span>LENTA <span
      class="text-primary-light"
     >ASN</span>
    </div>
    <div>
     <span class="text-primary-light">K</span>OMREHENSIF DAN TERPAD<span class="text-primary-light">U</span>
    </div>
   </h2>
   <h3>PEMERINTAH KOTA BANJARMASIN</h3>
  </div>
 </div>

 <div class="absolute left-8 top-2 h-20 w-60 overflow-hidden rounded-md bg-white p-3">
  <img
   src="{{ asset('images/seribu-sungai.jpeg') }}"
   alt=""
   class="h-full w-full object-contain"
  >
 </div>
 <div class="absolute right-8 top-2 rounded-md bg-white">
  <img
   src="{{ asset('images/logo-asn-berakhlak-1024x262.png') }}"
   alt=""
   class="w-80"
  >
 </div>
</div>
