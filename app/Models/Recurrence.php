<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Recurrence.
 *
 * @package namespace App\Models;
 */
class Recurrence extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id',
        'type',
        'config',
        'bank_code',
        'filename',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function charges()
    {
        return $this->hasMany(RecurrenceCharge::class);
    }

}
