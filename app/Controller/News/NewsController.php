<?php
declare(strict_types=1);

namespace App\Controller\News;


use App\Common\Api\Status;
use App\Controller\AbstractController;
use App\Model\News;
use App\Request\NewsRequest;
use App\Services\NewsService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;

class NewsController extends AbstractController
{
    /**
     * @Inject
     * @var NewsService
     */
    private $service;

    public function index()
    {
        $res = $this->service->paginate();
        $total = $res->total() ?? 0;
        $data = [];
        foreach ($res->items() as $datum) {
            $data[] = [
                'id' => $datum->id,
//                'type' => $datum->type,
                'title' => $datum->title,
                'description' => $datum->description,
            ];
        }
        return $this->response->apiSuccess(compact('total', 'data'));
    }

    public function detail(RequestInterface $request, int $id)
    {
        if (!$id) {
           return $this->response->apiError(Status::UNFOUND);
        }
        $newInfo = $this->service->find($id);
        if (!$newInfo) {
            return $this->response->apiError(Status::UNFOUND);
        }
        $data = [
          'title' => $newInfo->title,
          'content' => $newInfo->content,
        ];
        return $this->response->apiSuccess($data);
    }

    public function save(NewsRequest $request)
    {
        $res = $this->service->saveNewsInfo($request);
        if ($res) {
            return $this->response->apiSuccess();
        }
        return $this->response->apiError(new Status(Status::ERR_PRODUCT));
    }
}
