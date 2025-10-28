<canvas
 id="TalentPoolChart"
 x-init="startChart($el)"
 wire:ignore
></canvas>

@script
 <script>
  console.log('test')
  window.startChart = startChart

  function startChart(TalentPoolChartEl) {
   if (window.TalentPoolChart) {
    window.TalentPoolChart.destroy()
   }

   const dataset = @js($dataset);

   //  const dataset = assessments.map(assessment => {
   //   return {
   //    label: `${assessment.employee.name} (${assessment.potential_value}, ${assessment.performance_value})`,
   //    name: assessment.employee.name,
   //    nip: assessment.employee.nip,
   //    y: assessment.performance_value,
   //    x: assessment.potential_value,
   //   }
   //  })

   const boxs = @js($boxs);

   const annotations = {}
   boxs.map(box => {
    annotations[`box_${box.label}`] = {
     drawTime: 'beforeDatasetsDraw',
     type: 'box',
     xMin: box.xMin,
     xMax: box.xMax,
     yMin: box.yMin,
     yMax: box.yMax,
     backgroundColor: box.color,
     borderColor: 'white',
     label: {
      content: box.label,
      display: true,
      font: {
       size: 32,
       weight: 700,
      }
     },
     click(context, event) {
      $data.selectedBox = {
       label: box.label,
       description: box.description,
       color: box.color,
      }

      const points = TalentPoolChart.getElementsAtEventForMode(event, 'nearest', {
       intersect: true
      }, true);

      let dataPoints = [];

      if (points.length) {
       // Mendapatkan index dataset dan data point yang diklik
       const firstPoint = points[0];
       const datasetIndex = firstPoint.datasetIndex;
       const dataIndex = firstPoint.index;
       const dataset = TalentPoolChart.data.datasets[datasetIndex];
       dataPoints = dataset.data.filter((data, index) => points.some(point => point.index === index));
      } else {
       dataPoints = dataset.filter(data => {
        return data.x > box.xMin && data.x <= box.xMax && data.y > box.yMin && data.y <= box.yMax
       })
      }

      $wire.dispatch('filter_nips', {
       nips: dataPoints.map(data => data.nip)
      })
     },
    }
   })

   const ctx = TalentPoolChartEl.getContext('2d');
   window.TalentPoolChart = new Chart(ctx, {
    type: 'scatter',
    data: {
     datasets: [{
      label: 'Talent Pool',
      data: dataset,
      backgroundColor: 'white',
      borderColor: 'blue',
      pointRadius: 2
     }]
    },
    options: {
     aspectRatio: 1.1,
     scales: {
      x: {
       title: {
        display: true,
        text: 'POTENSIAL',
        font: {
         size: 16,
         weight: 700,
        }
       },
       min: 20,
       max: 100,
       ticks: {
        stepSize: 2,
        autoSkip: false,
        maxRotation: 0,
        callback: function(value) {
         if ([20, 46, 72, 100].includes(value)) {
          return value;
         }
         return '';
        }
       }
      },
      y: {
       title: {
        display: true,
        text: 'KINERJA',
        font: {
         size: 16,
         weight: 700,
        }
       },
       min: 20,
       max: 100,
       ticks: {
        stepSize: 2,
        autoSkip: false,
        maxRotation: 0,
        callback: function(value) {
         if ([20, 46, 72, 100].includes(value)) {
          return value;
         }
         return '';
        }
       }
      }
     },
     plugins: {
      annotation: {
       annotations: annotations
      },
      tooltip: {
       callbacks: {
        label: function(context) {
         return context.raw.label;
        }
       }
      },
     },
    },
   });

   $dispatch('talent-pool-chart-loaded')
  }
 </script>
@endscript
