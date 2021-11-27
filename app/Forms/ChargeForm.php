<?php

namespace App\Forms;

use App\Services\AccountService;
use Kris\LaravelFormBuilder\Form;

class ChargeForm extends Form
{
    public function buildForm()
    {
        $this->add('account_id', 'select', [
            'empty_value' => 'Selecione...',
            'choices' => app(AccountService::class)->pluck(),
            'label' => "Conta",
            'rules' => ['required']
        ]);

        if (empty($this->request->route('cobranca'))) {
            $this->add('quantity', 'number', [
                'label' => "Quantidade de geração",
                'rules' => ['required', 'numeric', 'min:0', 'max:999999']
            ]);
        }

        $this->add('value', 'number', [
            'label' => "Valor",
            'attr' => ['step' => '0.01'],
            'rules' => ['required', 'numeric', 'min:0', 'max:999999']
        ]);

        $this->add('date_due', 'date', [
            'label' => "Data de vencimento",
            'rules' => ['required']
        ]);
    }
}
