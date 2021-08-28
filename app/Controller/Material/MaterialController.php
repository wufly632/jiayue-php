<?php


namespace App\Controller\Material;


use App\Controller\AbstractController;
use App\Services\MaterialService;
use Hyperf\Di\Annotation\Inject;

class MaterialController extends AbstractController
{
    /**
     * @Inject
     * @var MaterialService
     */
    private $service;

    public function index()
    {
        $pageSize = (int)$this->request->input('pageSize', 20);
        $res = $this->service->paginate($pageSize);
        $total = $res->total() ?? 0;
        $data = [];
        foreach ($res->items() as $datum) {
            $data[] = [
                'id' => $datum->id,
                'title' => $datum->title,
                'content' => $datum->content,
                'mainPicture' => $datum->main_picture,
            ];
        }
        return $this->response->apiSuccess(compact('total', 'data'));
    }
}
