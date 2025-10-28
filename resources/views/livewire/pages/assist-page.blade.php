@use('Illuminate\Support\Carbon', 'Carbon')

<x-slot
 name="header"
 :sticky="false"
>
 <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
  {{ __('Rencana Kerja dan Anggaran') }}
 </h2>
</x-slot>

<x-slot name="subheader">
 @php
  $paths = ['Asistensi', 'RKA'];
  if (@$comment_to) {
      $paths[] = $comment_to->sub_activity_code;
  }
 @endphp

 <x-breadcrumbs :paths="$paths" />
</x-slot>

<div @class([
    'items-start gap-2 px-4 py-6 xl:px-8',
    'lg:grid grid-cols-2' => !!$comment_to,
])>
 <!--
   Heads up! 👋
 
   This component comes with some `rtl` classes. Please remove them if they are not needed in your project.
 -->

 @if (@$comment_to)
  <div
   class="card card-compact sticky top-10 z-10 order-last mb-6 shrink-0 rounded-md bg-base-100 shadow-lg lg:mb-0"
   x-data="{
       assist_id: '{{ $comment_to->id }}',
   }"
  >
   <div class="grid gap-1 divide-y px-4 py-2 shadow-sm">
    <div class="relative flex items-center gap-2">
     <h3 class="mr-auto font-mono text-sm">
      {{ $comment_to->sub_activity_code }}
     </h3>
     <div
      class="tooltip tooltip-bottom"
      data-tip="Lihat File"
     >
      <a
       href="{{ $comment_to->filepath }}"
       target="_blank"
       referrerpolicy="no-referrer"
       class="btn btn-ghost btn-sm min-h-0 bg-rose-400 p-1 text-white ring-rose-500/50 hover:bg-rose-500"
      >
       <i class="i-mdi-file-pdf h-5 w-5"></i>
      </a>
     </div>
     <div
      class="tooltip tooltip-left"
      data-tip="Tutup Komentar"
     >
      <a
       href="{{ route('assist.rka') }}"
       class="btn btn-ghost btn-active btn-sm min-h-0 p-1"
       x-vision
      >
       <i class="i-mdi-close h-5 w-5"></i>
      </a>
     </div>
    </div>
    <div>
     {{ $comment_to->title }}
    </div>
   </div>
   <div
    class="card-body max-h-[calc(100vh-400px)] flex-col-reverse gap-4 overflow-y-auto"
    x-on:scrollto-lastcomment.window="() => {
      $el.scrollTo({
        top: $el.scrollHeight,
        behavior: 'smooth'
      });
    }"
   >
    @foreach ($comment_to->comments as $comment)
     @php
      $is_current_user = $comment->created_by === auth('web')->user()->id;
     @endphp
     <div
      @class([
          'chat',
          'chat-end' => $is_current_user,
          'chat-start' => $is_current_user,
      ])
      wire:key="{{ $comment->id }}"
     >
      <div class="chat-header">
       {{ $comment->created_by_user->name }}
       @php
        $created_at = $comment->created_at->addHours(8);
        $now = Carbon::now()->addHours(8);
       @endphp
       <time class="text-xs opacity-50">
        @if ($created_at->diffInMinutes($now) <= 60)
         {{ $comment->created_at->diffForHumans() }}
        @elseif ($created_at->diffInHours($now) <= 24)
         {{ $created_at->format('H:i') }}
        @else
         {{ $created_at->format('Y-m-d H:i') }}
        @endif
       </time>
      </div>
      <div class="chat-bubble">
       <div class="prose prose-sm text-base-100 prose-p:my-0">
        {!! $comment->body !!}
       </div>
      </div>
      {{-- <div class="chat-footer opacity-50">Seen</div> --}}
     </div>
    @endforeach
   </div>

   @persist('assist_comment_input')
    <div
     class="px-2 py-2"
     x-on:send-comment="(e) => {
      console.log('🚀 ~ e:', e)
      const editor = e.detail.$editor
      $wire.dispatch('save-comment', {
        assist: assist_id,
        comment: editor.getHTML(),
      })
      editor.commands.clearContent()
    }"
     wire:ignore
    >
     <x-comment-input />
    </div>
   @endpersist
  </div>
 @endif


 <div class="card mb-4 w-full shrink rounded-md bg-base-100 px-2 py-4 shadow-lg">
  <div class="mb-4 flex gap-4">
   <div
    class="tooltip"
    data-tip="Upload RKA"
    x-uppy="{
      restrictions: {
        allowedFileTypes: ['application/pdf'],
      }
    }"
    x-uppy:dashboard="{ 
      inline: false, 
      trigger: '#UploadRKATrigger', 
      allowedFileTypes: ['application/pdf'],
    }"
    x-uppy:tus
    x-on:upload-complete="(ev) => {
      const successful = ev.detail.successful

      if(successful) {
        $wire.dispatch('upload-rka', {
          dataset: successful
        })
        $toastify({
          message: `Berhasil Mengupload Berkas (${successful.length})`,
          duration: 12000,
        })
      }
    }"
   >
    <button
     class="btn btn-primary btn-sm min-h-0 p-1"
     type="button"
     id="UploadRKATrigger"
    >
     <i class="i-mdi-upload h-5 w-5"></i>
    </button>
   </div>
  </div>
  <div class="overflow-x-auto rounded-md border">
   <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
    <thead class="ltr:text-left rtl:text-right">
     <tr>
      <th class="whitespace-nowrap px-4 py-2 text-left font-medium text-gray-900">Kode Sub Kegiatan</th>
      <th class="whitespace-nowrap px-4 py-2 text-left font-medium text-gray-900">Berkas</th>
      <th class="whitespace-nowrap px-4 py-2 text-left font-medium text-gray-900">Keterangan</th>
      <th class="whitespace-nowrap px-4 py-2 text-left font-medium text-gray-900">Komentar</th>
     </tr>
    </thead>

    <tbody class="divide-y divide-gray-200">
     @forelse ($assists as $assist)
      <tr class="odd:bg-gray-50">
       <td class="whitespace-nowrap px-4 py-2 align-top font-mono text-gray-900">{{ $assist->sub_activity_code }}</td>
       <td class="px-4 py-2 align-top text-gray-700">
        <p class="group">
         <a
          href="{{ $assist->filepath }}"
          class="group-hover:link group-hover:link-primary"
          target="_blank"
          referrerpolicy="no-referrer"
         >
          {{ $assist->filename }}
         </a>
        </p>
       </td>
       <td class="px-4 py-2 align-top text-gray-700">
        <p class="max-w-sm">
         {{ $assist->title }}
        </p>
       </td>
       <td class="px-4 py-2 align-top text-gray-700">
        <div
         class="tooltip"
         data-tip="Lihat Komentar"
        >
         <div class="indicator">
          @if ($assist->comments->count() > 0)
           <span class="badge indicator-item badge-primary h-auto px-1 text-xs text-white">
            {{ $assist->comments->count() }}
           </span>
          @endif
          <a
           class="btn btn-secondary btn-sm min-h-0 p-1 pb-0"
           href="{{ route('assist.rka', [
               'comment_to' => $assist->id,
           ]) }}"
           x-vision
          >
           <i class="i-mdi-comment h-5 w-5"></i>
          </a>
         </div>
        </div>
       </td>
      </tr>
     @empty
      <tr class="odd:bg-gray-50">
       <td
        class="whitespace-nowrap px-4 py-4 text-center font-medium text-gray-400"
        colspan="4"
       >
        <p class="pointer-events-none">
         Belum Ada Data
        </p>
       </td>
      </tr>
     @endforelse
    </tbody>
   </table>
  </div>

  <div class="mt-4 px-4">
   {{ $assists->onEachSide(1)->links() }}
  </div>
 </div>

</div>
