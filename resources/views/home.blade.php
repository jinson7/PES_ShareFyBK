@extends('layout.layout')

@section('content')
<div class="row text-center">
    <div class="col">
        <img src = "{{ asset('/images/logo.png') }}" alt="logo_home" style="max-width: 300px;" />
        <h1>Sharefy API</h1>
        <p><a href="/api/documentation">DOC</a></p>
    </div>
</div>
@endsection