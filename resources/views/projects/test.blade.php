@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        	<H1>TEST SITE</H1>
            <p>{{$employees[0]['first_name']}} {{$employees[0]['last_name']}}</p>
            <h3>Timetracking overview for: {{$current_date}}</h3>
            <p><b>Active Projects</b>:            @foreach($projects as $project)
            {{$project->title}} </p>
            @endforeach
            <p><b>Milestones</b>:
        	@foreach($milestones as $milestone)
        	{{$milestone->name}} Due: {{$milestone->due_on}}</p>
        	@endforeach

            <p><b>Tasks</b>:<br>
            @foreach($tasks as $task)
            {{$task->description}} - Estimated time: {{$task->est_duration}} minutes.</p>
            @endforeach

            <p><b>Time tracking</b>:<br>
            @foreach($timetrackings as $timetracking)
            {{$timetracking->description}} - Duration: {{$timetracking->duration}} minutes.</p>
            @endforeach
        </div>
    </div>
</div>
@endsection
