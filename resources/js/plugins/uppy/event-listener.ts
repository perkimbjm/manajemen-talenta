import type { Uppy, Body, Meta, UppyEventMap } from '@uppy/core';
import type { Alpine } from 'alpinejs';

export default function useUppyEventListener(alpine: Alpine) {
  alpine.directive('uppy-on', (el, { expression, value }, { evaluate, evaluateLater, cleanup, effect }) => {
    if (!value) {
      throw new Error('Uppy-on directive requires an uppy event name as value')
    }

    const cleanups: Array<() => void> = []


    const data = alpine.closestDataStack(el).at(0) as {
      $uppy?: Uppy
    }

    if (!data.$uppy) {
      throw new Error('Uppy-on directive requires an uppy instance')
    }
    const getCallback = expression ? evaluateLater(expression) : undefined

    effect(() => {
      alpine.dontAutoEvaluateFunctions(() => {
        getCallback?.((callbackFn) => {
          data.$uppy?.on(value as keyof UppyEventMap<Meta, Body>, callbackFn as () => void)

          cleanups.unshift(() => {
            data.$uppy?.off(value as keyof UppyEventMap<Meta, Body>, callbackFn as () => void)
          })

        })
      })
    })


    cleanup(() => {
      cleanups.map(fn => fn())
    })

  })
}