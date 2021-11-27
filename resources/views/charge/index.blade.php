@extends('layouts.app')

@section('content')
<div class='card'>
    <div class='card-header'>
        <div class='float-left'>Relatório de cobranças</div>
        <div class='float-right'>
            <a class='btn btn-outline-primary' href="{{ route('cobranca.create') }}">Nova cobrança</a>
        </div>
    </div>
    {!! $table->render() !!}
</div>
@endsection