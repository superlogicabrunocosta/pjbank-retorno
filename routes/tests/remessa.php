<?php

use CnabParser\Model\Remessa;
use CnabParser\Output\RemessaFile;
use CnabParser\Parser\Layout;
use Illuminate\Support\Facades\Route;

Route::get('remessa', function () {
    $remessaLayout = new Layout(file_get_contents(base_path('app/Layouts/itau/cnab240/cobranca.yml')));
    $remessa = new Remessa($remessaLayout);

    // header arquivo
    $remessa->header->codigo_banco = 341;
    $remessa->header->tipo_inscricao = 2;
    $remessa->header->inscricao_numero = '12234567000186';
    $remessa->header->numero_inscricao_empresa = '12234567000186';
    $remessa->header->numero_convenio = '1552';
    $remessa->header->cobranca_cedente = '554';
    $remessa->header->numero_carteira_cobranca = '10';
    $remessa->header->variacao_carteira_cobranca = '1';
    $remessa->header->agencia_mantenedora_conta = '1';
    $remessa->header->numero_conta_corrente = '1001454';
    $remessa->header->codigo_remessa_retorno = '1';
    $remessa->header->agencia = 2932;
    $remessa->header->conta = 24992;
    $remessa->header->dac = 9;
    $remessa->header->nome_empresa = 'NOME DA EMPRESA';
    $remessa->header->data_geracao_arquivo = date('dmY');
    $remessa->header->data_geracao = date('dmY');
    $remessa->header->hora_geracao = date('His');
    $remessa->header->hora_geracao_arquivo = date('His');
    $remessa->header->numero_sequencial_arquivo_retorno = 1;
    $remessa->header->numero_sequencial_arquivo = 1;
    $remessa->header->numero_sequencial_arquivo = 1;
    $remessa->header->tipo_inscricao_empresa = 1;

    // criar um novo lote de serviço para a remessa
    // informando o código sequencial do lote
    $lote = $remessa->novoLote(1);

    $lote->header->codigo_banco = 341;
    $lote->header->lote_servico = $lote->sequencial;
    $lote->header->tipo_registro = 1;
    $lote->header->tipo_operacao = 'R';
    $lote->header->tipo_servico = '01';
    $lote->header->zeros_01 = 0;
    $lote->header->versao_layout_lote = '030';
    $lote->header->tipo_inscricao_empresa = '030';
    $lote->header->brancos_01 = '';
    $lote->header->tipo_inscricao = 2;
    $lote->header->inscricao_empresa = '12234567000186';
    $lote->header->numero_inscricao_empresa = '12234567000186';
    $lote->header->numero_convenio = '6516161';
    $lote->header->cobranca_cedente = '1651';
    $lote->header->numero_carteira_cobranca = '10';
    $lote->header->variacao_carteira_cobranca = '1';
    $lote->header->agencia_mantenedora_conta = '1';
    $lote->header->numero_conta_corrente = '11156';
    $lote->header->numero_remessa_retorno = '1';
    $lote->header->brancos_02 = '0';
    $lote->header->zeros_02 = 0;
    $lote->header->agencia = 2932;
    $lote->header->brancos_03 = '';
    $lote->header->zeros_03 = 0;
    $lote->header->conta = '24992';
    $lote->header->brancos_04 = '';
    $lote->header->dac = 9;
    $lote->header->nome_empresa = 'NOME DA EMPRESA';
    $lote->header->brancos_05 = '';
    $lote->header->numero_sequencial_arquivo_retorno = 1;
    $lote->header->data_gravacao = date('dmY');
    $lote->header->data_credito = date('dmY');
    $lote->header->brancos_06 = '';

    $detalhe = $lote->novoDetalhe();
    // segmento p
    $detalhe->segmento_p->lote_servico = $lote->sequencial;
    $detalhe->segmento_p->nummero_sequencial_registro_lote = 1;
    $detalhe->segmento_p->numero_sequencial_registro_lote = 1;
    $detalhe->segmento_p->numero_registro = '01';
    $detalhe->segmento_p->codigo_ocorrencia = '01';
    $detalhe->segmento_p->valor_nominal = 2932;
    $detalhe->segmento_p->agencia = 2932;
    $detalhe->segmento_p->conta = 24992;
    $detalhe->segmento_p->dac = 9;
    $detalhe->segmento_p->carteira = 109;
    $detalhe->segmento_p->nosso_numero = 12345678;
    $detalhe->segmento_p->dac_nosso_numero = 3;
    $detalhe->segmento_p->numero_documento = 1;
    $detalhe->segmento_p->vencimento = '10052016';
    $detalhe->segmento_p->agencia_conta = '10052016';
    $detalhe->segmento_p->numero_conta = '10052016';
    $detalhe->segmento_p->codigo_carteira = '1515';
    $detalhe->segmento_p->valor_titulo = 1000;
    $detalhe->segmento_p->agencia_cobradora = 0;
    $detalhe->segmento_p->dac_agencia_cobradora = 0;
    $detalhe->segmento_p->especie = '05';
    $detalhe->segmento_p->codigo_juros_mora = '0';
    $detalhe->segmento_p->aceite = 'N';
    $detalhe->segmento_p->data_emissao = date('dmY');
    $detalhe->segmento_p->data_vencimento = date('dmY');
    $detalhe->segmento_p->data_juros_mora = '11052016';
    $detalhe->segmento_p->juros_1_dia = 0;
    $detalhe->segmento_p->juros_mora_dia = 0;
    $detalhe->segmento_p->data_1o_desconto = '00000000';
    $detalhe->segmento_p->valor_1o_desconto = 0;
    $detalhe->segmento_p->valor_iof = 38;
    $detalhe->segmento_p->valor_abatimento = 0;
    $detalhe->segmento_p->identificacao_titulo_empresa = '';
    $detalhe->segmento_p->codigo_negativacao_protesto = 0;
    $detalhe->segmento_p->prazo_negativacao_protesto = 0;
    $detalhe->segmento_p->codigo_baixa = 0;
    $detalhe->segmento_p->lote_servico = 0;
    $detalhe->segmento_p->prazo_baixa = 0;
    $detalhe->segmento_p->lote_servico = '156165';
    // segmento q
    $detalhe->segmento_q->lote_servico = $lote->sequencial;
    $detalhe->segmento_q->nummero_sequencial_registro_lote = 2;
    $detalhe->segmento_q->numero_sequencial_registro_lote = 2;
    $detalhe->segmento_q->codigo_ocorrencia = '01';
    $detalhe->segmento_q->numero_registro = '01';
    $detalhe->segmento_q->codigo_movimento_remessa = '1';
    $detalhe->segmento_q->tipo_inscricao = 2;
    $detalhe->segmento_q->inscricao_numero = '12345678999';
    $detalhe->segmento_q->nome_pagador = 'NOME PAGADOR';
    $detalhe->segmento_q->logradouro = 'RUA PAGADOR';
    $detalhe->segmento_q->bairro = 'BAIRRO';
    $detalhe->segmento_q->cep = 31814;
    $detalhe->segmento_q->sufixo_cep = 500;
    $detalhe->segmento_q->cidade = 'CIDADE';
    $detalhe->segmento_q->uf = 'MG';
    $detalhe->segmento_q->tipo_inscricao_sacador = 2;
    $detalhe->segmento_q->inscricao_sacador = '12234567000186';
    $detalhe->segmento_q->nome_sacador = 'NOME DA EMPRESA';
    $detalhe->segmento_q->numero_inscricao = '156165';
    $detalhe->segmento_q->lote_servico = '156165';
    // segmento r opcional nao adicionado
    unset($detalhe->segmento_r);
    // segmento y opcional nao adicionado
    unset($detalhe->segmento_y);
    // insere o detalhe no lote da remessa
    $lote->inserirDetalhe($detalhe);

    // trailer lote
    $lote->trailer->lote_servico = $lote->sequencial;
    $lote->trailer->quantidade_registros_lote = 4; // quantidade de Registros do Lote correspondente à soma da quantidade dos registros tipo 1 (header_lote), 3(detalhes) e 5(trailer_lote)
    $lote->trailer->quantidade_cobranca_simples = 1;
    $lote->trailer->valor_total_cobranca_simples = 10000;
    $lote->trailer->quantidade_cobranca_vinculada = 0;
    $lote->trailer->valor_total_cobranca_vinculada = 0;
    $lote->trailer->aviso_bancario = '00000000';
    $lote->trailer->aviso_bancario = '00000000';
    $remessa->inserirLote($lote);

    // trailer arquivo
    $remessa->trailer->lote_servico = 1; // quantidade de Lotes do arquivo correspondente à soma da quantidade dos registros tipo 1 (header_lote).
    $remessa->trailer->total_lotes = 1; // quantidade de Lotes do arquivo correspondente à soma da quantidade dos registros tipo 1 (header_lote).
    $remessa->trailer->total_registros = 6; //total da quantidade de Registros no arquivo correspondente à soma da quantidade dos registros tipo 0(header_arquivo), 1(header_lote), 3(detalhes), 5(trailer_lote) e 9(trailer_arquivo).

    $remessaFile = new RemessaFile($remessa);
    return $remessaFile->text();
});