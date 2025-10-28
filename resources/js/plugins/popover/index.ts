import { eventHandler, once } from "@/helpers/dom/event";
import type { Alpine } from "alpinejs";

export default function useCustomPopover(alpine: Alpine) {
  alpine.data('Popover', ({ open = false }) => ({
    open: open,
    state: 'closed',
    loading: false,
    timeout: 350,
    supportsPopover(): boolean {
      // biome-ignore lint/suspicious/noPrototypeBuiltins: <explanation>
      const supported = HTMLElement.prototype.hasOwnProperty("popover")

      if (supported) {
        this.popover?.setAttribute('popover', 'manual')
      }
      return supported
    },
    popover: null as HTMLElement | null,
    cleanups: [] as Array<() => void>,
    init() {
      this.popover = this.$el
      this.supportsPopover()

      this.cleanups.unshift(() => {
        this.popover = null
      })

      this.cleanups.unshift(eventHandler(this.popover, 'toggle', (ev) => {
        const event = ev as ToggleEvent
        this.state = event.newState
        if (event.newState === 'closed') {
          this.closePopover()
        } else {
          this.showPopover()
        }
      }))
      this.cleanups.unshift(eventHandler(this.popover, 'close-popover', (ev) => {
        this.closePopover()
      }))
      this.cleanups.unshift(eventHandler(this.popover, 'show-popover', (ev) => {
        this.showPopover()
      }))

      if (open) {
        this.showPopover()
      }
    },
    destroy() {
      this.cleanups.map((listener) => {
        listener();
      })
    },
    async closePopover() {
      if (this.popover == null) return
      if (this.loading || !this.open) return

      this.loading = true
      this.open = false
      await once(this.popover, 'transitionend', this.timeout)
      this.loading = false
      if (!this.supportsPopover()) return
      this.popover?.hidePopover()
    },
    async showPopover() {
      if (this.loading) return
      if (this.popover == null) return

      if (this.supportsPopover() && this.state === 'closed') {
        this.popover.showPopover()
      }

      if (this.open) return

      this.open = true
      this.loading = true
      // await once(this.popover, 'transitionend', this.timeout)
      this.loading = false
    }
  }))
}