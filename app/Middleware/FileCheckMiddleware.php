<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Helper\FileHelper;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Hyperf\Utils\Context;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FileCheckMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var HttpResponse
     */
    protected HttpResponse $response;

    public function __construct(ContainerInterface $container, HttpResponse $response)
    {
        $this->container = $container;
        $this->response = $response;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $files = $request->getUploadedFiles();

        if(!array_key_exists('image', $files))
        {
            return $this->response->json([
                'error' => 1,
                'message' => '请上传图片'
            ]);
        }

        $image = $files['image']->toArray();
        if($image['size'] > (2*1024*1024))
        {
            return $this->response->json([
                'error' => 2,
                'message' => '最大支持2M以内的图片上传'
            ]);
        }

        $type = FileHelper::getFileType($image['tmp_file']);
        if($type === 'unknown')
        {
            return $this->response->json([
                'error' => 3,
                'message' => '不支持的图片格式'
            ]);
        }
        $request = Context::override(ServerRequestInterface::class, function () use($request, $type){
            return $request->withAttribute('file_type', $type);
        });
        return $handler->handle($request);
    }
}