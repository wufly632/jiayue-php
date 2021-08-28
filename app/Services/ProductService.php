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

namespace App\Services;

use App\Model\Product;
use App\Model\ProductSize;
use App\Model\ProductType;
use App\Model\UserFave;
use App\Request\ProductRequest;
use Carbon\Carbon;
use Hyperf\DbConnection\Db;
use Hyperf\Utils\Arr;
use Palmbuy\Logstash;

class ProductService
{
    public function saveProductInfo(ProductRequest $request): bool
    {
        try {
            Db::beginTransaction();
            // 商品信息
            $productId = $request->input('id');
            $productInfo = [
                'style_id' => $request->input('styleId'),
                'product_type_id' => $request->input('typeId'),
                'title' => $request->input('title'),
                'product_model' => $request->input('productModel'),
                'main_picture' => $request->input('pictures', []) ? Arr::first($request->input('pictures')) : '',
                'pictures' => json_encode($request->input('pictures', [])),
                'detail_pictures' => json_encode($request->input('detailPictures', [])),
                'material_change_able' => $request->input('materialChangeAble', false),
            ];
            if (!$productId) {
                $product = Product::query()->create($productInfo);
                $productId = $product->id;
            } else {
                Product::query()->where(['id' => $productId])->update($productInfo);
            }
            Db::commit();
            return true;
        } catch (\Exception $exception) {
            Db::rollBack();
            Logstash::channel('product')->error('商品保存失败：' . $exception->getMessage());
        }
        return false;
    }

    public function paginate($pageSize = 20)
    {
        return Product::query()->paginate($pageSize);
    }

    public function getAllTypes()
    {
        return ProductType::query()->get();
    }

    public function all($styleId)
    {
        return Product::query()->where(['style_id' => $styleId])->get();
    }

    public function find(int $id)
    {
        return Product::query()->find($id);
    }

    public function hasFollowed($userId, $productId)
    {
        return UserFave::query()->where(['user_id' => $userId, 'product_id' => $productId])->exists();
    }
}
