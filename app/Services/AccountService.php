<?php

namespace App\Services;

use App\Repositories\AccountRepositoryEloquent as Eloquent;
use App\Repositories\Contracts\AccountRepository as Contract;
use Exception;

class AccountService
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

    public function pluck()
    {
        return $this->getRepository()->get()->pluck('name', 'id')->toArray();
    }

    /**
     * @return Eloquent
     */
    private function getRepository() {
        return app(Contract::class);
    }
}
