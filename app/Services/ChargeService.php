<?php

namespace App\Services;

use App\Repositories\ChargeRepositoryEloquent as Eloquent;
use App\Repositories\Contracts\ChargeRepository as Contract;
use Exception;

class ChargeService
{
    public function getAll(array $filter = null)
    {
        if ($filter === null) {
            throw new Exception('parameter filter do not implemented');
        }
        return $this->getRepository()->get();
    }

    public function getPaginate(array $filter = null)
    {
        if ($filter === null) {
            throw new Exception('parameter filter do not implemented');
        }
        return $this->getRepository()
            ->orderBy('id', 'desc')
            ->paginate();
    }

    /**
     * @return Eloquent
     */
    private function getRepository() {
        return app(Contract::class);
    }
}
