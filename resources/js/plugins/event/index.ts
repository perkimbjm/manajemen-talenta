import type { Alpine } from "alpinejs";

type GUnionString<T> = T | (string & {})
export type TEventName = GUnionString<(keyof HTMLElementEventMap)>

export function once<EventName extends TEventName>(element: HTMLElement | Document, eventName: EventName, timeout?: number) {
  const startTime = Date.now();

  const promise = new Promise(resolve => {
    let timeoutId: NodeJS.Timer
    const callback = (ev: Event) => {
      const _ev = ev as CustomEvent
      if (typeof _ev.detail === 'undefined') {
        // @ts-ignore
        _ev.detail = {}
      }

      if (_ev.detail && typeof _ev.detail === 'object' && _ev.detail?.duration === undefined) {
        const endTime = Date.now();
        const duration = endTime - startTime;
        _ev.detail.duration = duration
      }

      resolve(_ev);
      clearTimeout(timeoutId)
    }

    if (typeof timeout === 'number') {
      timeoutId = setTimeout(() => {
        element.removeEventListener(eventName, callback)
        resolve(new Error(`Promise timed out after ${timeout} ms`));
      }, timeout)
    }

    element.addEventListener(eventName, callback, {
      once: true,
    });
  });

  return promise
}


export function eventHandler<EventName extends TEventName>(
  element: HTMLElement | Document,
  eventName: EventName,
  handler: (ev: Event | CustomEvent) => void,
  options?: AddEventListenerOptions
) {
  element.addEventListener(eventName, handler, options)
  return () => {
    element.removeEventListener(eventName, handler)
  }
}


export function waitFor<EventName extends TEventName>(element: HTMLElement, eventName: EventName, handler: (detail: unknown) => boolean) {
  const startTime = Date.now();

  const promise = new Promise(resolve => {
    const callback = (ev: Event) => {
      const _ev = ev as CustomEvent
      if (typeof _ev.detail === 'undefined') {
        // @ts-ignore
        _ev.detail = {}
      }
      if (typeof _ev.detail === 'object' && _ev.detail.duration === undefined) {
        const endTime = Date.now();
        const duration = endTime - startTime;
        _ev.detail.duration = duration
      }

      if (handler(_ev.detail)) {
        resolve(_ev);
      }
    }

    element.addEventListener(eventName, callback);
  });

  return promise
}

export default function useEvent(alpine: Alpine) {
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

  alpine.magic('waitFor', () => {
    return (target: HTMLElement, eventName: keyof HTMLElementEventMap, handler: (detail: unknown) => boolean) => {
      return waitFor(target, eventName, handler)
    }
  })
}