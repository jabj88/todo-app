<?php

use function Livewire\Volt\{state, with, mount, rules, computed};
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
rules(['from' => 'required|date|date_format:Y-m-d', 'to' => 'required|date|date_format:Y-m-d']);
state(['from', 'to'])->url(keep: true);
state(['todosCompleted', 'todosPending', 'totalTasks']);
mount(function () {
    try {
        $this->validate();
    } catch (Exception $e) {
        $this->from = Carbon::now()->subDays(7)->format('Y-m-d');
        $this->to = Carbon::now()->format('Y-m-d');
    }
    $this->getRangeData();
});

$completion_rate = computed(function () {
    if (!$this->totalTasks) {
        return 0;
    }
    $rate = (count($this->todosCompleted) * 100) / $this->totalTasks;
    return round($rate, 2);
});

$getRangeData = function () {
    //add one day to search param to cover whole day
    $plusDay = Carbon::createFromFormat('Y-m-d', $this->to)
        ->addDays(1)
        ->format('Y-m-d');
    $this->todosCompleted = DB::table('todos')
        ->select('task', 'id', 'status', 'completed_at')
        ->where('status', 'completed')
        ->whereBetween('created_at', [$this->from, $plusDay])
        ->get();

    $this->todosPending = DB::table('todos')
        ->select('task', 'id', 'status', 'completed_at')
        ->where('status', 'pending')
        ->whereBetween('created_at', [$this->from, $plusDay])
        ->get();
    $this->totalTasks = count($this->todosCompleted) + count($this->todosPending);
};

?>

<div class="flex flex-col text-center text-white">
    <x-menu class="flex flex-row self-center gap-5 p-2 font-bold text-white list-none justify-self-center " />
    <div class="align-middle h-5/6">
        <h1 class="m-5 text-5xl font-bold">
            Here are some stats
        </h1>
        <h2 class="flex flex-col gap-2 m-5 text-2xl text-center md:block ">
            <span>From</span> <input class="self-center flex-shrink text-center bg-transparent w-44 "
                style="color-scheme:dark" type="date" wire:model='from' wire:change='getRangeData' />
            <span>To</span> <input class="self-center flex-shrink text-center bg-transparent w-44"
                style="color-scheme:dark" type="date" wire:model='to' wire:change='getRangeData' />
        </h2>
        @if ($totalTasks == 0)
            <h2 class="m-5 text-2xl">
                Ooops.... You have no Data Available!
            </h2>
            <h3 class="text-xl"> Try a different range of days <x-akar-face-happy class="inline-block w-4 h-4" /> </h3>
        @else
            <div>You created a total of <span class="text-xl font-bold">{{ $totalTasks }}</span> tasks </div>
            <div>completed <span class="text-xl font-bold">{{ count($todosCompleted) }}</span>
                out of <span class="text-xl font-bold">{{ $totalTasks }}</span> tasks you wanted to accomplish.
            </div>
            <div>Your Completion Rate is: <span class="text-xl font-bold">{{ $this->completion_rate }}<span>%</div>
        @endif

    </div>

    @if (count($todosPending) > 0)
        <div>
            <hr class="w-11/12 mx-auto my-10 border-indigo-600" />
            <h3 class="text-xl "> Try Completing <span class="text-xl font-bold">{{ count($todosPending) }}</span> more
                to get
                a <span class="text-xl font-bold">100%</span> success rate </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 auto-rows-max ">
                @foreach ($todosPending as $todo)
                    <livewire:task :todo="$todo" :key="$todo->id" />
                @endforeach
            </div>
        </div>
    @endif


</div>
