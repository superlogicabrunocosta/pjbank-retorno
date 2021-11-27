<?php

namespace App\Http\Controllers;

use App\Forms\AccountForm;
use App\Repositories\Contracts\AccountRepository;
use App\Services\AccountService;
use Costa\LaravelPackage\Util\Form;
use Costa\LaravelPackage\Util\Transaction;
use Costa\LaravelTable\TableSimple;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class AccountController extends Controller
{
    public function index(TableSimple $tableSimple, AccountService $accountService)
    {
        $table = $tableSimple->setData($accountService->getPaginate([]))
            ->setColumns(['name', 'cnpj', 'credential'])
            ->run();
        return view('account.index', compact('table'));
    }

    public function create(Form $form)
    {
        $data = [];

        if(class_exists(\Faker\Factory::class)){
            $faker = \Faker\Factory::create('pt_BR');
            $data['name'] = $faker->company;
            $data['cnpj'] = $faker->cnpj;
        }

        $form = $form->setForm(AccountForm::class)->create(route('conta.store'), $data);
        return view('account.create', compact('form'));
    }

    public function store(AccountRepository $accountRepository, Form $form)
    {
        return Transaction::exec(function() use($accountRepository, $form){
            $data = $form->setForm(AccountForm::class)->data();
            $url = config('pjbank')[$data['enviroment']]['credenciar'];
            $request = Http::post($url, [
                "nome_empresa" => $data['name'],
                "conta_repasse" => $data['bank_account'],
                "agencia_repasse" => $data['bank_agency'],
                "banco_repasse" => $data['bank_code'],
                "cnpj" => Str::number($data['cnpj']),
                "email" => time() . "@" . time() .'.com.br',
            ]);
            if ($request->json('status') != 201) {
                throw new Exception($request->json('msg'), $request->json('status'));
            }

            $accountRepository->create($data + [
                'credential' => $request->json('credencial'),
                'secret' => $request->json('chave'),
                'webhook' => $request->json('chave_webhook'),
            ]);
            return redirect()->route('conta.index');
        });
    }
}
