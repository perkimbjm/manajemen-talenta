import type { Alpine } from "alpinejs";
import { floatingUI } from "@/plugins/floating-ui/dom";
import { computePosition, type ComputePositionOptions } from "@/plugins/floating-ui/position";
import { eventHandler } from "@/helpers/dom/event";

type AnchoredData = ComputePositionOptions & {
  when?: boolean
}

const updatePosition = (el: HTMLElement, data: AnchoredData) => computePosition(
  el,
  data
)

const runAutoUpdate = (el: HTMLElement, data: AnchoredData) => {
  return floatingUI.autoUpdate(
    data.anchor, el,
    () => updatePosition(el, data)
  )
}


export default function FloatingUIPlugin(alpine: Alpine) {
  alpine.magic('floatingUI', () => {
    return floatingUI
  })

  alpine.directive('anchored', (el, params, { cleanup, effect, evaluateLater }) => {
    const getData = evaluateLater<AnchoredData>(params.expression)

    let cleanups = new Array<() => void>()

    const cleanupAll = () => {
      cleanups.map(cleanup => cleanup())
      cleanups = new Array<() => void>()
    }

    effect(() => {
      cleanupAll()
      getData((data) => {
        if (typeof data.when === 'boolean' && data.when === false) {
          cleanupAll()
          updatePosition(el, data)
          return
        }

        if (typeof data.when === 'string') {
          cleanups.unshift(eventHandler(el, data.when, () => updatePosition(el, data)))
          return
        }

        cleanups.unshift(runAutoUpdate(el, data))
      })
    })

    cleanup(() => {
      cleanupAll()
    })
  })
}