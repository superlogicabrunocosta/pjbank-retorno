@extends('layouts.app')

@section('content')
<div class='card'>
    <div class='card-header'>
        <div class='float-left'>Relatório de contas</div>
        <div class='float-right'>
            <a class='btn btn-outline-primary' href="{{ route('conta.create') }}">Nova conta</a>
        </div>
    </div>
    {!! $table->render() !!}
</div>
@endsection