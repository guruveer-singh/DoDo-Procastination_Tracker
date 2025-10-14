<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up(): void
{
Schema::create('tasks', function (Blueprint $table) {
$table->id();
$table->string('title');
$table->text('notes')->nullable();
$table->unsignedInteger('current_streak')->default(0);
$table->unsignedInteger('best_streak')->default(0);
$table->date('last_completed_date')->nullable();
$table->boolean('is_archived')->default(false);
$table->timestamps();
});


Schema::create('task_logs', function (Blueprint $table) {
$table->id();
$table->foreignId('task_id')->constrained()->cascadeOnDelete();
$table->date('done_on');
$table->timestamps();
$table->unique(['task_id', 'done_on']);
});
}


public function down(): void
{
Schema::dropIfExists('task_logs');
Schema::dropIfExists('tasks');
}
};