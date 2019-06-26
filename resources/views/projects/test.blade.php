@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        	<H1>TEST SITE</H1>
        	@foreach ($milestones as $milestone)
        	<p>{{ $milestone->project}}</p>
        	
        	@endforeach
        	@foreach($tasks as $task)
        	<p>{{$task->milestone}}</p>
        	@endforeach
        </div>
    </div>
</div>
@endsection
