<?php


namespace App\Controller\Appearance;
use App\Common\Api\Status;
use App\Request\AppearanceRequest;
use App\Services\AppearanceService;
use Hyperf\Di\Annotation\Inject;


use App\Controller\AbstractController;
use Hyperf\HttpServer\Contract\RequestInterface;

class AppearanceController extends AbstractController
{

    /**
     * @Inject
     * @var AppearanceService
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
            return $this->response->apiError(new Status(Status::UNFOUND, ''));
        }
        $appearanceInfo = $this->service->find($id);
        if (!$appearanceInfo) {
            return $this->response->apiError(new Status(Status::UNFOUND));
        }
        $data = [
            'title' => $appearanceInfo->title,
            'pictures' => json_decode($appearanceInfo->pictures),
        ];
        return $this->response->apiSuccess($data);
    }

    public function save(AppearanceRequest $request)
    {
        $res = $this->service->saveAppearanceInfo($request);
        if ($res) {
            return $this->response->apiSuccess();
        }
        return $this->response->apiError(new Status(Status::ERR_PRODUCT));
    }
}
