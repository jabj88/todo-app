<?php

use function Livewire\Volt\{state, with};
state(['todo', 'isReadOnly' => true, 'task' => fn($todo) => $todo->task]);
$remove = function () {
    \App\Models\Todo::where('id', $this->todo->id)->delete();
};

$updateStatus = function () {
    $this->todo->status = $this->todo->status === 'pending' ? 'completed' : 'pending';
    \App\Models\Todo::where('id', $this->todo->id)->update(['status' => $this->todo->status]);
};

$updateTask = function () {
    \App\Models\Todo::where('id', $this->todo->id)->update(['task' => $this->task]);
    $this->toggleReadyOnly();
};

$toggleReadyOnly = function () {
    $this->isReadOnly = !$this->isReadOnly;
};

?>

<div>
    <div>
        <input type="checkbox" {{ $todo->status == 'completed' ? 'checked' : '' }} wire:click='updateStatus' />
        <input type="text" wire:model='task' wire:click="toggleReadyOnly" wire:change='updateTask'
            {{ $isReadOnly ? 'readonly' : '' }} /> - {{ $todo->status }}
        {{ $task }}
    </div>
    <button wire:click='remove'> delete </button>
</div>
