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
});

$completion_rate = computed(function () {
    if (!$this->totalTasks) {
        return 0;
    }
    $rate = (count($this->todosCompleted) * 100) / $this->totalTasks;
    return round($rate, 2);
});

?>

<div class="flex flex-col text-center text-white">
    <x-menu class="flex flex-row self-center gap-5 p-2 font-bold text-white list-none justify-self-center " />
    <div class="align-middle h-5/6">
        <h1 class="m-5 text-5xl font-bold">
            Here are some stats
        </h1>
        <h2 class="m-5 text-2xl">
            From {{ $from }} To {{ $to }}
        </h2>
        @if ($totalTasks == 0)
            <h2 class="m-5 text-2xl">
                Ooops.... You have no Data Available!
            </h2>
            <h3 class="text-xl"> Try a different range of days <x-akar-face-happy class="inline-block w-4 h-4" /> </h3>
        @else
            <div>You created a total of <span class="text-xl font-bold">{{ $totalTasks }}</span> tasks </div>
            <div>completed <span class="text-xl font-bold">{{ count($todosCompleted) }}</span>
                And <span class="text-xl font-bold">{{ count($todosPending) }} </span> are still pending for completion
            </div>
            <div>Your Completion Rate is: <span class="text-xl font-bold">{{ $this->completion_rate }}<span>%</div>
        @endif

    </div>

    @if (count($todosPending) > 0)
        <div>
            <hr class="w-11/12 mx-auto my-10 border-indigo-600" />
            <h3 class="text-xl "> Try Closing this tasks to get to 100% rate </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 ">
                @foreach ($todosPending as $todo)
                    <livewire:task :todo="$todo" :key="$todo->id" />
                @endforeach
            </div>
        </div>
    @endif


</div>
