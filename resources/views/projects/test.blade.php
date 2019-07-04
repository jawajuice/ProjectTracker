@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
           
        	<H1>TEST SITE</H1>
            <p>{{$employees[0]['first_name']}} {{$employees[0]['last_name']}}</p>
            <h3>Timetracking overview for: {{$current_date}}</h3>
                      
            @foreach($projects as $project)
             <div class="card">
            <p><h4>Project:</h4></p>  
            <p><h2>{{$project->title}}</h2> </p>
         

            	@foreach($milestones as $milestone)
                @if($milestone->project_id == $project->project_id)
                 <p><b>Milestones</b>:</p>
            	<p>{{$milestone->name}} Due: {{$milestone->due_on}} - {{ $milestone->milestone_id}}</p>


                    <p><b>Tasks</b>:<br></p>
                    @foreach($tasks as $task)
                        @if($task->milestone == $milestone->milestone_id)
                            <p>{{$task->description}} - Estimated time: {{$task->task_id}} minutes.</p>
                            @foreach($timetrackings as $timetracking)
                                @if($timetracking->milestone == $task->task_id)
                                    <h4>TIMETRACKING</h4>
                                    <p><b>{{$timetracking->description}} - Duration: {{$timetracking->duration}} minutes.</b></p>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    <p><b>Time tracking</b>:<br></p>

                    @foreach($timetrackings as $timetracking)
                        @if($timetracking->milestone == $milestone->milestone_id)
                            <p>{{$timetracking->description}} - Duration: {{$timetracking->duration}} minutes.</p>
                        @endif

                    @endforeach
                @endif
                @endforeach
                </div>
            @endforeach
        
            @foreach($tasks as $task)
            {{$task->description}}
            @endforeach
        
        </div>
    </div>
</div>
@endsection
