<?php

use function Livewire\Volt\{state, with, mount, rules, computed};
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
rules(['from' => 'required|date|date_format:Y-m-d', 'to' => 'required|date|date_format:Y-m-d']);
state(['from', 'to'])->url();
state(['todosCompleted', 'todosPending', 'totalTasks']);
mount(function () {
    try {
        $this->validate();
    } catch (Exception $e) {
        $this->from = Carbon::now()->subDays(7)->format('Y-m-d');
        $this->to = Carbon::now()->format('Y-m-d');
    }
    $this->todosCompleted = DB::table('todos')
        ->select('task', 'id', 'status', 'completed_at')
        ->where('status', 'completed')
        ->whereBetween('created_at', [$this->from, $this->to])
        ->get();

    $this->todosPending = DB::table('todos')
        ->select('task', 'id', 'status', 'completed_at')
        ->where('status', 'pending')
        ->whereBetween('created_at', [$this->from, $this->to])
        ->get();

    $this->totalTasks = count($this->todosCompleted) + count($this->todosPending);
});

$completion_rate = computed(function () {
    return (count($this->todosCompleted) * 100) / $this->totalTasks;
});

?>

<div>
    <x-menu class="flex flex-row self-center gap-5 p-2 font-bold text-white list-none justify-self-center " />
    {{ $from }} -{{ $to }}
    <div>Total Tasks Created: {{ $totalTasks }}</div>
    <div>Completed: {{ count($todosCompleted) }}</div>
    <div>Pending: {{ count($todosPending) }}</div>
    <div>Completion Rate: {{ $this->completion_rate }}%</div>
    @foreach ($todosCompleted as $todo)
        <div>
            {{ $todo->task }} - {{ $todo->status }}
        </div>
    @endforeach

    @foreach ($todosPending as $todo)
        <div>
            {{ $todo->task }} - {{ $todo->status }}
        </div>
    @endforeach
</div>
