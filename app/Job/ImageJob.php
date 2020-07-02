<?php

declare(strict_types=1);

namespace App\Job;


use App\Service\ImageTagService;
use Hyperf\AsyncQueue\Job;

class ImageJob extends Job
{
    public string $action;

    public object $model;

    public string $file;

    public function __construct(string $action, object $model, string $file = '')
    {
        $this->action = $action;
        $this->model = $model;
        $this->file = $file;
    }

    public function handle()
    {
        switch ($this->action)
        {
            case 'add':
                // TODO:: 增加图片标签识别，将数据添加进ES引擎
                $imageTagService = new ImageTagService($this->file);
                $labels = $imageTagService->DetectLabel();

                // 取前三个标签
                $tags = '';
                for($i = 0; $i < 3; $i++)
                {
                    $tags .= $labels[$i]->getName(). ',';
                }
                $this->model->tags = $tags;
                $this->model->save();
                break;
            case 'show':
                $this->model->pv += 1;
                $this->model->save();
                break;
        }
    }
}