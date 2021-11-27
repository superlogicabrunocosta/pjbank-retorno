<?php

namespace App\Http\Controllers;

use App\Events\RecurrenceCreateEvent;
use App\Repositories\Contracts\ChargeRepository;
use App\Repositories\RecurrenceChargeRepositoryEloquent;
use App\Repositories\RecurrenceRepositoryEloquent;
use Costa\LaravelPackage\Util\Form;
use Costa\LaravelPackage\Util\Transaction;
use Costa\LaravelTable\TableSimple;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Kris\LaravelFormBuilder\FormBuilder;

class RecurrenceController extends Controller
{
    public function index(Form $form)
    {
        return view('recurrency.index', compact('form'));
    }

    public function charges(Request $request, ChargeRepository $chargeRepository, TableSimple $tableSimple, FormBuilder $formBuilder)
    {
        $min = $request->min;
        $max = $request->max;
        $type = $request->type;

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

        $resultBank = $chargeRepository->whereBetween('id', [$min, $max])
            ->select(['sync_bank_code'])
            ->groupBy('sync_bank_code')
            ->get();

        $tables = [];
        foreach ($resultBank as $rsBank) {
            $resultCharge = $chargeRepository->whereBetween('id', [$min, $max])
                ->where('sync_bank_code', $rsBank->sync_bank_code)
                ->get();

            $model = (Cache::get(
                'data_bank_' . $rsBank->sync_bank_code
            ) ?? []) + ['min' => $min, 'max' => $max, 'type' => $request->type];

            $tables[] = [
                'table' => $tableSimple->setData($resultCharge)
                    ->setColumns(false)
                    ->setAddColumns([
                        $titleValue => fn ($obj) => '<input class="form-control" type="hidden" value="' . $obj->id . '" name="charge[' . $obj->id . '][id]" /><input class="form-control" type="number" step=0.01 value="' . $obj->value . '" name="charge[' . $obj->id . '][value_pay]" />',
                        $titleDate => fn ($obj) => '<input class="form-control" type="date" value="' . $obj->date_due . '" name="charge[' . $obj->id . '][date_pay]" />',
                        'Quantidade' => fn ($obj) => '<input class="form-control" type="number" value="1" name="charge[' . $obj->id . '][qtd]" />',
                    ])
                    ->run(),
                'bank' => $rsBank->sync_bank_code,
            ];
        }

        return view('recurrency.charges', compact('tables', 'type'));
    }

    public function update(
        $bank,
        Request $request,
        ChargeRepository $chargeRepository,
        RecurrenceRepositoryEloquent $recurrenceRepositoryEloquent,
        RecurrenceChargeRepositoryEloquent $recurrenceChargeRepositoryEloquent
    ) {
        return Transaction::exec(function () use (
            $bank,
            $request,
            $chargeRepository,
            $recurrenceChargeRepositoryEloquent,
            $recurrenceRepositoryEloquent
        ) {
            $formData = $request->except('_token');
            $type = $formData['type'];

            unset($formData['min']);
            unset($formData['max']);
            unset($formData['type']);

            $data = $formData + [
                'charges' => $request->charge,
            ];

            $ids = array_keys($data['charges']);
            $resultAccounts = $chargeRepository
            ->select('account_id')
            ->whereIn('id', $ids)
            ->groupBy('account_id')
            ->get();

            foreach ($resultAccounts as $rsAccount) {

                $objRecurrency = $recurrenceRepositoryEloquent->create([
                    'account_id' => $rsAccount->account_id,
                    'type' => $type,
                    'config' => json_encode($formData),
                    'bank_code' => $bank,
                ]);

                $result = $chargeRepository
                    ->whereIn('id', $ids)
                    ->where('account_id', $rsAccount->account_id)
                    ->get();

                foreach ($result as $rs) {
                    $dataFormCharge = $data['charges'][$rs->id];
                    for ($i = 0; $i < $dataFormCharge['qtd']; $i++) {
                        $recurrenceChargeRepositoryEloquent->create([
                            'recurrence_id' => $objRecurrency->id,
                            'charge_id' => $rs->id,
                            'value_recurrence' => $dataFormCharge['value_pay'],
                            'value_charge' => $rs->value,
                            'date_recurrence' => $dataFormCharge['date_pay'],
                            'date_due' => $rs->date_due,
                        ]);
                    }
                }

                event(new RecurrenceCreateEvent($objRecurrency));
            }

            return redirect()->route('cobranca.index');
        });
    }
}
