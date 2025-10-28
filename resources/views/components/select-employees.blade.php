@props(['model' => 'nip'])

<x-my-select
 x-data="Select({
     placeholder: 'Pilih Pegawai',
     getItem(data) {
 
         return {
             label: String(data.name),
             value: String(data.nip),
             description: String(`${data.nip} ${data.unit?.name ? '- ' + data.unit?.name : ''}`)
         }
     },
     asyncData(params = {}) {
         params.search = this.search
         return $fetch('{{ route('api.employees.index') }}', {
             params: params,
         }).then(res => ({
             total: res.total,
             dataset: res.data,
         }))
     }
 })"
 x-modelable="value"
 wire:model.live="{{ $model }}"
 x-on:selected="$dispatch('urlchange')"
/>
