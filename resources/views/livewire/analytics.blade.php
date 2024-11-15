<?php

use function Livewire\Volt\{state, with};

state(['startDate', 'endDate']);

?>

<div>
    Analytics from {{ $startDate }} to {{ $endDate }}

</div>
