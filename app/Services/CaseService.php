<?php


namespace App\Services;


use App\Model\ProductCase;

class CaseService
{
    public function paginate()
    {
        return ProductCase::query()->paginate();
    }

    public function find(int $id)
    {
        return ProductCase::query()->find($id);
    }
}
