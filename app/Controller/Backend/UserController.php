<?php


namespace App\Controller\Backend;


use App\Common\Api\Status;
use App\Controller\AbstractController;
use HyperfExt\Hashing\Driver\BcryptDriver;
use HyperfExt\Hashing\Hash;
use HyperfExt\Jwt\Contracts\JwtFactoryInterface;

class UserController extends AbstractController
{
    public function login()
    {
        $credentials = $this->request->inputs(['mobile', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            return $this->response->apiError(new Status(Status::ERR_AUTH,'login failed'));
        }
        return $this->response->apiSuccess([
            'accessToken' => $token,
            'tokenType' => 'bearer',
            'expireIn' => make(JwtFactoryInterface::class)->make()->getPayloadFactory()->getTtl()
        ]);
    }
}
