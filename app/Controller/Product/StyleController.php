<?php


namespace App\Controller\Product;


use App\Common\Api\Status;
use App\Controller\AbstractController;
use App\Model\Product;
use App\Request\StyleOnOfflineRequest;
use App\Request\StyleRequest;
use App\Services\StyleService;
use Hyperf\Di\Annotation\Inject;

class StyleController extends AbstractController
{
    /**
     * @Inject
     * @var StyleService
     */
    private $service;

    public function index()
    {
        $styles = [];
        // 获取所有商品的style
        $styleIds = Product::query()->pluck('style_id')->unique()->toArray();
        $status = $this->request->getPathInfo() === '/api/product/style' ? 1 : 0;
        $res = $this->service->all($status);
        foreach ($res as $re) {
            if (in_array($re->id, $styleIds)) {
                $styles[] = [
                    'id' => $re->id,
                    'name' => $re->name,
                    'smallPicture' => $re->small_picture,
                    'bigPicture' => $re->big_picture,
                    'status' => $re->status,
                ];
            }
        }

        return $this->response->apiSuccess(compact('styles'));
    }

    public function save(StyleRequest $request)
    {
        $res = $this->service->saveStyleInfo($request);
        if ($res) {
            return $this->response->apiSuccess();
        }
        return $this->response->apiError(new Status(Status::ERR_PRODUCT));
    }

    public function onOffline(StyleOnOfflineRequest $request)
    {
        $productStyle = $this->service->find($request->input('id'));
        if (!$productStyle) {
            return $this->response->apiError(new Status(Status::UNFOUND));
        }
        if ($productStyle->status == $request->input('status')) {
            return $this->response->apiError(new Status(Status::UNFOUND, '无需操作'));
        }
        $productStyle->status = $request->input('status');
        if ($productStyle->save()) {
            return $this->response->apiSuccess();
        }
        return $this->response->apiError(new Status(Status::ERR_SYSTEM));
    }
}
