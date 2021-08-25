<?php


namespace App\Controller\Fave;


use App\Controller\AbstractController;
use App\Services\FaveService;
use App\Services\ProductService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;

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

    }
}
