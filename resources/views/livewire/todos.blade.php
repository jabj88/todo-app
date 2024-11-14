<?php

use function Livewire\Volt\{state, with};

state(['task']);
with([
    'todos' => fn() => \App\Models\Todo::all(),
]);

$add = function () {
    \App\Models\Todo::create([
        'task' => $this->task,
        'status' => 'pending',
    ]);
    $this->task = '';
};

$remove = function ($id) {
    \App\Models\Todo::where('id', $id)->delete();
};

$updateStatus = function ($todo) {
    \App\Models\Todo::where('id', $todo['id'])->update(['status' => $todo['status'] === 'pending' ? 'completed' : 'pending']);
};

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
                <div>
                    <div>
                        <input type="checkbox" {{ $todo->status == 'completed' ? 'checked' : '' }}
                            wire:click='updateStatus({{ $todo }})' />
                        {{ $todo->task }} - {{ $todo->status }}
                    </div>
                    <button wire:click='remove({{ $todo->id }})'> delete </button>
                </div>
            @endforeach
        </div>
    </div>

</div>
