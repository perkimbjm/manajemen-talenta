<div class="mx-auto grid h-[400vh] max-w-screen-sm gap-6 px-4 py-6 text-xl sm:px-6">
 <div>
  <x-my-select x-data="Select({
      getItem(data) {
          return {
              label: String(data.name),
              value: String(data.code),
              description: String(data.code)
          }
      },
      asyncData() {
          return $fetch('https://sipejabat-v2.banjarmasinkota.go.id/api/units?is_root=1').then(res => ({
              total: res.data.length,
              dataset: res.data,
          }))
      }
  })" />
 </div>

 <div class="card card-compact mt-80 bg-white">
  <div
   class="card-body"
   x-data="{
       supportAnchor: ('anchorName' in document.documentElement.style)
   }"
  >
   <x-my-select.container
    x-data="Select({
        getItem(data) {
            return {
                label: String(data.name),
                value: String(data.code),
                description: String(data.code)
            }
        },
        asyncData() {
            return $fetch('https://sipejabat-v2.banjarmasinkota.go.id/api/units?is_root=1').then(res => ({
                total: res.data.length,
                dataset: res.data,
            }))
        }
    })"
    x-init="() => {
        $watch('selecteds', () => {
            $dom('native-popover').hidePopover()
        })
    }"
   >
    <x-my-select.trigger
     popovertarget="native-popover"
     id="trigger-popover"
     @style(['anchor-name: --trigger-popover'])
    />
    <x-my-select.trigger-indicator />
    <div
     popover
     id="native-popover"
     class="native-popover anchor-bottom-left min-h-64 w-full bg-transparent"
     @style(['position-anchor: --trigger-popover'])
     x-bind:style="{
         maxWidth: `anchor-size(width)`,
     }"
    >
     <div class="grid overflow-hidden rounded-md border bg-white shadow-lg">
      <template x-if="searchVisible">
       <div
        class="sticky top-0 z-10 bg-white px-2 py-2"
        role="searchbox"
       >
        <x-my-select.search-input />
       </div>
      </template>
      <ul
       class="relative flex max-h-[calc(70vh-2.5rem)] flex-col overflow-y-auto sm:max-h-[calc(50vh-2.5rem)] [[role=searchbox]+&]:max-h-[calc(70vh-6rem)] sm:[[role=searchbox]+&]:max-h-[calc(50vh-6rem)]"
       x-bind:class="{
           'h-[calc(70vh-2.5rem)] sm:h-[calc(50vh-2.5rem)]': total >= 10,
       }"
      >
       <template x-if="dataset.length === 0">
        <x-my-select.empty />
       </template>
       <template
        x-for="data in filterDataset(dataset)"
        :key="getItem(data).value"
       >
        <x-my-select.option>
         <x-my-select.option-label />
         <x-my-select.option-indicator />
        </x-my-select.option>
       </template>
       <template x-if="total">
        <x-my-select.total-indicator />
       </template>
      </ul>
     </div>
    </div>
   </x-my-select.container>
  </div>
 </div>

 <div class="flex gap-4 rounded-md border bg-white px-4 py-3">
  <div>
   <x-pin-input x-model="$wire.pins" />
  </div>
  <button
   class="btn"
   x-on:click="$wire.pins[2] = 9"
  >Change Pin 3</button>
 </div>
 <div class="rounded-md border bg-white px-4 py-3">
  <x-pin-input :pins="$pins" />
 </div>

 <div class="rounded-md border bg-white px-4 py-3">
  <div
   id="source"
   x-data="{ count: 0 }"
   x-cloak
  >
   <button x-on:click="count++">Increase Count</button>
   <p>Count: <span x-text="count"></span></p>
  </div>

  <div x-data="{ moveElement() { $el.appendChild(document.getElementById('source')) } }">
   <button x-on:click="moveElement()">Move Element</button>
  </div>
 </div>
