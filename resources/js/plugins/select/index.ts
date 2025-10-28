import type { Alpine } from "alpinejs";
import { dom } from "../helpers";

type TItem = {
  label: string
  value: string
  description?: string
}

type TData = Record<string, unknown>

export default function useSelect(alpine: Alpine) {
  alpine.data('Select', (initData?: TData) => ({
    get value() {
      return this.values[0] ?? null
    },
    set value(val) {
      if (val) {
        this.values = [val]
      } else {
        this.values = []
      }
    },
    search: '',
    open: false,
    loading: false,
    multiselect: false,
    placeholder: 'Pilih Data',
    searchPlaceholder: 'Cari...',
    values: [] as string[],
    selecteds: [] as TData[],
    dataset: [] as TData[],
    originals: [] as TData[],
    total: 0,
    async init() {
      this.loading = true
      // @ts-ignore
      if (typeof this?.asyncData === 'function') {
        // @ts-ignore
        const response = await this.asyncData()
        this.dataset = response.dataset
        this.total = response.total
      }

      if (Array.isArray(this.values)) {
        this.setSelecteds(this.values)
      }

      this.loading = false

      this.originals = this.dataset

      this.$watch('values', () => {
        this.setSelecteds(this.values)
      })
    },
    searchVisible() {
      return this.total >= 10
    },
    selectItem(item: TItem) {
      if (this.values.includes(item.value)) return
      if (!this.multiselect) {
        this.values = []
      }
      this.values.push(item.value)
      // this.setSelecteds(this.values)

      if (!this.multiselect) {
        this.$nextTick(() => {
          this.closeMenu()
        });
      }

    },
    deselectItem(item: TItem) {
      this.values = this.values.filter(value => value !== item.value)
      // this.setSelecteds(this.values)
      if (!this.multiselect) {
        this.$nextTick(() => {
          this.closeMenu()
        });
      }
    },
    toggleItem(item: TItem) {
      if (this.values.includes(item.value)) {
        this.deselectItem(item)
      } else {
        this.selectItem(item)
      }
    },
    async setSelecteds(values: string[]) {
      let selecteds = this.dataset.filter(data => values.includes(this.getItem(data).value))

      // @ts-ignore
      if (values.length > selecteds.length && typeof this.asyncData === 'function') {
        // @ts-ignore
        const response = await this.asyncData({
          'selecteds[]': values,
        })
        selecteds = response.dataset.filter((data: TData) => values.includes(this.getItem(data).value))
      }

      this.selecteds = selecteds

      this.$dispatch('selected', {
        values: values,
        selecteds: this.selecteds
      })
      return this.selecteds
    },
    async getSelecteds(values: string[]) {
      let selecteds = this.dataset.filter(data => values.includes(this.getItem(data).value))

      // @ts-ignore
      if (values.length > selecteds.length && typeof this.asyncData === 'function') {
        // @ts-ignore
        const response = await this.asyncData({
          'selecteds[]': values,
        })
        selecteds = response.dataset.filter((data: TData) => values.includes(this.getItem(data).value))
      }

      return selecteds
    },
    resetSelecteds() {
      this.values = []
      // this.selecteds = []
      this.$dispatch('reset', {
        values: [],
        selecteds: []
      })
    },
    getItem(data: TData): TItem {
      return {
        label: String(data.label),
        value: String(data.value),
        description: data.description ? String(data.description) : undefined
      }
    },
    searchValue(value: string, search: string) {
      const cleanedStr = value.replace(/\s+/g, '');
      const cleanedSearchTerm = search.replace(/\s+/g, '');
      return cleanedStr.search(new RegExp(cleanedSearchTerm, 'i')) > -1
    },
    filterData(data: TData) {
      const item = this.getItem(data)
      return this.searchValue(item.label, this.search) || this.searchValue(item.value, this.search)
    },
    filterDataset(dataset: TData[]) {
      return dataset.filter(data => this.filterData(data))
    },
    showMenu() {
      dom(this.$id('select', 'menu'))?.dispatchEvent(new CustomEvent('show-popover'))
      this.open = true
    },
    closeMenu() {
      this.open = false
      dom(this.$id('select', 'menu'))?.dispatchEvent(new CustomEvent('close-popover'))
    },
    ...initData
  }))
}