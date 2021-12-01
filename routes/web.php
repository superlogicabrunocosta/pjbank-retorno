<?php

use App\Events\ChargeCreateEvent;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ChargeController;
use App\Http\Controllers\RecurrenceController;
use App\Http\Controllers\RecurrenceFakeController;
use App\Jobs\SendRecurrenceJob;
use App\Models\Charge;
use App\Models\Recurrence;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', '/conta');
Route::resource('conta', AccountController::class)->only(['index', 'create', 'store']);
Route::resource('cobranca', ChargeController::class);
Route::get('retorno/charges', [RecurrenceController::class, 'charges'])->name('retorno.cobrancas');
Route::get('retorno-fake/charges', [RecurrenceFakeController::class, 'charges'])->name('retorno-fake.cobrancas');
Route::resource('retorno', RecurrenceController::class);
Route::resource('retorno-fake', RecurrenceFakeController::class);
Route::group(['prefix' => 'test'], function(){
    Route::get('cobranca/{id}', function ($id) {
        $obj = Charge::find($id);
        event(new ChargeCreateEvent($obj));
    });

    Route::get('retorno/{id}', function ($id) {
        $obj = Recurrence::find($id);
        SendRecurrenceJob::dispatch($obj);
    });

    include __DIR__ . "/tests/remessa.php";
});