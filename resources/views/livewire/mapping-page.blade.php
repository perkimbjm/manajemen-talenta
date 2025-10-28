<main class="px-4 py-6">
 <section class="flex flex-col gap-6">
  <article class="card-compact card bg-white shadow">
   <div class="card-body">
    <header class="card-title">
     <h2 class="text-lg font-semibold">SKPD SIM-ASN Mapping</h2>
    </header>
    <livewire:unit-mapping-table />
   </div>
  </article>

  <article class="card-compact card bg-white shadow">
   <div class="card-body">
    <header class="card-title">
     <h2 class="text-lg font-semibold">SKPD TPP Mapping</h2>
    </header>
    <livewire:tpp-unit-mapping-table />
   </div>
  </article>
 </section>

 <template
  id="SimpleFormTemplate"
  max-height="240px"
 >
  <div class="skeleton h-32"></div>
 </template>
</main>
