<?php


namespace App\Services;


use App\Model\Serving;

class ServingService
{
    public function paginate($request)
    {
        $model = Serving::query();
        $type = $request->input('type');
        if ($type) {
            $model = $model->where('type', $type);
        }
        $pageSize = (int)$request->input('pageSize', 20);
        return $model->paginate($pageSize);
    }

    public function find(int $id)
    {
        return Serving::query()->find($id);
    }
}
