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
        var_dump((new BcryptDriver())->make('admin123'));
        $credentials = $this->request->inputs(['mobile', 'password']);
        var_dump(auth('backend')->attempt($credentials));
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
