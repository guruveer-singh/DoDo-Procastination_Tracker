<template>
  <div class="flex flex-wrap gap-1 p-2 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
    <button
      @click="editor.chain().focus().toggleBold().run()"
      :class="['p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700', { 'bg-gray-200 dark:bg-gray-700': editor.isActive('bold') }]"
      title="Bold"
    >
      <i class="material-icons-round text-sm">format_bold</i>
    </button>
    <button
      @click="editor.chain().focus().toggleItalic().run()"
      :class="['p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700', { 'bg-gray-200 dark:bg-gray-700': editor.isActive('italic') }]"
      title="Italic"
    >
      <i class="material-icons-round text-sm">format_italic</i>
    </button>
    <button
      @click="editor.chain().focus().toggleStrike().run()"
      :class="['p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700', { 'bg-gray-200 dark:bg-gray-700': editor.isActive('strike') }]"
      title="Strikethrough"
    >
      <i class="material-icons-round text-sm">strikethrough_s</i>
    </button>
    <div class="w-px h-6 bg-gray-300 dark:bg-gray-600 my-1"></div>
    <button
      @click="editor.chain().focus().toggleHeading({ level: 1 }).run()"
      :class="['p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700', { 'bg-gray-200 dark:bg-gray-700': editor.isActive('heading', { level: 1 }) }]"
      title="Heading 1"
    >
      <i class="material-icons-round text-sm">title</i>
    </button>
    <button
      @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
      :class="['p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700', { 'bg-gray-200 dark:bg-gray-700': editor.isActive('heading', { level: 2 }) }]"
      title="Heading 2"
    >
      <i class="material-icons-round text-sm">text_fields</i>
    </button>
    <div class="w-px h-6 bg-gray-300 dark:bg-gray-600 my-1"></div>
    <button
      @click="editor.chain().focus().toggleBulletList().run()"
      :class="['p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700', { 'bg-gray-200 dark:bg-gray-700': editor.isActive('bulletList') }]"
      title="Bullet List"
    >
      <i class="material-icons-round text-sm">format_list_bulleted</i>
    </button>
    <button
      @click="editor.chain().focus().toggleOrderedList().run()"
      :class="['p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700', { 'bg-gray-200 dark:bg-gray-700': editor.isActive('orderedList') }]"
      title="Numbered List"
    >
      <i class="material-icons-round text-sm">format_list_numbered</i>
    </button>
    <div class="w-px h-6 bg-gray-300 dark:bg-gray-600 my-1"></div>
    <button
      @click="editor.chain().focus().toggleCodeBlock().run()"
      :class="['p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700', { 'bg-gray-200 dark:bg-gray-700': editor.isActive('codeBlock') }]"
      title="Code Block"
    >
      <i class="material-icons-round text-sm">code</i>
    </button>
    <button
      @click="editor.chain().focus().toggleBlockquote().run()"
      :class="['p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700', { 'bg-gray-200 dark:bg-gray-700': editor.isActive('blockquote') }]"
      title="Blockquote"
    >
      <i class="material-icons-round text-sm">format_quote</i>
    </button>
    <div class="w-px h-6 bg-gray-300 dark:bg-gray-600 my-1"></div>
    <button
      @click="editor.chain().focus().setHorizontalRule().run()"
      class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700"
      title="Horizontal Rule"
    >
      <i class="material-icons-round text-sm">horizontal_rule</i>
    </button>
    <button
      @click="editor.chain().focus().undo().run()"
      class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700"
      title="Undo"
    >
      <i class="material-icons-round text-sm">undo</i>
    </button>
    <button
      @click="editor.chain().focus().redo().run()"
      class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700"
      title="Redo"
    >
      <i class="material-icons-round text-sm">redo</i>
    </button>
  </div>
</template>

<script>
export default {
  props: {
    editor: {
      type: Object,
      required: true,
    },
  },
}
</script>
