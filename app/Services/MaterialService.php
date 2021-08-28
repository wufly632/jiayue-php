<?php


namespace App\Services;


use App\Model\Material;

class MaterialService
{
    public function paginate($pageSize)
    {
        return Material::query()->paginate($pageSize);
    }
}
