<?php


namespace App\Controller\Fave;


use App\Common\Api\Status;
use App\Controller\AbstractController;
use App\Services\FaveService;
use App\Services\ProductService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use stdClass;

class FaveController extends AbstractController
{
    /**
     * @Inject
     * @var FaveService
     */
    private $service;

    /**
     * @Inject
     * @var ProductService
     */
    private $productService;

    public function index(RequestInterface $request)
    {
        $res = $this->service->paginate($request);
        $faves = [];
        $total = $res->total();

        foreach ($res->items() as $datum) {
            $faves[] = [
                'id' => $datum->id,
                'productId' => $datum->product_id,
                'productName' => data_get($datum->product, 'title'),
                'productPicture' => data_get($datum->product, 'main_picture'),
                'userId' => $datum->user_id,
                'userMobile' => data_get($datum->user, 'mobile'),
                'createdAt' => $datum->created_at->toDateTimeString(),
            ];
        }
        return $this->response->apiSuccess(compact('faves', 'total'));
    }

    public function fave(RequestInterface $request)
    {
        $productId = $request->input('productId');
        if (!$productId) {
            return $this->response->apiError(new Status(Status::ERR_PARAM_VALIDAE));
        }
        $res = $this->service->fave(auth()->id(),$productId);
        if ($res) {
            return $this->response->apiSuccess();
        }
        return $this->response->apiError(new Status(Status::ERR_SYS));
    }

    public function list()
    {
        if(! auth()->id()) {
           return $this->response->apiSuccess();
        }
        $faves = $this->service->getByUserId(auth()->id(), $this->request->input('pageSize', 20));

        $data = [];

        // 获取所有产品类别
        $types = $this->productService->getAllTypes();
        foreach ($types as $type) {
            $data[$type->id] = [];
            $data[$type->id]['name'] = $type->name;
            $data[$type->id]['total'] = 0;
            $data[$type->id]['products'] = [];
        }
        foreach ($faves as $fave) {
            $datum = $fave->product;
            ++$data[$datum->product_type_id]['total'];
            $data[$datum->product_type_id]['products'][] = [
                'id' => $datum->id,
                'name' => $datum->title,
                'productModel' => $datum->product_model,
                'mainPicture' => $datum->main_picture,
            ];
        }
        $data = collect($data)->filter(function ($item) {
            return $item['total'] > 0;
        });
        if (count($data) == 0 ){
            $data = new stdClass();
        }
        return $this->response->apiSuccess($data);
    }
}
