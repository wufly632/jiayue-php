<?php


namespace App\Services;


use App\Model\Product;
use App\Model\ProductCase;
use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Utils\Arr;
use Palmbuy\Logstash;

class CaseService
{
    public function paginate(RequestInterface $request)
    {
        return ProductCase::query()->paginate();
    }

    public function find($id)
    {
        return ProductCase::query()->find($id);
    }

    public function saveCaseInfo(\App\Request\CaseRequest $request)
    {
        try {
            Db::beginTransaction();
            // 案例信息
            $caseId = $request->input('id');
            $caseInfo = [
                'title' => $request->input('title'),
                'case_model' => $request->input('caseModel'),
                'main_picture' => $request->input('pictures', []) ? Arr::first($request->input('pictures')) : '',
                'pictures' => json_encode($request->input('pictures', [])),
                'detail_content' => $request->input('detailContent', ''),
            ];
            ProductCase::query()->updateOrCreate(['id' => $caseId], $caseInfo);
            Db::commit();
            return true;
        } catch (\Exception $exception) {
            Db::rollBack();
            Logstash::channel('case')->error('案例保存失败：' . $exception->getMessage());
        }
        return false;
    }
}
