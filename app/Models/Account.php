<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Account.
 *
 * @package namespace App\Models;
 */
class Account extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'enviroment',
        'name',
        'cnpj',
        'bank_code',
        'bank_agency',
        'bank_account',
        'credential',
        'secret',
        'webhook',
    ];

}
