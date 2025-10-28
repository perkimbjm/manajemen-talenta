import type { Alpine } from "alpinejs";

type TItem = Record<string, unknown>
type TData = {
  value: string
  label: string
}

type TInputData = {
  open: boolean;
  name: string;
  triggerId: string;
  popoverId: string;
  items: TItem[];
  selecteds: Set<string>;
  search: string;
  searchKeys: string[];
  placeholder: string;
  getItemData(item: TItem): TData;
  label(): string
}

function generateRandomId(prefix: string): string {
  return `${prefix}-${Math.random().toString(36).substring(2, 9)}`;
}

export default function useDropdown(alpine: Alpine) {
  alpine.data('dropdown', (data?: Record<string, unknown>) => ({
    container: (null) as HTMLElement | null,
    open: false,
    name: 'dropdown',
    triggerId: generateRandomId('dropdown-trigger'),
    popoverId: generateRandomId('dropdown-popover'),
    outputId: generateRandomId('dropdown-output'),
    items: ([]) as TItem[],
    selecteds: (new Set<string>([])),
    search: '',
    searchKeys: [],
    placeholder: 'Pilih Data',
    getItemData(item: TItem) {
      return {
        value: item.code,
        label: item.name,
      }
    },
    filterItems(items: Record<string, unknown>[]) {
      if (!this.search) return items

      return items.filter(
        item => (
          this.searchKeys
            .some(key => String(item[key]).search(new RegExp(this.search, 'i')) > -1)
        )
      )
    },
    resetSelecteds() {
      this.selecteds.clear()
      const menu = this.container?.querySelector('.combobox-menu')
      if (!menu) return
    },

    getSelectedItem() {
      return this.items.find(item => item.code === this.selecteds.values().next().value)
    },
    addSelected(item: TItem) {
      this.selecteds.add(`${item.code}`)
    },
    removeSelected(item: TItem) {
      this.selecteds.delete(`${item.code}`)
    },
    label() {
      if (this.selecteds.size === 0) return this.placeholder

      if (this.selecteds.size > 1) return `${this.selecteds.size} data dipilih`
      const selected = this.getSelectedItem()
      if (!selected) return this.placeholder

      const data = this.getItemData(selected)

      return data?.label || this.placeholder
    },
    init() {
      this.container = this.$el
    },
    ...data
  }))
}