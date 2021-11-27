@extends('layouts.app')

@section('content')
<div class='card'>
    <div class='card-header'>
        <div class='float-left'>Configuração da remessa</div>
    </div>
    <form method="get" class='card-body' action="{{ route('retorno.cobrancas') }}">
        <div class='row'>
            <div class='col-md-4 form-group'>
                <label class='control-label'>Número inicial da cobrança</label>
                <input type="number" name='min' value=1 min=0 class='form-control'>
            </div>
            <div class='col-md-4 form-group'>
                <label class='control-label'>Número final da cobrança</label>
                <input type="number" name='max' value=1 min=0 class='form-control'>
            </div>
            <div class='col-md-4 form-group'>
                <label class='control-label'>Tipo de remessa</label>
                <select name="type" class='form-control'>
                    <option value="confirmar">Confirmar</option>
                    <option value="baixar">Baixar</option>
                    <option value="pagar">Pagar</option>
                </select>
            </div>
        </div>

        <button class='btn btn-primary'>Enviar</button>
    </form>
</div>
@endsection