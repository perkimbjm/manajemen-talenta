<div class="relative text-white">
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
  <div class="mb-2 flex items-center justify-center gap-4">
   <img
    src="{{ asset('images/logo.png') }}"
    alt="Logo"
    class="w-12"
   />
   <h3 class="text-4xl font-bold text-primary-light">MATA ASN-KU</h3>
   <div class="w-12"></div>
  </div>
  <div class="mb-4 flex flex-col text-2xl font-extrabold">
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

 <section class="bg-gradient-to-b from-white/0 via-white/95 to-white pb-32 text-gray-900">
  <div class="mx-auto max-w-screen-xl px-4 sm:px-6">
   <div class="mx-auto grid gap-4 max-md:max-w-lg md:grid-cols-2 xl:grid-cols-4">
    <x-landing-feature
     :link="route('features.skj')"
     :icon="asset('images/features/SKJ.png')"
     title="SKJ"
     description="Standar Kompetensi Jabatan."
    />
    <x-landing-feature
     :link="route('features.manja')"
     :icon="asset('images/features/MANJA.png')"
     title="MANJA ASN"
     description="Manajemen Kinerja ASN"
    />
    <x-landing-feature
     :link="route('features.assessment-center')"
     :icon="asset('images/features/ASSESSMENT-CENTER.png')"
     title="Assessment Center"
     description="Asesmen potensi, nilai asesmen kompetensi, Job Person Match."
    />
    <x-landing-feature
     :link="route('features.asn-potensial')"
     :icon="asset('images/features/ASN-POTENSIAL.png')"
     title="ASN Potensial"
     description="Hasil pengolahan potensial ASN yang diolah berdasarkan Potensi, Kompetensi, ..."
    />
    <x-landing-feature
     :link="route('features.profil-talenta-asn')"
     :icon="asset('images/features/PROFIL-TALENTA-ASN.png')"
     description="Berisi fitur pencarian talenta ASN"
    >
     <x-slot name="title">
      Profil <span class="whitespace-nowrap">Talenta ASN</span>
     </x-slot>
    </x-landing-feature>
    <x-landing-feature
     :link="route('features.talent-pool')"
     :icon="asset('images/features/TALENT-POOL.png')"
     title="Talent Pool"
     description="Memuat 9 kotak talenta stasiun kerja antara kinerja dengan potensial, ..."
    />
    <x-landing-feature
     link="#"
     :icon="asset('images/features/POLA-KARIER.png')"
     title="Pola Karier"
     description="Berisi alur pola karier ASN yang bisa ditempuh oleh setiap ASN."
    />
    <x-landing-feature
     link="#"
     :icon="asset('images/features/MANAJEMAN-BANGKOMP.png')"
     title="Manajemen BANGKOMP"
     description="Rekomendasi pengembangan kompetensi berdasarkan kinerja ASN."
    />
   </div>
  </div>
 </section>
</div>
