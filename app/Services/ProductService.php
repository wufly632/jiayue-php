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
            ];
            if (!$productId) {
                $product = Product::query()->create($productInfo);
                $productId = $product->id;
            } else {
                Product::query()->where(['id' => $productId])->update($productInfo);
                ProductSize::query()->where(['product_id' => $productId])->delete();
            }
            // 尺码信息  删除原来的，直接添加新的
            $sizeInfos = [];
            foreach ($request->input('sizes') as $size) {
                $sizeInfos[] = [
                    'product_id' => $productId,
                    'name' => $size,
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                ];
            }
            if (count($sizeInfos) > 0) {
                ProductSize::query()->where(['product_id' => $productId])->insert($sizeInfos);
            }
            Db::commit();
            return true;
        } catch (\Exception $exception) {
            Db::rollBack();
            Logstash::channel('product')->error('商品保存失败：' . $exception->getMessage());
        }
        return false;
    }

    public function paginate()
    {
        return Product::query()->paginate();
    }

    public function getAllTypes()
    {
        return ProductType::query()->get();
    }

    public function all()
    {
        return Product::query()->get();
    }

    public function find(int $id)
    {
        return Product::query()->find($id);
    }

    public function hasFollowed(int $userId, int $productId)
    {
        return false; // TODO
    }
}
