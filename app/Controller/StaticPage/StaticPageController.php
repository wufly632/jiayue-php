<?php


namespace App\Controller\StaticPage;


use App\Common\Api\Status;
use App\Controller\AbstractController;
use App\Services\StaticPageService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;

class StaticPageController extends AbstractController
{
    const ADDRESS_TYPE = 1;
    const ABOUT_TYPE = 2;
    const CONTACT_TYPE = 3;
    /**
     * @Inject
     * @var StaticPageService
     */
    protected $service;

    public function addressInfo()
    {
        $content = $this->service->findByType(self::ADDRESS_TYPE);
        $address = json_decode($content);
        return $this->response->apiSuccess(compact('address'));
    }

    public function addressSave(RequestInterface $request)
    {
        $address = $request->input('address', []);
        $type = self::ADDRESS_TYPE;
        $res = $this->service->save($type, json_encode($address));
        if ($res) {
            return $this->response->apiSuccess();
        }
        return $this->response->apiError(new Status(Status::ERR_SYS));
    }


    public function aboutInfo()
    {
        $content = $this->service->findByType(self::ABOUT_TYPE);
        return $this->response->apiSuccess(compact('content'));
    }

    public function aboutSave(RequestInterface $request)
    {
        $content = $request->input('content', '');
        $type = self::ABOUT_TYPE;
        $res = $this->service->save($type, $content);
        if ($res) {
            return $this->response->apiSuccess();
        }
        return $this->response->apiError(new Status(Status::ERR_SYS));
    }


    public function contactInfo()
    {
        $content = $this->service->findByType(self::CONTACT_TYPE);
        return $this->response->apiSuccess(compact('content'));
    }

    public function contactSave(RequestInterface $request)
    {
        $content = $request->input('content', '');
        $type = self::CONTACT_TYPE;
        $res = $this->service->save($type, $content);
        if ($res) {
            return $this->response->apiSuccess();
        }
        return $this->response->apiError(new Status(Status::ERR_SYS));
    }
}
