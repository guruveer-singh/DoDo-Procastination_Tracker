import './bootstrap';
import { createApp } from 'vue';
import { Editor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';

// Initialize Alpine.js
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// Initialize Vue app only if we're on a page with the app id
if (document.getElementById('app')) {
    const app = createApp({
        components: {
            EditorContent,
        },
        data() {
            return {
                editor: null,
            }
        },
        mounted() {
            // Initialize the editor when the component is mounted
            this.editor = new Editor({
                element: document.querySelector('.editor-content'),
                extensions: [
                    StarterKit,
                ],
                content: '',
                editorProps: {
                    attributes: {
                        class: 'prose dark:prose-invert max-w-none focus:outline-none p-4 min-h-[200px]',
                    },
                },
            });
        },
        beforeUnmount() {
            if (this.editor) {
                this.editor.destroy();
            }
        },
    });

    app.mount('#app');
}

// Global function to handle note editing
window.editNote = function(id, title, content) {
    document.getElementById('editNoteForm').action = '/notes/' + id;
    document.getElementById('edit_title').value = title;
    document.getElementById('edit_content').value = content;
    document.getElementById('editNoteModal').classList.remove('hidden');
};

// Close modals when clicking outside
document.addEventListener('click', function(event) {
    if (event.target.id === 'newNoteModal') {
        document.getElementById('newNoteModal').classList.add('hidden');
    }
    if (event.target.id === 'editNoteModal') {
        document.getElementById('editNoteModal').classList.add('hidden');
    }
});
