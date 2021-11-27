<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class RecurrenceCharge.
 *
 * @package namespace App\Models;
 */
class RecurrenceCharge extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'recurrence_id',
        'charge_id',
        'value_recurrence',
        'value_charge',
        'date_recurrence',
        'date_due',
    ];

    public function charge()
    {
        return $this->belongsTo(Charge::class);
    }

}
