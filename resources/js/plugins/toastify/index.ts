import type { Alpine } from "alpinejs";
import Toastify from 'toastify-js'
import { getTostifyTemplate } from "./template";
import "toastify-js/src/toastify.css"
import { eventHandler } from "@/plugins/event";

let listeners = Array<{ element: HTMLElement | Document, type: string, removeListener: () => void }>()

function handleRemovedSelector(alpine: Alpine, selector: HTMLElement) {
  const listener = listeners.find(listener => {
    return listener.type === 'close' && listener.element === selector
  })

  if (!listener) {
    const removeListener = eventHandler(selector, 'close', () => {
      const state = alpine.$data(selector) as {
        prevActiveModal?: HTMLElement
      }

      let newSelector = document.body

      if (state.prevActiveModal) {
        newSelector = state.prevActiveModal
      }

      // biome-ignore lint/complexity/noForEach: <explanation>
      selector
        .querySelectorAll('.toastify')
        .forEach(el => newSelector.appendChild(el))

      listeners = listeners.filter(listener => {
        return listener.type === 'close' && listener.element !== selector
      })
    }, {
      once: true
    })

    listeners.push({
      element: selector,
      type: 'close',
      removeListener
    })
  }
}

function handleNewModal(alpine: Alpine) {
  const listener = listeners.find(listener => {
    return listener.type === 'modal-rendered'
  })

  if (listener) return

  const removeListener = eventHandler(document, 'modal-rendered', (ev) => {
    const key = ev instanceof CustomEvent ? ev.detail.id : undefined
    if (!key) return

    const modal = document.getElementById(`modal-${key}`)
    if (!modal) return


    // biome-ignore lint/complexity/noForEach: <explanation>
    document
      .querySelectorAll('.toastify')
      .forEach(el => modal.appendChild(el))

    handleRemovedSelector(alpine, modal)
  })

  listeners.push({
    element: document,
    type: 'modal-rendered',
    removeListener
  })
}

export default function ToastifyPlugin(alpine: Alpine) {
  alpine.magic('toastify', () => {
    return (params: { type?: string, message: string, description?: string } & Toastify.Options) => {
      const template = getTostifyTemplate(params)

      let selector: HTMLElement | undefined = undefined

      const openedDialogs: NodeListOf<HTMLElement> = document.querySelectorAll('dialog[open]')
      if (openedDialogs.length > 1) {
        const activeDialog: HTMLElement | null = document.querySelector('dialog[open][active]')
        if (activeDialog) {
          selector = activeDialog
        }
      } else {
        selector = openedDialogs[0]
      }

      if (selector) {
        handleRemovedSelector(alpine, selector)
      }

      handleNewModal(alpine)

      const toast = Toastify({
        selector,
        close: true,
        node: template,
        className: '[&>.toast-close]:text-gray-300 [&>.toast-close]:text-xs [&>.toast-close]:-mr-2 [&>.toast-close]:hover:opacity-100 gap-2 group flex items-start !border !border-gray-100 bg-white !shadow-[0_5px_15px_-3px_rgb(0_0_0_/_0.08)] transition-all duration-300 ease-out sm:!rounded-md !py-3 !px-4 max-md:!max-w-[calc(100%-32px)]',
        style: {
          background: 'white',
          display: 'flex',
          alignItems: 'center',
          minHeight: params.description ? '62px' : 'auto',
        },
        stopOnFocus: true,
        ...params,
      })

      toast.showToast()

      return toast
    }
  })
}