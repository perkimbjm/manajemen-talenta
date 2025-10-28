<section class="bg-gradient-to-b from-white/0 via-white/95 to-white pb-32">
 <hr class="my-12 border-gray-300" />
 <div class="mx-auto mt-32 max-w-screen-xl px-4 sm:px-6">
  <div class="mx-auto mb-16 max-w-2xl text-center">
   <h2 class="mb-6 text-3xl font-extrabold md:text-4xl">
    FITUR APLIKASI
   </h2>
  </div>
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
