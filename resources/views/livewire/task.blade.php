<?php

use function Livewire\Volt\{state, with};
state(['todo', 'isReadOnly' => true, 'task' => fn($todo) => $todo->task]);
$remove = function () {
    \App\Models\Todo::where('id', $this->todo->id)->delete();
    $this->dispatch('item_removed');
};

$updateStatus = function () {
    $this->todo->status = $this->todo->status === 'pending' ? 'completed' : 'pending';
    \App\Models\Todo::where('id', $this->todo->id)->update([
        'status' => $this->todo->status,
        'completed_at' => $this->todo->status === 'completed' ? now() : '',
    ]);
};

$updateTask = function () {
    \App\Models\Todo::where('id', $this->todo->id)->update(['task' => $this->task]);
    $this->toggleReadyOnly();
};

$toggleReadyOnly = function () {
    $this->isReadOnly = !$this->isReadOnly;
};

?>

<div
    class="flex flex-row items-center justify-between w-11/12 h-auto gap-2 p-6 my-5 bg-indigo-800 rounded-md shadow-lg justify-self-center ">
    <input class="w-4 rounded-full checked:bg-green-500 hover:bg-green-300" type="checkbox"
        {{ $todo->status == 'completed' ? 'checked' : '' }} wire:click='updateStatus' />
    @if ($isReadOnly)
        <div class="w-full text-left text-white" wire:click="toggleReadyOnly">
            {{ $task }}
        </div>
        <button class="w-5 justify-items-end" wire:click='remove'>
            <x-uiw-delete class="w-5 h-5 text-white hover:text-red-600" />
        </button>
    @else
        <textarea class="w-10/12 text-black border-0 outline-none h-5/6 focus:border-2px" type="text" wire:model='task'
            wire:keydown.enter.prevent='updateTask' autofocus>  
        </textarea>
        <button class="w-5 justify-items-end" wire:click='toggleReadyOnly'>
            <x-uiw-circle-close class="w-5 h-5 text-white hover:text-red-600" />
        </button>
    @endif



</div>
