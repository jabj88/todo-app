<?php

use function Livewire\Volt\{state, with, on};

state(['task']);
with([
    'todos' => fn() => \App\Models\Todo::all(),
]);

$add = function () {
    \App\Models\Todo::create([
        'task' => $this->task,
        'status' => 'pending',
        'completed_at' => '',
    ]);
    $this->task = '';
};
//todo: ineficient method - fix
on([
    'item_removed' => function () {
        $todos = \App\Models\Todo::all();
    },
]);
?>

<div class="grid grid-rows-[50px_auto_60px] h-screen">

    <x-menu class="flex flex-row self-center gap-5 p-2 font-bold text-white list-none justify-self-center " />

    <div class="justify-center px-10 py-5 overflow-y-auto align-middle">
        @foreach ($todos as $todo)
            <livewire:task :todo="$todo" :key="$todo->id" />
        @endforeach
    </div>

    <form wire:submit='add' class="flex flex-row items-center gap-1 px-8 py-3 bg-indigo-900 shadow-lg justify-evenly">
        <input class="w-11/12 rounded-md" type=text wire:model='task' />
        <button class="bg-green-400 rounded-full w-7 h-7 justify-items-center" type='submit'> <x-akar-send
                class="w-4 h-4 text-white" /> </button>
    </form>

</div>
