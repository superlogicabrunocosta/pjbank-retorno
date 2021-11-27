@extends('layouts.app')

@section('content')
@foreach($tables as $table)

<form method='post' target="_blank" action="{{ route('retorno.update', $table['bank']) }}">
    @method('PUT')
    @csrf
    <input type="hidden" name="type" value="{{ $type }}">
    <input type="hidden" name="bank" value="{{ $table['bank'] }}">
    <div class='card form-group'>
        <div class='card-header'>
            <div class='float-left'>Banco - {!! $table['bank'] !!}</div>
        </div>
        {!! $table['table']->render() !!}
        <div class='card-footer'>
            <button class='btn btn-primary'>Enviar</button>
        </div>
    </div>
</form>

@endforeach
@endsection
