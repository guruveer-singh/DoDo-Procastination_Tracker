// Handle mark as done functionality
document.addEventListener('DOMContentLoaded', function() {
    // Mark task as done
    document.querySelectorAll('.mark-done-btn').forEach(button => {
        button.addEventListener('click', async function() {
            const taskId = this.getAttribute('data-task-id');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            
            try {
                const response = await fetch(`/tasks/${taskId}/done`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const result = await response.json();
                
                if (result.ok) {
                    window.location.reload();
                } else {
                    alert(result.message || 'Failed to mark task as done');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while updating the task');
            }
        });
    });
});