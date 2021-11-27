<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\RecurrenceRepository;
use App\Models\Recurrence;
use App\Validators\RecurrenceValidator;

/**
 * Class RecurrenceRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class RecurrenceRepositoryEloquent extends BaseRepository implements RecurrenceRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Recurrence::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
