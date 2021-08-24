<?php


namespace App\Controller\Cases;


use App\Common\Api\Status;
use App\Controller\AbstractController;
use App\Services\CaseService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;

class CaseController extends AbstractController
{
    /**
     * @Inject
     * @var CaseService
     */
    private $service;

    public function index()
    {
        $res = $this->service->paginate();
        $total = $res->total() ?? 0;
        $data = [];
        foreach ($res->items() as $datum) {
            $data[] = [
                'id' => $datum->id,
                'title' => $datum->title,
                'mainPicture' => $datum->main_picture,
            ];
        }
        return $this->response->apiSuccess(compact('total', 'data'));
    }

    public function detail(RequestInterface $request, int $id)
    {
        if (!$id) {
            return $this->response->apiError(Status::UNFOUND);
        }
        $caseInfo = $this->service->find($id);
        if (!$caseInfo) {
            return $this->response->apiError(Status::UNFOUND);
        }
        $data = [
            'title' => $caseInfo->title,
            'mainPicture' => $caseInfo->main_picture,
        ];
        return $this->response->apiSuccess($data);
    }
}
