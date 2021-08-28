<?php


namespace App\Services;


use App\Model\User;
use App\Model\UserFave;
use Carbon\Carbon;
use Hyperf\HttpServer\Contract\RequestInterface;

class FaveService
{

    public function paginate(RequestInterface $request)
    {
        $userFave = UserFave::query();
        if ($createdAt = $request->input('createdRange')) {
            $dateRange = [];
            foreach ($createdAt as $item) {
                $dateRange[] = Carbon::parse($item)->addHours(8)->toDateTimeString();
            }
            $userFave = $userFave->whereBetween('created_at', $dateRange);
        }
        if ($productId = $request->input('productId')) {
            $userFave = $userFave->where('product_id', $productId);
        }
        if ($userMobile = $request->input('userMobile')) {
            $userIds = User::query()->where('mobile', 'like', '%' . $userMobile . '%')->pluck('id')->toArray();
            $userFave = $userFave->whereIn('user_id', $userIds);
        }
        $pageSize = (int)$request->input('pageSize', 20);
        return $userFave->paginate($pageSize);
    }

    public function getByUserId($userId, $pageSize)
    {
        return UserFave::query()->where('user_id', $userId)->get();
    }

    public function fave($userId, $productId)
    {
        $userFave = UserFave::query()->where(['user_id' => $userId, 'product_id' => $productId])->first();
        if ($userFave) {
            return $userFave->delete();
        }
        return UserFave::query()->insert(['user_id' => $userId, 'product_id' => $productId,'created_at' => Carbon::now()->toDateTimeString(),'updated_at' => Carbon::now()->toDateTimeString()]);
    }
}
