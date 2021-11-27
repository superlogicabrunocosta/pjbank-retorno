<?php

namespace App\Bank\Recurrence;

use Carbon\Carbon;

class Bank301 extends AbstractBank
{
    /**
     * Get the value of dateType
     */
    public function getDateType()
    {
        return (new Carbon($this->dateType))->format('dmY');
    }

    /**
     * Get the value of date
     */
    public function getDate()
    {
        return (new Carbon($this->date))->format('dmY');
    }

    public function getNameFile()
    {
        return sprintf(
            "CNAB400-%s-1-%s-%s-%s",
            $this->getConfig()['transferor_code'],
            $this->getConfig()['checking_account'],
            date('Ymd-His'),
            $this->getIdRecurrence(),
        ) . '.rem';
    }
}
