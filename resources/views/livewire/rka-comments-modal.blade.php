<form>
 <div x-data="editor('<p>Hello world! :-)</p>')">
  <template x-if="isLoaded()">
   <div class="mb-4 flex gap-4 rounded border px-4 py-3">
    <button
     x-on:click="toggleHeading({ level: 1 })"
     class="btn btn-ghost btn-sm [&[active]]:bg-base-200"
     :active="isActive('heading', { level: 1 }, updatedAt)"
    >
     H1
    </button>
    <button
     x-on:click="toggleBold()"
     class="btn btn-ghost btn-sm [&[active]]:bg-base-200"
     :active="isActive('bold', updatedAt)"
    >
     Bold
    </button>
    <button
     x-on:click="toggleItalic()"
     class="btn btn-ghost btn-sm [&[active]]:bg-base-200"
     :active="isActive('italic', updatedAt)"
    >
     Italic
    </button>
   </div>
  </template>

  <div
   x-ref="element"
   class="prose rounded border px-4 py-3 prose-p:my-0 [&>[contenteditable=true]]:outline-none"
  ></div>
 </div>
</form>
