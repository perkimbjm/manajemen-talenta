<div
 x-data="{
     rowId: templateContent.statusTemplate.id,
     status: templateContent.statusTemplate.status,
     status_label: templateContent.statusTemplate.status_label,
     verifyEvent: templateContent.statusTemplate.verifyEvent ?? 'verify-row',
 }"
 x-id="['row']"
>
 @can('Ubah Status Penilaian')
  <button
   class="btn no-animation btn-sm"
   x-bind:id="$id('row', 'status-label')"
   x-on:click="$dom($id('row', 'status-menu')).dispatchEvent(new CustomEvent('show-popover'))"
   x-text="status_label"
  >
  </button>
  <div
   x-data="Popover({})"
   x-bind:id="$id('row', 'status-menu')"
   x-show="open"
   x-transition
   x-cloak
   x-trap.noreturn="open"
   x-on:click.outside="closePopover()"
   x-anchored="{
            when: open,
            anchor: $dom($id('row', 'status-label')),
            offset: 8,
            padding: 8,
            placement: 'bottom-start',
          }"
   class="popover rounded-md border bg-white p-2 shadow"
  >
   <div class="grid gap-2">
    <button
     class="btn btn-ghost btn-sm justify-start text-left"
     x-bind:class="{
         'disabled': status > 0,
     }"
     x-on:click="$dispatch('confirm-modal', {
              content: 'Apakah anda yakin akan menolak data ini?',
              prompt: true,
              promptPlaceholder: 'Alasan Penolakan',
              disableConfirm: (promptInput) => promptInput === '',
              confirmAction: (promptInput) => $wire.changeStatus(rowId, 1, promptInput)
             })"
    >Ditolak</button>
    <button
     class="btn btn-ghost btn-sm justify-start text-left"
     x-bind:class="{
         'disabled': status > 0,
     }"
     x-on:click="$dispatch(verifyEvent, {
              rowId: rowId,
             })"
    >Diverifikasi</button>
   </div>
  </div>
 @else
  <span x-text="status_label"></span>
 @endcan
</div>
