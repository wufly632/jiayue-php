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

namespace App\Controller\User;

use App\Common\Api\Status;
use \App\Controller\AbstractController;
use App\Exception\ApiException;
use App\Exception\BusinessException;
use App\Request\User\RegisterRequest;
use App\Services\UserService;
use HyperfExt\Jwt\Contracts\JwtFactoryInterface;
use Hyperf\Di\Annotation\Inject;

/**
 */
class UserController extends AbstractController
{

    /**
     * @Inject
     * @var UserService
     */
    private $service;


    public function login()
    {
        $credentials = $this->request->inputs(['mobile', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            return $this->response->apiError(new Status(Status::UNAUTHORIZED, 'login failed'));
        }
        return $this->response->apiSuccess([
            'accessToken' => $token,
            'tokenType' => 'bearer',
            'expireIn' => make(JwtFactoryInterface::class)->make()->getPayloadFactory()->getTtl()
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $userData = $request->all();
        $user = $this->service->registerUser($userData);

        $token = auth()->login($user);
        return $this->response->apiSuccess([
                'accessToken' => $token,
                'tokenType' => 'bearer',
                'expireIn' => make(JwtFactoryInterface::class)->make()->getPayloadFactory()->getTtl()
            ]
        );
    }

    public function info()
    {
        return $this->response->apiSuccess(auth()->user());
    }


}
