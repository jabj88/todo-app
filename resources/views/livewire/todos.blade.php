<?php

use function Livewire\Volt\{state, with, on};

state(['task']);
with([
    'todos' => fn() => \App\Models\Todo::all()->reverse(),
]);

$add = function () {
    if (!$this->task) {
        return;
    }
    \App\Models\Todo::create([
        'task' => $this->task,
        'status' => 'pending',
        'completed_at' => '',
    ]);
    $this->task = '';
};
on(['item_removed']);
?>

<div class="grid grid-rows-[50px_auto_60px] h-screen">

    <x-menu class="flex flex-row self-center gap-5 p-2 font-bold text-white list-none justify-self-center " />

    <div
        class="grid justify-center grid-cols-1 px-10 py-5 overflow-y-auto align-middle auto-rows-max md:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 ">
        @foreach ($todos as $todo)
            <livewire:task :todo="$todo" :key="$todo->id" />
        @endforeach
    </div>

    <form wire:submit='add' class="flex flex-row items-center gap-1 px-8 py-3 bg-indigo-900 shadow-lg justify-evenly">
        <input class="w-11/12 rounded-md" type=text wire:model='task'
            placeholder="Defeat procrastination. Add a task. &#9786;" />
        <button class="bg-green-400 rounded-full w-7 h-7 justify-items-center" type='submit'> <x-akar-send
                class="w-4 h-4 text-white" /> </button>
    </form>

</div>
