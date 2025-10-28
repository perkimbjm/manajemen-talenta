import { createElement, html } from '@/lit-html'
import { bool, text } from '@/plugins/helpers'

export function getTostifyTemplate(params: { type?: string, message: string, description?: string }) {
  const type = params.type || 'default'
  const description = params.description || ''

  const typesIcon = {
    info: 'i-mdi-information-slab-circle', success: 'i-mdi-check-circle', warning: 'i-mdi-warning-circle', danger: 'i-mdi-close-circle'
  }

  const typesClassess = { 'success': 'text-green-500', info: 'text-blue-500', warning: 'text-orange-400', danger: 'text-red-500', 'default': 'text-gray-800' }

  const data = {
    type: type,
    message: text(params.message),
    description: description ? text(description) : '',
  }

  const divClasses = text(typesClassess[data.type as keyof typeof typesClassess])
  const icon = data.type in typesIcon ? typesIcon[data.type as keyof typeof typesIcon] : ''

  const template = html`
    <div
      class="group relative"
      role="toast"
    >
      <div
      class="min-h-5 flex items-center ${divClasses}"
      >

        <template x-if="${bool(icon)}">
          <span class="-ml-2 mr-1.5 h-[18px] w-[18px] flex-shrink-0 ${text(icon)}"></span>
        </template>

        <p
          class="line-clamp-1 text-[13px] font-medium leading-none text-gray-800 transition-all group-hover:line-clamp-none break-all"
          x-text="'${data.message}'"
          role="toast-message"
        ></p>
      </div>
      <p
      x-show="${bool(data.description)}"
      x-bind:class="{ 'pl-4': ${bool(icon)} }"
      class="line-clamp-1 text-xs leading-4 text-gray-500 opacity-70 transition-all group-hover:mt-1.5 group-hover:line-clamp-none break-all"
      x-text="'${data.description}'"
      ></p>
    </div>`

  return createElement(template)
}