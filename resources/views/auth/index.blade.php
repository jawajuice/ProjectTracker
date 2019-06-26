@extends('layouts.app')

@section('content')
@php

    @endphp
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        	<h1>Auth page</h1>
        	@if(isset($reponse))
        	<p>{{ $response }}</p>
        	@endif
        </div>
    </div>
</div>
@endsection
