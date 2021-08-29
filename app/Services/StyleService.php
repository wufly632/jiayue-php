<?php


namespace App\Services;


use App\Model\Style;

class StyleService
{

    public function all($status)
    {
        $model = Style::query();
        if ($status) {
            $model = $model->where('status', $status);
        }
        return $model->get();
    }

    public function saveStyleInfo(\App\Request\StyleRequest $request)
    {
        $id = $request->input('id');
        $styleInfo = [
            'name' => $request->input('name'),
            'small_picture' => $request->input('smallPicture'),
            'big_picture' => $request->input('bigPicture'),
        ];
        return Style::query()->updateOrCreate(['id' => $id], $styleInfo);
    }

    public function find($id)
    {
        return Style::query()->find($id);
    }
}
