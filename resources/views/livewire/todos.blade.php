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

<div>
    Todo List:
    <div>
        <form wire:submit='add'>
            <input type=text wire:model='task' />
            <button type='submit'> Save </button>
        </form>
        <div>
            @foreach ($todos as $todo)
                <livewire:task :todo="$todo" :key="$todo->key" />
            @endforeach
        </div>
    </div>

</div>
