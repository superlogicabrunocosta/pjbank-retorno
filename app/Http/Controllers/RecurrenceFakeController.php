<?php

namespace App\Http\Controllers;

use App\Models\Charge;
use App\Services\BankService;
use Carbon\Carbon;
use Costa\LaravelPackage\Util\Form;
use Costa\LaravelTable\TableSimple;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;

class RecurrenceFakeController extends Controller
{
    public function index(Form $form, BankService $bankService)
    {
        $banks = $bankService->getBankActiveGenerateReturn();

        return view('recurrency.fake.index', compact('form', 'banks'));
    }

    public function charges(Request $request, TableSimple $tableSimple, FormBuilder $formBuilder)
    {
        $min = $request->min;
        $max = $request->max;
        $type = $request->type;
        $bank = $request->bank_code;

        if ($min > $max) {
            $c = $min;
            $max = $min;
            $min = $c;
        }

        $titleValue = "Valor da Cobrança";
        $titleDate = "Data da confirmação";
        switch ($request->type) {
            case 'baixar':
                $titleDate = "Data da baixa";
                break;
            case 'pagar':
                $titleValue = "Valor do Pagamento";
                $titleDate = "Data do pagamento";
                break;
        }

        $resultCharge = [];

        do {
            $rs = new Charge();
            $rs->id = $min;
            $rs->date_due = (new Carbon())->format('Y-m-d');
            $rs->value = 100;
            array_push($resultCharge, $rs);
            $min++;
        }while($min < $max);

        $tables[] = [
            'table' => $tableSimple->setData($resultCharge)
                ->setColumns(false)
                ->setAddColumns([
                    $titleValue => fn ($obj) => '<input class="form-control" type="hidden" value="' . $obj->id . '" name="charge[' . $obj->id . '][id]" /><input class="form-control" type="number" step=0.01 value="' . $obj->value . '" name="charge[' . $obj->id . '][value_pay]" />',
                    $titleDate => fn ($obj) => '<input class="form-control" type="date" value="' . $obj->date_due . '" name="charge[' . $obj->id . '][date_pay]" />',
                    'Quantidade' => fn ($obj) => '<input class="form-control" type="number" value="1" name="charge[' . $obj->id . '][qtd]" />',
                ])
                ->run(),
            'bank' => $bank,
        ];

        return view('recurrency.fake.charges', compact('tables', 'type'));
    }
}
