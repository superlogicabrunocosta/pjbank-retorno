<?php

namespace App\Http\Controllers;

use App\Events\ChargeCreateEvent;
use App\Events\ChargeUpdateEvent;
use App\Forms\ChargeForm;
use App\Repositories\Contracts\ChargeRepository;
use App\Services\ChargeService;
use Costa\LaravelPackage\Util\Form;
use Costa\LaravelPackage\Util\Transaction;
use Costa\LaravelTable\TableSimple;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChargeController extends Controller
{
    public function index(TableSimple $tableSimple, ChargeService $accountService)
    {
        $table = $tableSimple
            ->setData($accountService->getPaginate([]))
            ->setColumns(['id', 'uuid'])
            ->setAddColumns([
                'Valor' => fn($obj) => Str::numberEnToBr($obj->value),
                'Mensagem de sincronização' => fn($obj) => $obj->sync_message ?: '-',
                'edit' => [
                    'action' => function ($model) {
                        return btnLinkEditIcon(route('cobranca.edit', $model->uuid));
                    },
                    'class' => 'min',
                ],
                'delete' => [
                    'action' => function ($model) {
                        return btnLinkDelIcon(route('cobranca.destroy', $model->uuid));
                    },
                    'class' => 'min',
                ]
            ])
            ->run();
        return view('charge.index', compact('table'));
    }

    public function create(Form $form)
    {
        $data = [
            'quantity' => 1,
        ];

        $form = $form->setForm(ChargeForm::class)->create(route('cobranca.store'), $data);
        return view('charge.create', compact('form'));
    }

    public function store(ChargeRepository $chargeRepository, Form $form)
    {
        return Transaction::exec(function () use ($chargeRepository, $form) {
            $data = $form->setForm(ChargeForm::class)->data();
            $i = 0;
            do {
                $obj = $chargeRepository->create($data);
                event(new ChargeCreateEvent($obj));
                $i++;
            } while($i < $data['quantity']);

            return redirect()->route('cobranca.index');
        });
    }

    public function edit(Form $form, ChargeRepository $chargeRepository, $id)
    {
        $obj = $chargeRepository->where('uuid', $id)->first();
        $form = $form->setForm(ChargeForm::class)->edit(route('cobranca.update', $obj->uuid), $obj);
        return view('charge.edit', compact('form'));
    }

    public function update(ChargeRepository $chargeRepository, Form $form, $id)
    {
        return Transaction::exec(function () use ($chargeRepository, $form, $id) {
            $obj = $chargeRepository->where('uuid', $id)->first();
            $data = $form->setForm(ChargeForm::class)->data();
            $obj->fill($data);
            $obj->save();
            event(new ChargeUpdateEvent($obj));
            return redirect()->route('cobranca.index');
        });
    }
}
