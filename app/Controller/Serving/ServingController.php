<?php


namespace App\Controller\Serving;


use App\Common\Api\Status;
use App\Controller\AbstractController;
use App\Services\ServingService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;

class ServingController extends AbstractController
{
    /**
     * @Inject
     * @var ServingService
     */
    private $service;

    public function index(RequestInterface $request)
    {
        $res = $this->service->paginate($request->input('type'));
        $total = $res->total() ?? 0;
        $data = [];
        foreach ($res->items() as $datum) {
            $data[] = [
                'id' => $datum->id,
                'type' => $datum->type,
                'title' => $datum->title,
                'content' => $datum->content,
            ];
        }
        return $this->response->apiSuccess(compact('total', 'data'));
    }

    public function detail(RequestInterface $request, int $id)
    {
        if (!$id) {
            return $this->response->apiError(new Status(Status::UNFOUND));
        }
        $newInfo = $this->service->find($id);
        if (!$newInfo) {
            return $this->response->apiError(new Status(Status::UNFOUND));
        }
        $data = [
            'title' => $newInfo->title,
            'content' => $newInfo->content,
        ];
        return $this->response->apiSuccess($data);
    }
}
