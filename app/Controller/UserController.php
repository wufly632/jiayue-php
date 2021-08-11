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
namespace App\Controller;

use App\Common\Api\Status;
use App\Constants\ErrorCode;
use App\Exception\ApiException;
use App\Exception\BusinessException;
use HyperfExt\Jwt\Contracts\JwtFactoryInterface;

/**
 */
class UserController extends AbstractController
{



    public function login()
    {
        $credentials = $this->request->inputs(['email', 'password']);
        var_dump($credentials);
        if (!$token = auth()->attempt($credentials)) {
            return $this->response->apiError(new Status(Status::UNAUTHORIZED,'login failed'));
        }
        return $this->response->apiSuccess([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expire_in' => make(JwtFactoryInterface::class)->make()->getPayloadFactory()->getTtl()
        ]);
    }

    public function info()
    {
        $id = auth()->id();
        $user = auth()->user();
        var_dump(auth()->check());
        var_dump($id);
        var_dump($user);
        return $this->response->apiSuccess(auth()->user());
    }

    public function testException(){
        ApiException::break(Status::ERR_SYS,'系统');
    }



}
