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
namespace App\Exception\Handler;

use App\Common\Api\Response;
use App\Common\Log\Logger;
use App\Common\Log\StdoutLogger;
use App\Exception\ApiException;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Palmbuy\Logstash;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class AppExceptionHandler extends ExceptionHandler
{
    /**
     * @var StdoutLoggerInterface
     */
    protected $logger;

    public function __construct(StdoutLoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
//        $this->logger->error(sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile()));
//        $this->logger->error($throwable->getTraceAsString());
//        return $response->withHeader('Server', 'Hyperf')->withStatus(500)->withBody(new SwooleStream('Internal Server Error.'));
        // 阻止异常冒泡
        $this->stopPropagation();
        var_dump(request()->getPathInfo());
        if (env('app_env') != 'local'){
            StdoutLogger::error(
                sprintf(
                    "%s(%s): %s\n%s",
                    $throwable->getFile(),
                    $throwable->getLine(),
                    $throwable->getMessage(),
                    $throwable->getTraceAsString()
                )
            );
        }else{
            Logstash::channel(get_class($throwable))->error(
                sprintf(
                    "%s(%s): %s\n%s",
                    $throwable->getFile(),
                    $throwable->getLine(),
                    $throwable->getMessage(),
                    $throwable->getTraceAsString()
                )
            );
        }

        /**
         * @var ApiException $throwable
         */
        $apiResponse = new Response($response);

        return $apiResponse->apiError($throwable->status, $throwable->getData());
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
