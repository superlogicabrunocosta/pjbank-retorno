<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\RecurrenceChargeRepository;
use App\Models\RecurrenceCharge;
use App\Validators\RecurrenceChargeValidator;

/**
 * Class RecurrenceChargeRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class RecurrenceChargeRepositoryEloquent extends BaseRepository implements RecurrenceChargeRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return RecurrenceCharge::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
