<?php

namespace App\Bank\Recurrence;

use Carbon\Carbon;
use CnabParser\Model\Remessa;
use CnabParser\Output\RemessaFile;
use CnabParser\Parser\Layout;
use Illuminate\Support\Str;

abstract class AbstractBank
{
    protected $idRecurrence;

    public function __construct($idRecurrence)
    {
        $this->$idRecurrence = $idRecurrence;
    }

    

    protected string $code;

    protected string $date;

    protected string $dateType;

    protected float $valueCharge;

    protected float $valueType;

    protected array $config;

    protected array $details;

    protected string $document;

    protected string $name;

    protected string $type;

    protected BusinessBank $businessBank;

    /**
     * Get the value of date
     */
    public function getDate()
    {
        return (new Carbon($this->date))->format('dmY');
    }

    /**
     * Set the value of date
     *
     * @return  self
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get the value of config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Set the value of config
     *
     * @return  self
     */
    public function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Get the value of details
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set the value of details
     *
     * @return  self
     */
    public function setDetails(AbstractBank $details)
    {
        $this->details[] = $details;
        return $this;
    }

    /**
     * Get the value of businessBank
     */
    public function getBusinessBank()
    {
        return $this->businessBank;
    }

    /**
     * Set the value of businessBank
     *
     * @return  self
     */
    public function setBusinessBank($businessBank)
    {
        $this->businessBank = $businessBank;

        return $this;
    }

    /**
     * Get the value of document
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * Set the value of document
     *
     * @return  self
     */
    public function setDocument($document)
    {
        $this->document = Str::number($document);

        return $this;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of code
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set the value of code
     *
     * @return  self
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get the value of idShipiment
     */
    public function getIdRecurrence()
    {
        return str_pad($this->idRecurrence, 7, "0", STR_PAD_LEFT);
    }

    public function getNameFile()
    {
        return $this->getIdRecurrence() . '.rem';
    }

    /**
     * Get the value of type
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */ 
    public function setType($type)
    {
        switch ($type) {
            case 'confirmar':
                $type = '02';
                break;
            case 'baixar':
                $type = '09';
                break;
            case 'pagar':
                $type = '06';
                break;
        }
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of dateType
     */ 
    public function getDateType()
    {
        return (new Carbon($this->dateType))->format('dmY');
    }

    /**
     * Set the value of dateType
     *
     * @return  self
     */ 
    public function setDateType($dateType)
    {
        $this->dateType = $dateType;

        return $this;
    }

    /**
     * Get the value of valueCharge
     */ 
    public function getValueCharge()
    {
        return number_format($this->valueCharge, 2, '', '');
    }

    /**
     * Set the value of valueCharge
     *
     * @return  self
     */ 
    public function setValueCharge($valueCharge)
    {
        $this->valueCharge = $valueCharge;

        return $this;
    }

    /**
     * Get the value of valueType
     */ 
    public function getValueType()
    {
        return number_format($this->valueType, 2, '', '');
    }

    /**
     * Set the value of valueType
     *
     * @return  self
     */ 
    public function setValueType($valueType)
    {
        $this->valueType = $valueType;

        return $this;
    }

    public function generateFile()
    {
        $remessaLayout = new Layout(base_path('app/Layouts/' . $this->getConfig()['bank'] . '.yml'));

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

        foreach ($this->getDetails() as $detail) {
        
            /**
             * @var self $detail
             */
            $detalhe = $lote->novoDetalhe();
            foreach (array_keys($remessaLayout->getConfig()['remessa']['detalhes']) as $seguimento) {    
                $detalhe->$seguimento->lote_servico = $lote->sequencial;
                $detalhe->$seguimento->nosso_numero = $detail->getCode();
                $detalhe->$seguimento->nosso_numero_02 = $detail->getCode();
                $detalhe->$seguimento->nosso_numero_03 = $detail->getCode();
                $detalhe->$seguimento->tipo_retorno = $detail->getType();
                $detalhe->$seguimento->data_vencimento = $detail->getDate();
                $detalhe->$seguimento->data_ocorrencia = (new Carbon())->format('dmy');
                $detalhe->$seguimento->data_ocorrencia_02 = (new Carbon())->format('dmY');
                $detalhe->$seguimento->data_credito = $detail->getDateType();
                $detalhe->$seguimento->valor_cobranca = $detail->getValueCharge();
                $detalhe->$seguimento->valor_ocorrencia = $detail->getValueType();
            }
            $lote->inserirDetalhe($detalhe);
        }

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
    }
}
