import type { Alpine } from "alpinejs";
import { ofetch } from "ofetch";

export default function useOfetch(alpine: Alpine) {
  alpine.magic('fetch', () => {
    return ofetch
  })
}