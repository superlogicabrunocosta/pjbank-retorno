@extends('layouts.app')

@section('content')
<div class='card'>
    <div class='card-header'>
        <div class='float-left'>Cadastro de conta</div>
        <div class='float-right'>
            <a class='btn btn-outline-primary' href="{{ route('conta.create') }}">Nova conta</a>
        </div>
    </div>
    <div class='card-body'>{!! form($form) !!}</div>
</div>
@endsection