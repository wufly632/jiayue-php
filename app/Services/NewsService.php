<?php


namespace App\Services;


use App\Model\News;
use App\Request\NewsRequest;

class NewsService
{
    public function paginate()
    {
        return News::query()->paginate();
    }

    public function find(int $id)
    {
        return News::query()->find($id);
    }

    public function saveNewsInfo(NewsRequest $request)
    {
        $id = $request->input('id');
        $newsInfo = [
            'type' => $request->input('type'),
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'description' => $request->input('description'),
        ];
        return News::query()->updateOrCreate(['id' => $id], $newsInfo);
    }
}
