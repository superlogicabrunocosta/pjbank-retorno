<?php

namespace App\Events\Listeners;

use App\Bank\Recurrence\AbstractBank;
use App\Bank\Recurrence\BusinessBank;
use App\Events\RecurrenceCreateEvent;
use App\Jobs\SendRecurrenceJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;

class RecurrenceCreateListener implements ShouldQueue
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
     * @param  \App\Events\RecurrenceCreateEvent  $event
     * @return void
     */
    public function handle(RecurrenceCreateEvent $event)
    {
        $objRecurrency = $event->getRecurrence();

        $rsAccount = $objRecurrency->account;

        $objBusiness = new BusinessBank();
        $objBusiness->setName($rsAccount->name);
        $objBusiness->setCnpj($rsAccount->cnpj);
        $bank = $objRecurrency->bank_code;

        /** @var AbstractBank */
        $objRemessa = app('App\\Bank\\Recurrence\\Bank' . $bank, ['idRecurrence' => $objRecurrency->id]);
        $objRemessa->setConfig(json_decode($objRecurrency->config, true));
        $objRemessa->setBusinessBank($objBusiness);

        foreach ($objRecurrency->charges as $rs) {
            /** @var AbstractBank */
            $objNewRemessa = app('App\\Bank\\Recurrence\\Bank' . $bank, ['idRecurrence' => $objRecurrency->id]);

            $objNewRemessa->setValueCharge($rs->value_charge);
            $objNewRemessa->setValueType($rs->value_recurrence);
            $objNewRemessa->setCode($rs->charge->sync_id);
            $objNewRemessa->setDate($rs->date_due);
            $objNewRemessa->setDateType($rs->date_recurrence);
            $objNewRemessa->setDocument($rs->charge->customer_document ?: $rsAccount->cnpj);
            $objNewRemessa->setName($rs->charge->customer_name ?: $rsAccount->name);
            $objNewRemessa->setType($objRecurrency->type);
            $objRemessa->setDetails($objNewRemessa);
        }

        Storage::put($name = 'fake-retorno-' . $objRecurrency->id . '.ret', $objRemessa->generateFile());
        $objRecurrency->filename = $name;
        $objRecurrency->save();
        SendRecurrenceJob::dispatch($objRecurrency);
    }
}
