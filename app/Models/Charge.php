<?php

namespace App\Models;

use Costa\LaravelPackage\Traits\Models\UuidGenerate;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Charge.
 *
 * @package namespace App\Models;
 */
class Charge extends Model implements Transformable
{
    use TransformableTrait, UuidGenerate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id',
        'value',
        'date_due',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
