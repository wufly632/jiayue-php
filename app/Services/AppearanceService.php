<?php


namespace App\Services;


use App\Model\Appearance;
use App\Request\AppearanceRequest;
use Hyperf\Utils\Arr;

class AppearanceService
{
    public function paginate($pageSize)
    {
        return Appearance::query()->paginate($pageSize);
    }

    public function find(int $id)
    {
        return Appearance::query()->find($id);
    }

    public function saveAppearanceInfo(AppearanceRequest $request)
    {
        $id = $request->input('id');
        $newsInfo = [
            'title' => $request->input('title'),
            'main_picture' => Arr::first($request->input('pictures')),
            'pictures' => json_encode($request->input('pictures')),
        ];
        return Appearance::query()->updateOrCreate(['id' => $id], $newsInfo);
    }
}
