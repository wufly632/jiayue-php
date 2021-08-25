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
            var_dump($dateRange);
            $userFave = $userFave->whereBetween('created_at', $dateRange);
        }
        if ($productId = $request->input('productId')) {
            $userFave = $userFave->where('product_id', $productId);
        }
        if ($userMobile = $request->input('userMobile')) {
            $userIds = User::query()->where('mobile', 'like', '%' . $userMobile . '%')->pluck('id')->toArray();
            $userFave = $userFave->whereIn('user_id', $userIds);
        }
        return $userFave->paginate();
    }
}
