<?php

namespace App\Forms;

use App\Services\BankService;
use Kris\LaravelFormBuilder\Form;

class AccountForm extends Form
{
    public function buildForm()
    {

        $this->add('enviroment', 'select', [
            'empty_value' => 'Selecione...',
            'choices' => [
                'local' => 'Local',
                // 'stage' => 'Estágio',
                // 'sandbox' => 'Sandbox',
                // 'production' => 'Produção',
            ],
            'label' => "Ambiente",
            'rules' => ['required']
        ]);

        $this->add('name', 'text', [
            'label' => "Nome da empresa",
            'rules' => ['required', 'min:3', 'max:100']
        ]);

        $this->add('cnpj', 'text', [
            'label' => "CNPJ",
            'rules' => ['required', 'cnpj']
        ]);

        $this->add('bank_code', 'select', [
            'empty_value' => 'Selecione...',
            'choices' => app(BankService::class)->serializeArrayBanks(),
            'label' => "Banco",
            'rules' => ['required']
        ]);

        $this->add('bank_agency', 'text', [
            'label' => "Agência com dígito",
            'rules' => ['required']
        ]);

        $this->add('bank_account', 'text', [
            'label' => "Conta com dídigo",
            'rules' => ['required']
        ]);
    }
}
