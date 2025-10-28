import type { Alpine } from "alpinejs"
import { Editor, type Content } from '@tiptap/core'
import StarterKit from '@tiptap/starter-kit'

type HeadingLevel = 1 | 2 | 3 | 4 | 5 | 6

export default function useEditor(alpine: Alpine) {
  alpine.data('editor', (content: Content) => {
    let editor: Editor // Alpine's reactive engine automatically wraps component properties in proxy objects. Attempting to use a proxied editor instance to apply a transaction will cause a "Range Error: Applying a mismatched transaction", so be sure to unwrap it using Alpine.raw(), or simply avoid storing your editor as a component property, as shown in this example.

    return {
      updatedAt: Date.now(), // force Alpine to rerender on selection change
      init() {
        const _this = this

        editor = new Editor({
          element: this.$refs.element,
          extensions: [StarterKit],
          content: content,
          onCreate({ editor }) {
            _this.updatedAt = Date.now()
          },
          onUpdate({ editor }) {
            _this.updatedAt = Date.now()
          },
          onSelectionUpdate({ editor }) {
            _this.updatedAt = Date.now()
          },
        })
      },
      isLoaded() {
        return editor
      },
      isActive(type: string, opts = {}) {
        return editor.isActive(type, opts)
      },
      toggleHeading(opts: {
        level: HeadingLevel;
      }) {
        editor.chain().toggleHeading(opts).focus().run()
      },
      toggleBold() {
        editor.chain().focus().toggleBold().run()
      },
      toggleItalic() {
        editor.chain().toggleItalic().focus().run()
      },
    }
  })
}