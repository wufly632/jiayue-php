<?php


namespace App\Controller\Product;


use App\Common\Api\Status;
use App\Controller\AbstractController;
use App\Request\StyleRequest;
use App\Request\TypesOnOfflineRequest;
use App\Request\TypesRequest;
use App\Services\TypesService;
use Hyperf\Di\Annotation\Inject;

class TypesController extends AbstractController
{
    /**
     * @Inject
     * @var TypesService
     */
    private $service;

    public function index()
    {
        $types = [];
        $res = $this->service->all();
        foreach ($res as $re) {
            $types[] = [
                'id' => $re->id,
                'name' => $re->name,
                'enName' => $re->en_name,
                'status' => $re->status,
            ];
        }
        return $this->response->apiSuccess(compact('types'));
    }

    public function save(TypesRequest $request)
    {
        $res = $this->service->saveTypeInfo($request);
        if ($res) {
            return $this->response->apiSuccess();
        }
        return $this->response->apiError(new Status(Status::ERR_PRODUCT));
    }

    public function onOffline(TypesOnOfflineRequest $request)
    {
        $productType = $this->service->find($request->input('id'));
        if (!$productType) {
            return $this->response->apiError(new Status(Status::UNFOUND));
        }
        if ($productType->status == $request->input('status')) {
            return $this->response->apiError(new Status(Status::UNFOUND, '无需操作'));
        }
        $productType->status = $request->input('status');
        if ($productType->save()) {
            return $this->response->apiSuccess();
        }
        return $this->response->apiError(new Status(Status::ERR_SYSTEM));
    }
}
