<?php


namespace App\Services;


use App\Model\ProductType;
use App\Model\Style;

class TypesService
{

    public function all($status)
    {
        $model = ProductType::query();
        if ($status) {
            $model = $model->where('status', $status);
        }
        return $model->orderByDesc('sort')->get();
    }

    public function saveTypeInfo(\App\Request\TypesRequest $request)
    {
        $id = $request->input('id');
        $styleInfo = [
            'name' => $request->input('name'),
            'en_name' => $request->input('enName'),
            'sort' => $request->input('sort', 99),
        ];
        return ProductType::query()->updateOrCreate(['id' => $id], $styleInfo);
    }

    public function find($id)
    {
        return ProductType::query()->find($id);
    }
}
