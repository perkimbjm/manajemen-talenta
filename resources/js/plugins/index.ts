import type { Livewire } from "$/livewire/livewire/dist/livewire.esm";
import type Alpine from "alpinejs";
import type Echo from "laravel-echo";

declare global {
  interface Window {
    Alpine: typeof Alpine;
    Echo: Echo
    Livewire: typeof Livewire
  }
}

type Module = { default?: () => unknown }
const plugins: Record<string, Module> = import.meta.glob('./**/index.ts', { eager: true })

document.addEventListener("alpine:init", () => {
  for (const path in plugins) {
    const module = plugins[path].default
    if (!module) continue;

    window.Alpine.plugin([module])
  }
})
