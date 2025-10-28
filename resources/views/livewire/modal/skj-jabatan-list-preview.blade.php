<div
 class="@container"
 max-width="screen-lg"
>
 <h2 class="mb-8 text-xl font-bold text-gray-700">SKJ {{ $position->name }}</h2>
 <div class="grid grid-cols-1 gap-6 @xl:grid-cols-2 @2xl:grid-cols-3">
  @foreach ($position->getMedia('skj') as $media)
   @php
    $path = route('files.show', [
        'disk' => $media->disk,
        'path' => "{$media->id}/{$media->file_name}",
    ]);
   @endphp
   <div class="group relative flex gap-2 overflow-hidden rounded-md border bg-white p-2">
    <div class="flex h-full w-20 shrink-0 items-center justify-center rounded bg-rose-500">
     <span class="i-mdi-file-pdf h-full w-full text-white"></span>
    </div>
    <div class="flex h-full w-full flex-col gap-1">
     <h3 class="min-h-10 line-clamp-2 text-base font-semibold leading-5 text-gray-800">{{ $media->file_name }}
     </h3>
     <p class="mb-4 text-xs font-semibold text-orange-500">
      {{ $media->created_at->format('d F Y') }}
     </p>
     <div
      class="bottom-2 right-2 mt-auto flex justify-end gap-2 rounded bg-white/25 p-1 backdrop-blur-sm transition-opacity group-hover:pointer-events-auto group-hover:opacity-100 sm:pointer-events-none sm:absolute sm:opacity-0"
     >
      <a
       href="{{ $path }}"
       download="{{ $media->file_name }}"
       class="btn btn-outline btn-primary btn-sm bg-white"
       x-on:click.self="$toastify({
         type: 'info',
         message: 'Silahkan tunggu file sedang didownload!',
       })"
      >Download</a>
      <button
       type="button"
       class="btn btn-accent btn-sm text-white"
       x-on:click="$wire.dispatch('openModal', {
        'component': 'modal.pdf-preview',
        'arguments': {
          'url': '{{ $path }}'
         }
       })"
      >Preview</button>
     </div>
    </div>
   </div>
  @endforeach
 </div>
</div>
