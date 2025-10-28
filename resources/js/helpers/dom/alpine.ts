import type { Alpine } from "alpinejs";
import { once, eventHandler, type TEventName } from "./event";

export default function (alpine: Alpine) {
  alpine.magic("dom", () => {
    return (domId: string) => document.getElementById(domId);
  });
  alpine.magic("domQuery", () => {
    return (query: string, el?: HTMLElement) => {
      if (el) {
        return el.querySelector(query);
      }

      return document.querySelector(query);
    };
  });
  alpine.magic("domQueryAll", () => {
    return (query: string, el?: HTMLElement) => {
      if (el) {
        return el.querySelectorAll(query);
      }

      return document.querySelectorAll(query);
    };
  });
  alpine.magic("thisQuery", (el: HTMLElement) => {
    return (query: string) => el.querySelector(query);
  });
  alpine.magic("thisQueryAll", (el: HTMLElement) => {
    return (query: string) => el.querySelectorAll(query);
  });

  alpine.magic('once', (el: HTMLElement) => {
    return (eventName: keyof HTMLElementEventMap, _elOrTimeout?: HTMLElement | number, timeout?: number) => {
      if (typeof _elOrTimeout === 'number') {
        return once(el, eventName, _elOrTimeout)
      }

      return once(_elOrTimeout || el, eventName, timeout)
    }
  })

  alpine.magic('eventHandler', (el: HTMLElement) => {
    return (eventName: TEventName, handler: (ev: Event | CustomEvent) => void, _elOrOptions?: HTMLElement | EventListenerOptions, options?: EventListenerOptions) => {
      if (typeof _elOrOptions === 'undefined') {
        return eventHandler(el, eventName, handler)
      }

      if (_elOrOptions instanceof HTMLElement) {
        return eventHandler(_elOrOptions, eventName, handler, options)
      }

      return eventHandler(el, eventName, handler, _elOrOptions)
    }
  })
}
