<?php


namespace App\Exception\Handler;


use App\Common\Api\Response;
use App\Common\Api\Status;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class ValidationExceptionHandler extends \Hyperf\Validation\ValidationExceptionHandler
{
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $this->stopPropagation();
        /** @var \Hyperf\Validation\ValidationException $throwable */
        $body = $throwable->validator->errors()->first();
        if (! $response->hasHeader('content-type')) {
            $response = $response->withAddedHeader('content-type', 'text/json; charset=utf-8');
        }
        $apiResponse = new Response($response);

        return $apiResponse->apiError(new Status(Status::ERR_PARAM_VALIDAE), $body);
    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof \Hyperf\Validation\ValidationException;
    }
}
