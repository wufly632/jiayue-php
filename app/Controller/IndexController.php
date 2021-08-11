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

use Hyperf\Di\Annotation\Inject;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Utils\ApplicationContext;
use Palmbuy\Logstash;
//use Psr\Log\LoggerInterface;

class IndexController extends AbstractController
{



    public function index()
    {
        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();

        Logstash::channel('test');

        ApplicationContext::getContainer()->get(LoggerFactory::class)->get('default')->info(333);

        return [
            'method' => $method,
            'message' => "Hello {$user}.",
        ];
    }
}
