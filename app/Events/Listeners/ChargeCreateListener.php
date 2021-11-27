<?php

namespace App\Events\Listeners;

use App\Events\ChargeCreateEvent;
use App\Events\ChargeUpdateEvent;
use App\Models\Charge;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChargeCreateListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ChargeCreateEvent|\App\Events\ChargeUpdateEvent  $event
     * @return void
     */
    public function handle($event)
    {
        $charge = $event->getCharge();
        $account = $charge->account;
        $url = config('pjbank')[$account->enviroment]['pagar'];
        $urlReplace = str_replace(['{credencial}'], [$account->credential], $url);

        $response = Http::post($urlReplace, [
            "nome_cliente" => $account->name,
            "vencimento" => (new Carbon($charge->date_due))->format('m/d/Y'),
            "info_completa" => 1,
            "valor" => $charge->value,
            "pedido_numero" => $charge->uuid,
        ]);

        $charge->sync_status = $response->json('status');
        if (in_array($response->json('status'), [200, 201])) {
            $charge->sync_our_number = $response->json('nossonumero');
            $charge->sync_bank_code = $response->json('banco_numero');
            $charge->sync_id = $response->json('id_unico');
            $charge->sync_data = $response->json();
            $charge->sync_message = null;
        } else {
            $charge->sync_message = $response->json('msg');
        }
        $charge->save();
    }
}
