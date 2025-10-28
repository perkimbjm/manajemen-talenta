@assets
 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endassets

@script
 <script>
  const chart = new Chart(
   document.getElementById(@json($id)), {
    type: @json($type),
    data: {
     labels: @json($labels),
     datasets: @json($dataset)
    },
    options: @json($options)
   }
  );
  Livewire.on('updateChart', data => {
   chart.data = data;
   chart.update();
  });
 </script>
@endscript

<canvas
 id="{{ $id }}"
 wire:ignore
></canvas>
