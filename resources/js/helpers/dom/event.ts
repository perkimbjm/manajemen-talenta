type GUnionString<T> = T | (string & {})
export type TEventName = GUnionString<(keyof HTMLElementEventMap)>

export async function promiseWithTimeout(promise: Promise<unknown>, ms?: number) {
  if (!ms) return promise

  // Create a timeout promise
  let timeoutId: Timer
  const timeout = new Promise((resolve) => {
    timeoutId = setTimeout(() => {
      resolve(new Error(`Promise timed out after ${ms} ms`));
    }, ms);
  });

  // Race the input promise against the timeout
  return Promise.race([promise, timeout]).finally(() => {
    clearTimeout(timeoutId);
  });
}

export function timeout(times: number) {
  return new Promise(resolve => {
    setTimeout(() => {
      resolve(true);
    }, times);
  });
}

export function once<EventName extends TEventName>(element: HTMLElement, eventName: EventName, timeout?: number) {
  const startTime = Date.now();

  const promise = new Promise(resolve => {
    let timeoutId: Timer
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
  element: HTMLElement,
  eventName: EventName,
  handler: (ev: Event | CustomEvent) => void,
  options?: EventListenerOptions
) {
  element.addEventListener(eventName, handler, options)
  return () => {
    element.removeEventListener(eventName, handler)
  }
}