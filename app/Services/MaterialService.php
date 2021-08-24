<?php


namespace App\Services;


use App\Model\Material;

class MaterialService
{
    public function paginate()
    {
        return Material::query()->paginate();
    }
}
