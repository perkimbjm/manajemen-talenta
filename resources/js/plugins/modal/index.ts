import type { Alpine } from 'alpinejs';
import { eventHandler, once } from '@/plugins/event';

export default function ModalPlugin(alpine: Alpine) {
  alpine.directive('modal', (el, params, { cleanup }) => {
    const cleanups: Array<() => void> = []

    const dialog = el as HTMLDialogElement
    const data = alpine.$data(el) as { prevActiveDialog?: HTMLDialogElement };
    const originalShowModal = dialog.showModal;
    dialog.showModal = () => {
      // Call the original showModal method
      originalShowModal.call(dialog);
      // Dispatch a custom event
      dialog.dispatchEvent(new CustomEvent('showmodal'));

      const otherDialog = document.querySelector('dialog[open][active]') as HTMLDialogElement | null;
      otherDialog?.removeAttribute('active')

      if (otherDialog) {
        const data = alpine.$data(el) as { prevActiveDialog?: HTMLDialogElement };
        data.prevActiveDialog = otherDialog
      }
      dialog.setAttribute('active', '')
      cleanups.unshift(() => {
        data.prevActiveDialog = undefined
        dialog.removeAttribute('active')
      })
    };

    async function onCloseModal(ev: Event) {
      dialog.removeAttribute('active')
      if (data.prevActiveDialog) {
        data.prevActiveDialog.setAttribute('active', '')
        data.prevActiveDialog = undefined
      }

      await once(dialog, 'transitionend', 3000);
      dialog.dispatchEvent(new CustomEvent('closed'));
    }

    cleanups.unshift(eventHandler(dialog, 'close', onCloseModal));

    cleanup(() => {
      cleanups.map(fn => fn())
    })
  })

}