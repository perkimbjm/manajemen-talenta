@props(['content' => ''])

<div
 x-data="editor('{!! $content !!}')"
 class="rounded border"
>
 <template x-if="isLoaded()">
  <div class="flex gap-1 border-b p-2">
   <button
    x-on:click="toggleBold()"
    class="btn btn-ghost btn-sm min-h-0 w-8 py-1 [&[active]]:bg-base-200"
    :active="isActive('bold', updatedAt)"
   >
    B
   </button>
   <button
    x-on:click="toggleItalic()"
    class="btn btn-ghost btn-sm min-h-0 w-8 py-1 [&[active]]:bg-base-200"
    :active="isActive('italic', updatedAt)"
   >
    <span class="italic">
     i
    </span>
   </button>

   <div class="ml-auto"></div>

   <button
    x-on:click="$dispatch('scrollto-lastcomment')"
    class="btn btn-ghost btn-sm min-h-0 w-8 p-0 [&[active]]:bg-base-200"
   >
    <span class="i-mdi-arrow-down h-5 w-5">
    </span>
   </button>

   <button
    class="btn btn-primary btn-sm min-h-0 py-1"
    x-on:click="$dispatch('send-comment', {
      $editor: isLoaded(),
   })"
   >
    Kirim
   </button>
  </div>
 </template>

 <div
  x-ref="element"
  class="prose max-h-32 min-h-[52px] overflow-y-auto px-4 py-3 prose-p:my-0 [&>[contenteditable=true]]:outline-none"
  x-on:keydown.ctrl.enter="$dispatch('send-comment', {
    $editor: isLoaded(),
  })"
 ></div>
</div>
