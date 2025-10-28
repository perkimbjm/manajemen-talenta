<label class="input flex items-center pl-0">
 <input
  type="text"
  class="w-full border-none px-2 focus:border-none focus:outline-none focus:ring-0"
  x-model.debounce.500ms="search"
  x-bind:placeholder="searchPlaceholder"
  x-on:input.debounce.1000ms="async () => {
     dataset = await asyncData().then(response => response.dataset)
   }"
 />
 <span class="i-lucide-search h-4 w-4 shrink-0 text-base-content opacity-70"></span>
</label>
