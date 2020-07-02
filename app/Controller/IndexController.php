<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace App\Controller;

use App\Model\ImageList;
use App\Service\QueueService;
use App\StorageEngine\Engine;
use Hyperf\Di\Annotation\Inject;
use App\Helper\FileHelper;
use Swoole\Exception;
use V1\StorageEngine\Entity\FileInfo;

class IndexController extends AbstractController
{
    /**
     * @Inject
     * @var Engine
     */
    protected Engine $engine;

    /**
     * @Inject
     * @var QueueService
     */
    protected QueueService $queueService;

    public function upload()
    {
        $imageFile = $this->request->file('image');
        $uploadData = $imageFile->toArray();
        $hash = $this->request->post('hash');

        // 根据hash值查询是否有相同的图片已经上传，实现秒传功能
        if(empty($hash)) // 如果没有附带hash，则从自动获取上传文件的hash
        {
            $hash = FileHelper::md5($uploadData['tmp_file']);
        }
        /**
         * @var ImageList|null $image
         */
        $image = ImageList::query()->where('hash', $hash)->first();
        if($image === null)
        {
            $type = $this->request->getAttribute('file_type');
            try {
                $size = FileHelper::getImageSize($uploadData['tmp_file']);

                $path = sprintf('%s/%s/%s/%s', date('Y'), date('m'), date('d'), md5(uniqid().microtime()).'.'.$type);
                $fileInfo = new FileInfo($path);
                $this->engine->AddFile($fileInfo);
                $image_content = file_get_contents($uploadData['tmp_file']);
                $this->engine->WriteText($image_content);

                // 插入数据库记录
                $image = new ImageList();
                $image->name = pathinfo($uploadData['name'], PATHINFO_FILENAME);
                $image->width = $size['w'];
                $image->height = $size['h'];
                $image->hash = $hash;
                $image->type = $type;
                $image->path = $path;
                $image->engine = $this->engine->toString();
                $image->tags = '图片标签';
                $image->ip = $this->getClientIp();
                $image->code = md5(microtime().uniqid('access_code'));
                $image->save();

                // TODO:: 添加消费者队列
                $this->queueService->pushImageQueue('add', $image, $image_content);
            }
            catch (Exception $exception)
            {
                throw $exception;
            }
        }
        else // 查询到有相同哈希值的，复制一条数据
        {
            $imageOld = $image;
            $image = new ImageList();
            $image->name = pathinfo($uploadData['name'], PATHINFO_FILENAME);
            $image->width = $imageOld->width;
            $image->height = $imageOld->height;
            $image->hash = $imageOld->hash;
            $image->type = $imageOld->type;
            $image->path = $imageOld->path;
            $image->engine = $imageOld->engine;
            $image->tags = $imageOld->tags;
            $image->ip = $this->getClientIp();
            $image->code = md5(microtime().uniqid('access_code'));
            $image->save();
        }
        // 上传完成后返回可以访问图片的路径，例如
        // https://www.young-pictures.com/s/8f60c8102d29fcd525162d02eed4566b
        $domain = config('domain');
        return [
            'error' => 0,
            'message' => 'OK',
            'data' => [
                'url' => $domain.'/s/'.$image->code,
                'name' => $image->name
            ]
        ];
    }


    public function show(string $code)
    {
        // 这里应该是从ES引擎里面检索图片
        // 通过添加消费者队列，实现对图片的访问计次等

        /**
         * @var ImageList|null $image
         */
        $image = ImageList::query()->where('code', $code)->first();
        if($image === null)
        {
            return $this->response->json([404])->withStatus(404);
        }

        if($image->engine !== $this->engine->toString())
        {
            $options = config('storage_engine.storage')[$image->engine];
            $this->engine->SwitchEngine($image->engine, $options);
        }

        // TODO:: 添加消费者队列
        $this->queueService->pushImageQueue('show', $image);

        $this->engine->AddFile(new FileInfo($image->path));
        return $this->response->raw($this->engine->ReadAsText())->withHeader('Content-Type', 'image/'.$image->type);
    }

    public function browse()
    {
        $list = ImageList::query()->orderBy('create_time', 'desc')->orderBy('pv', 'desc')->paginate(20, ['create_time', 'name', 'code']);
        $domain = config('domain');
        foreach ($list as &$item)
        {
            $item->url = $domain.'/s/'.$item->code;
        }
        return $list;
    }

    public function test()
    {
        return [
            $this->request->getHeaders(),
            $this->request->getServerParams()
        ];
    }
}
