<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Controller\Product;

use App\Common\Api\Status;
use App\Controller\AbstractController;
use App\Request\ProductRequest;
use App\Services\ProductService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;

class ProductController extends AbstractController
{
    /**
     * @Inject
     * @var ProductService
     */
    protected $service;

    public function save(ProductRequest $request)
    {
        $res = $this->service->saveProductInfo($request);
        if ($res) {
            return $this->response->apiSuccess();
        }
        return $this->response->apiError(new Status(Status::ERR_PRODUCT));
    }

    public function index()
    {
        $products = $this->service->all();
        $data = [];

        // 获取所有产品类别
        $types = $this->service->getAllTypes();
        foreach ($types as $type) {
            $data[$type->id] = [];
            $data[$type->id]['name'] = $type->name;
            $data[$type->id]['total'] = 0;
            $data[$type->id]['products'] = [];
        }
        foreach ($products as $datum) {
            ++$data[$datum->product_type_id]['total'];
            $data[$datum->product_type_id]['products'][] = [
                'id' => $datum->id,
                'name' => $datum->title,
                'productModel' => $datum->product_model,
                'mainPicture' => $datum->main_picture,
            ];
        }
        return $this->response->apiSuccess($data);
    }

    public function list()
    {
        $pageSize = (int)$this->request->input('pageSize', 20);
        $res = $this->service->paginate($pageSize);
        $products = [];
        $total = $res->total();

        foreach ($res->items() as $datum) {
            $products[] = [
                'id' => $datum->id,
                'name' => $datum->title,
                'styleName' => $datum->style->name ?? '',
                'typeName' => $datum->productType->name ?? '',
                'productModel' => $datum->product_model,
                'mainPicture' => $datum->main_picture,
            ];
        }
        return $this->response->apiSuccess(compact('products', 'total'));
    }

    public function detail(RequestInterface $request, int $id)
    {
        if (!$id) {
            return $this->response->apiError(new Status(Status::UNFOUND));
        }
        $productInfo = $this->service->find($id);
        if (!$productInfo) {
            return $this->response->apiError(new Status(Status::UNFOUND));
        }
        $data = [
            'id' => $productInfo->id,
            'title' => $productInfo->title,
            'styleId' => $productInfo->style_id,
            'typeId' => $productInfo->product_type_id,
            'productModel' => $productInfo->product_model,
            'pictures' => json_decode($productInfo->pictures),
            'detailPictures' => json_decode($productInfo->detail_pictures),
            'materialChangeAble' => (bool)$productInfo->material_change_able,
            'hasFollowed' => false,
        ];
        if (auth()->id()) {
            $data['hasFollowed'] = $this->service->hasFollowed(auth()->id(), $id);
        }
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
}
