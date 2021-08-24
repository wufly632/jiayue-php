<?php


namespace App\Services;


use App\Model\Serving;

class ServingService
{
    public function paginate($type = null)
    {
        $model = Serving::query();
        if ($type) {
            $model = $model->where('type', $type);
        }
        return $model->paginate();
    }

    public function find(int $id)
    {
        return Serving::query()->find($id);
    }
}
