import type { Alpine } from "alpinejs";
import { eventHandler, once } from "../event";

let navigating = false;

export function startNavigate(url: string) {
  if (!document.startViewTransition) {
    window.Livewire.navigate(url);
    return;
  }

  document.startViewTransition(async () => {
    window.Livewire.navigate(url);
    await once(document, "livewire:navigated", 480);
    navigating = false
  });
};

export default function useNavigation(alpine: Alpine) {
  const elementMap = new Map();
  elementMap.set("HTMLAnchorElement", {
    events: ["click", "keyup.Enter"],
    urlAttributes: ["href"],
  });

  document.addEventListener('livewire:navigate', (ev) => {
    if (!document.startViewTransition) {
      return;
    }

    const context = (ev as CustomEvent).detail
    if (!navigating) {
      ev.preventDefault();
      navigating = true
    } else {
      return
    }

    startNavigate(context.url);
  })

  alpine.magic('startNavigate', () => {
    return startNavigate
  })

  alpine.directive("vision", (el, { expression }, { cleanup, evaluate }) => {
    const elementType = elementMap.get(el.constructor.name);
    if (!elementType) return;

    let url = "";

    if (expression === "x-vision") {
      url = "";
    } else if (typeof expression === "string" && expression !== "") {
      url = evaluate(expression);
    }

    if (!url) {
      const urlAttribute = elementType.urlAttributes.find((attr: string) => {
        return (
          el.hasAttribute(attr) &&
          el.getAttribute(attr) !== "" &&
          el.getAttribute(attr) !== "#"
        );
      });

      if (urlAttribute) {
        url = el.getAttribute(urlAttribute) || "";
      }
    }

    if (!url) return;

    const cleanups: Array<() => void> = [];

    elementType.events.map((eventName: string) => {
      cleanups.unshift(
        eventHandler(el, eventName, (ev) => {
          ev.preventDefault();
          ev.stopPropagation();
          startNavigate(url);
        }),
      );
    });

    cleanup(() => {
      cleanups.map((cleanup) => cleanup());
    });
  });
}
