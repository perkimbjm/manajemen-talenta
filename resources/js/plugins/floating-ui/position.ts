import Alpine from "alpinejs"
import { floatingUI } from "@/plugins/floating-ui/dom"
import type { Boundary, ComputePositionReturn, Placement } from "@floating-ui/dom"

export type ComputedPositionResult = ComputePositionReturn & ComputePositionOptions

export type ComputePositionOptions = {
  anchor: HTMLElement
  strategy?: 'absolute' | 'fixed'
  placement?: Placement
  offset?: number
  boundary?: Boundary
  padding?: number,
  adjustWidth?: number,
}

function processComputedPosition(
  element: HTMLElement, { x, y, placement, boundary, padding, adjustWidth }: ComputedPositionResult) {
  x = Math.max(0, x)

  element.dataset.placement = placement
  Object.assign(element.style, {
    left: `${x}px`,
    top: `${y}px`,
  });

  if (boundary instanceof HTMLElement) {
    element.style.maxWidth = `${boundary.clientWidth - (padding || 0) + (adjustWidth || 0)}px`
  }
}

export const throttleComputedPosition = Alpine.throttle(processComputedPosition, 30)
export const throttleComputePosition = Alpine.throttle(computePosition, 30)

export async function computePosition(
  el: HTMLElement, options: ComputePositionOptions
) {
  return floatingUI.computePosition(options.anchor, el, {
    placement: options.placement,
    strategy: options.strategy || 'fixed',
    middleware: [
      floatingUI.offset(options.offset),
      floatingUI.flip({
        fallbackStrategy: 'initialPlacement',
      }),
      floatingUI.shift({
        padding: options.padding,
        boundary: options.boundary,
      }),
    ]
  }).then(({ x, y, placement, strategy, middlewareData }) => {
    processComputedPosition(el, {
      ...options,
      x, y, placement, strategy, middlewareData,
    })
  })
}

export async function computeAndThrottlePosition(
  el: HTMLElement, options: ComputePositionOptions
) {
  return floatingUI.computePosition(options.anchor, el, {
    placement: options.placement,
    strategy: options.strategy || 'fixed',
    middleware: [
      floatingUI.offset(options.offset),
      floatingUI.flip({
        fallbackStrategy: 'initialPlacement',
      }),
      floatingUI.shift({
        padding: options.padding,
        boundary: options.boundary,
      }),
    ]
  }).then(({ x, y, placement, strategy, middlewareData }) => {
    throttleComputedPosition(el, {
      ...options,
      x, y, placement, strategy, middlewareData,
    })
  })
}