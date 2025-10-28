import type { Alpine } from "alpinejs"
import focus from '@alpinejs/focus'

export default function useFocus(alpine: Alpine) {
  alpine.plugin(focus)
}