import type { Alpine } from "alpinejs";
import DOMPurify from "dompurify";

export default function useIcons(alpine: Alpine) {
  alpine.directive('icon', (element, metadata) => {
    import(`_/icons/${metadata.expression}.svg?raw`).then((svg) => {
      element.innerHTML = DOMPurify.sanitize(svg.default)
    })
  })
}