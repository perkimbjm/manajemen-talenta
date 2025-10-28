import type { Alpine } from "alpinejs"
import { dashToPascal } from "../helpers";
import type { Channel } from "laravel-echo";

const elementListeners = new Map<string, Function>()

let prevElement: HTMLElement | null = null;

export default function useEcho(alpine: Alpine) {
  alpine.directive('echo-channel', (el, directive, utils) => {
    if (!directive.expression) {
      throw new Error("Echo-channel directive requires an channel name as expression");
    }

    const channelName = utils.evaluate(directive.expression) as string

    const $channel = directive.modifiers.includes('private')
      ? window.Echo.private(channelName)
      : window.Echo.channel(channelName)

    alpine.addScopeToNode(el, { $channel })

    utils.cleanup(() => {
      window.Echo.leaveChannel(channelName)
    })
  })

  alpine.directive('echo', (el, directive, utils) => {
    if (!directive.value) {
      throw new Error("Echo directive requires an event name as value");
    }

    if (!directive.expression) {
      throw new Error("Echo directive requires an expression as callback event listener");
    }

    const $data = alpine.$data(el) as {
      $channel?: Channel
    }

    console.log('comparing prev element', prevElement == el)
    prevElement = el

    const channel = $data.$channel

    if (!channel) {
      throw new Error("Echo directive requires an echo channel instance");
    }

    let cleanups: Array<() => void> = []

    const eventNameInDashCase = directive.value as string;

    const eventName = dashToPascal(eventNameInDashCase)

    const elementId = el.id || el.closest('[wire\\:id]')?.getAttribute('wire:id')

    const listenerId = elementId ? `${elementId}-${eventName}` : eventName

    alpine.dontAutoEvaluateFunctions(function () {
      const callbackFn = utils.evaluate(directive.expression)
      if (typeof callbackFn !== 'function') {
        throw new Error("Echo directive requires an expression type of function as callback event listener");
      }

      let elementListener = elementListeners.get(listenerId)

      if (elementListener?.toString() === directive.expression) {
        console.warn('already registered', eventName, callbackFn)
        return
      } else if (elementListener) {
        console.warn('replacing event', eventName, elementListener, 'with', callbackFn)
        channel.stopListening(eventName, elementListener)
      }

      elementListeners.set(listenerId, callbackFn)
      channel.listen(eventName, callbackFn as () => void)

      cleanups.unshift(() => {
        console.warn('unregistering listener', eventName, callbackFn)
        channel.stopListening(eventName, callbackFn as () => void)
        elementListeners.delete(listenerId)
      })
    })


    utils.cleanup(() => {
      console.warn('running cleanups')
      cleanups.map(fn => fn())
      cleanups = []
    })
  })
}