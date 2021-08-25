<?php


namespace App\Services;


use App\Model\StaticPage;

class StaticPageService
{

    public function findByType($type)
    {
        $data = StaticPage::query()->where(['type' => $type])->first();
        return data_get($data, 'content');
    }

    public function save($type, $content)
    {
        return StaticPage::query()->updateOrCreate(['type' => $type], ['content' => $content]);
    }
}
