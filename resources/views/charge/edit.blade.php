@extends('layouts.app')

@section('content')
<div class='card'>
    <div class='card-header'>
        <div class='float-left'>Editar cobrança</div>
    </div>
    <div class='card-body'>{!! form($form) !!}</div>
</div>
@endsection