import type { Alpine } from 'alpinejs'
// @ts-ignore / ts(2307): Cannot find module 'alpinejs-tash'.
import tash from 'alpinejs-tash'

export default function useTash(alpine: Alpine) {
  alpine.plugin(tash)
}