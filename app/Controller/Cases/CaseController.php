<?php


namespace App\Controller\Cases;


use App\Common\Api\Status;
use App\Controller\AbstractController;
use App\Request\CaseRequest;
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

    public function index(RequestInterface $request)
    {
        $res = $this->service->paginate($request);
        $cases = [];
        $total = $res->total();

        foreach ($res->items() as $datum) {
            $cases[] = [
                'id' => $datum->id,
                'name' => $datum->title,
                'caseModel' => $datum->case_model,
                'mainPicture' => $datum->main_picture,
            ];
        }
        return $this->response->apiSuccess(compact('cases', 'total'));
    }

    public function detail(RequestInterface $request, int $id)
    {
        if (!$id) {
            return $this->response->apiError(new Status(Status::UNFOUND));
        }
        $caseInfo = $this->service->find($id);
        if (!$caseInfo) {
            return $this->response->apiError(new Status(Status::UNFOUND));
        }
        $data = [
            'id' => $caseInfo->id,
            'title' => $caseInfo->title,
            'caseModel' => $caseInfo->case_model,
            'pictures' => json_decode($caseInfo->pictures),
            'detailContent' => $caseInfo->detail_content,
        ];
        return $this->response->apiSuccess($data);
    }

    public function delete(RequestInterface $request)
    {
        $id = $request->input('id');
        $product = $this->service->find($id);
        if (!$product) {
            return $this->response->apiError(new Status(Status::UNFOUND));
        }
        if ($product->delete()) {
            return $this->response->apiSuccess();
        }
        return $this->response->apiError(new Status(Status::ERR_SYS));
    }

    public function save(CaseRequest $request)
    {
        $res = $this->service->saveCaseInfo($request);
        if ($res) {
            return $this->response->apiSuccess();
        }
        return $this->response->apiError(new Status(Status::ERR_PRODUCT));
    }
}
