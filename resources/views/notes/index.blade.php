@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">My Notes</h1>
        <button onclick="showNewNoteModal()" 
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
            <i class="material-icons-round mr-1">add</i>
            New Note
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @php
        $groupedNotes = $notes->groupBy(function($note) {
            return $note->created_at->format('Y-m-d');
        });
    @endphp

    @forelse($groupedNotes as $date => $dailyNotes)
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                {{ \Carbon\Carbon::parse($date)->format('F j, Y') }}
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($dailyNotes as $note)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-200 dark:border-gray-700">
                        <div class="p-4">
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">{{ $note->title }}</h3>
                                <div class="flex space-x-2">
                                    <button data-note-id="{{ $note->id }}" 
                                            data-note-title="{{ htmlspecialchars($note->title, ENT_QUOTES) }}" 
                                            data-note-content="{{ htmlspecialchars($note->content, ENT_QUOTES) }}"
                                            onclick="handleEditClick(event)"
                                            class="edit-note-btn text-gray-500 hover:text-blue-500 dark:text-gray-400 dark:hover:text-blue-400">
                                        <i class="material-icons-round text-sm">edit</i>
                                    </button>
                                    <form action="{{ route('notes.destroy', $note) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-500 hover:text-red-500 dark:text-gray-400 dark:hover:text-red-400" 
                                                onclick="return confirm('Are you sure you want to delete this note?')">
                                            <i class="material-icons-round text-sm">delete</i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="prose dark:prose-invert max-w-none text-gray-600 dark:text-gray-300">
                                {!! $note->content !!}
                            </div>
                            <div class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                                {{ $note->created_at->format('g:i A') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="text-center py-10">
            <i class="material-icons-round text-5xl text-gray-400 mb-4">note_add</i>
            <p class="text-gray-600 dark:text-gray-400">No notes yet. Create your first note!</p>
        </div>
    @endforelse
</div>

<!-- New Note Modal -->
<div id="newNoteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200">New Note</h3>
                <button onclick="hideModal('newNoteModal')" class="text-gray-400 hover:text-gray-500">
                    <i class="material-icons-round">close</i>
                </button>
            </div>
            <form action="{{ route('notes.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <input type="text" name="title" id="noteTitle" placeholder="Note title" 
                           class="w-full px-4 py-2 text-lg font-semibold bg-transparent border-0 border-b border-gray-200 dark:border-gray-700 focus:border-blue-500 focus:ring-0" required>
                </div>
                <div class="mb-4">
                    <textarea name="content" id="noteContent" placeholder="Write your note here..." 
                             class="w-full px-4 py-2 bg-transparent border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-0 min-h-[200px]" 
                             required></textarea>
                </div>
                <div class="flex justify-end space-x-2 pt-2">
                    <button type="button" onclick="hideModal('newNoteModal')" 
                            class="px-4 py-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        Save Note
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Note Modal -->
<div id="editNoteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Edit Note</h3>
                <button onclick="hideModal('editNoteModal')" class="text-gray-400 hover:text-gray-500">
                    <i class="material-icons-round">close</i>
                </button>
            </div>
            <form id="editNoteForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <input type="text" name="title" id="editNoteTitle" placeholder="Note title" 
                           class="w-full px-4 py-2 text-lg font-semibold bg-transparent border-0 border-b border-gray-200 dark:border-gray-700 focus:border-blue-500 focus:ring-0" required>
                </div>
                <div class="mb-4">
                    <textarea name="content" id="editNoteContent" placeholder="Write your note here..." 
                             class="w-full px-4 py-2 bg-transparent border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-0 min-h-[200px]" 
                             required></textarea>
                </div>
                <div class="flex justify-between pt-2">
                    <button type="button" onclick="if(confirm('Are you sure you want to delete this note? This cannot be undone.')) {
                        document.getElementById('deleteNoteForm').submit();
                    }" 
                            class="px-4 py-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg">
                        <i class="material-icons-round text-sm mr-1">delete</i> Delete Note
                    </button>
                    <div class="space-x-2">
                        <button type="button" onclick="hideModal('editNoteModal')" 
                                class="px-4 py-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
            <form id="deleteNoteForm" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showNewNoteModal() {
    document.getElementById('noteTitle').value = '';
    document.getElementById('noteContent').value = '';
    document.getElementById('newNoteModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function handleEditClick(event) {
    event.preventDefault();
    const button = event.currentTarget;
    const id = button.getAttribute('data-note-id');
    const title = button.getAttribute('data-note-title');
    const content = button.getAttribute('data-note-content');
    
    // Decode HTML entities
    const titleElement = document.createElement('textarea');
    titleElement.innerHTML = title;
    const decodedTitle = titleElement.value;
    
    const contentElement = document.createElement('textarea');
    contentElement.innerHTML = content;
    const decodedContent = contentElement.value;
    
    // Set up the form
    const form = document.getElementById('editNoteForm');
    const deleteForm = document.getElementById('deleteNoteForm');
    
    form.action = `/notes/${id}`;
    deleteForm.action = `/notes/${id}`;
    
    // Set the values
    document.getElementById('editNoteTitle').value = decodedTitle;
    document.getElementById('editNoteContent').value = decodedContent;
    
    // Show the modal
    document.getElementById('editNoteModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function hideModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Close modals when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('fixed')) {
        event.target.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
};

// Close modals with escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        document.querySelectorAll('.fixed').forEach(modal => {
            if (!modal.classList.contains('hidden')) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        });
    }
});
</script>
@endpush

@endsection
