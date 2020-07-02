<?php

declare(strict_types=1);

namespace App\Service;

use App\Job\ImageJob;
use Hyperf\AsyncQueue\Driver\DriverFactory;
use Hyperf\AsyncQueue\Driver\DriverInterface;

class QueueService
{
    /**
     * @var DriverInterface
     */
    protected DriverInterface $driver;

    public function __construct(DriverFactory $driverFactory)
    {
        $this->driver = $driverFactory->get('default');
    }

    /**
     * 生产消息
     * @param string $action
     * @param object $model
     * @param string $imageContent
     * @param int $delay
     * @return bool
     */
    public function pushImageQueue(string $action, object $model, string $imageContent = '', int $delay = 0) : bool
    {
        return $this->driver->push(new ImageJob($action, $model, $imageContent), $delay);
    }
}