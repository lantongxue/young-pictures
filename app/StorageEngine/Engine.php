<?php

declare(strict_types=1);

namespace App\StorageEngine;


use V1\StorageEngine\Entity\FileInfo;
use V1\StorageEngine\StorageEngine;
use V1\StorageEngine\Entity\StreamBuffer;

/**
 * Class Engine
 * @package App\StorageEngine
 * @method string ReadAsText()
 * @method StreamBuffer ReadAsStreamBuffer()
 * @method int WriteText(string $content)
 * @method int WriteStream(StreamBuffer $buffer)
 * @method int AppendText(string $content)
 * @method int AppendStream(StreamBuffer $buffer)
 * @method bool CopyTo(string $target)
 * @method bool MoveTo(string $target)
 * @method bool Delete()
 * @method StorageEngine AddFile(FileInfo $fileInfo)
 * @method StorageEngine SwitchEngine(string $engine, array $options = [], bool $inherit = true)
 */
class Engine
{
    protected StorageEngine $engineObject;

    protected string $engine;

    public function __construct()
    {
        $this->engine = config('storage_engine.default');
        $options = config('storage_engine.storage')[$this->engine];
        $this->engineObject = new StorageEngine($this->engine, $options);
    }

    public static function getInstance() : Engine
    {
        return new self();
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->engineObject, $name], $arguments);
    }

    public function toString() : string
    {
        return $this->engine;
    }
}