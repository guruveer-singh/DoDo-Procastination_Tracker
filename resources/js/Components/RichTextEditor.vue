<template>
  <div class="rich-text-editor">
    <MenuBar v-if="editor" :editor="editor" class="mb-2 border-b border-gray-200 dark:border-gray-700 pb-2" />
    <editor-content :editor="editor" class="prose dark:prose-invert max-w-none" />
  </div>
</template>

<script>
import { Editor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import MenuBar from './MenuBar.vue'

export default {
  components: {
    EditorContent,
    MenuBar,
  },

  props: {
    modelValue: {
      type: String,
      default: '',
    },
  },

  emits: ['update:modelValue'],

  data() {
    return {
      editor: null,
    }
  },

  watch: {
    modelValue(value) {
      const isSame = this.editor.getHTML() === value
      if (isSame) {
        return
      }
      this.editor.commands.setContent(value, false)
    },
  },

  mounted() {
    this.editor = new Editor({
      content: this.modelValue,
      extensions: [
        StarterKit,
      ],
      onUpdate: () => {
        this.$emit('update:modelValue', this.editor.getHTML())
      },
      editorProps: {
        attributes: {
          class: 'prose dark:prose-invert max-w-none focus:outline-none p-4 min-h-[200px]',
        },
      },
    })
  },

  beforeUnmount() {
    this.editor.destroy()
  },
}
</script>

<style>
.rich-text-editor {
  @apply border border-gray-300 dark:border-gray-600 rounded-md overflow-hidden;
}

.rich-text-editor:focus-within {
  @apply ring-2 ring-blue-500 border-blue-500;
}

.rich-text-editor .ProseMirror {
  @apply p-4 min-h-[200px];
}

.rich-text-editor .ProseMirror:focus {
  @apply outline-none;
}

.rich-text-editor .ProseMirror > * + * {
  @apply mt-4;
}

.rich-text-editor .ProseMirror ul,
.rich-text-editor .ProseMirror ol {
  @apply pl-6;
}

.rich-text-editor .ProseMirror ul {
  @apply list-disc;
}

.rich-text-editor .ProseMirror ol {
  @apply list-decimal;
}

.rich-text-editor .ProseMirror h1 {
  @apply text-2xl font-bold;
}

.rich-text-editor .ProseMirror h2 {
  @apply text-xl font-semibold;
}

.rich-text-editor .ProseMirror h3 {
  @apply text-lg font-medium;
}

.rich-text-editor .ProseMirror pre {
  @apply bg-gray-100 dark:bg-gray-800 p-4 rounded-md overflow-x-auto;
}

.rich-text-editor .ProseMirror code {
  @apply bg-gray-100 dark:bg-gray-800 text-red-600 dark:text-red-400 px-1 py-0.5 rounded text-sm;
}

.rich-text-editor .ProseMirror blockquote {
  @apply border-l-4 border-gray-300 dark:border-gray-600 pl-4 italic;
}

.rich-text-editor .ProseMirror hr {
  @apply border-t border-gray-300 dark:border-gray-600 my-4;
}
</style>
