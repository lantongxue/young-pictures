<?php

declare(strict_types=1);

namespace App\Helper;


use Swoole\Exception;

class FileHelper
{
    public static function getFileType(string $file) : string
    {
        $fileTypeDefine = config('file_type');
        if(!is_array($fileTypeDefine))
        {
            throw new Exception('file_type 未配置');
        }
        $fp = fopen($file, 'rb');
        defer(function () use($fp) {
            fclose($fp);
        });
        $size = filesize($file);
        $binary = fread($fp, $size);

        foreach ($fileTypeDefine as $item)
        {
            $binaryBuffer = [];
            for($i = $item['offset'][0]; $i < $item['offset'][1]; $i++)
            {
                $hex = dechex(ord($binary[$i]));
                $binaryBuffer[] = str_pad($hex, 2, '0', STR_PAD_LEFT);
            }
            $flag = self::binaryBuffer2HexString($binaryBuffer);
            $fingerprint = strtoupper($item['fingerprint']);
            if(self::compareHex($flag, $fingerprint))
            {
                return $item['type'];
            }
        }
        return 'unknown';
    }

    /**
     * 将16进制数组转为字符串
     * @param array $buffer
     * @param string $glue
     * @return string
     */
    public static function binaryBuffer2HexString(array $buffer, $glue = ' ') : string
    {
        return strtoupper(implode($glue, $buffer));
    }

    /**
     * 16进制字符串对比. 支持模糊对比
     * 模糊格式：52 49 46 46 ?? ?? ?? ?? 57 45 42 50
     * @param string $hex1 不支持模糊格式
     * @param string $hex2 支持模糊格式
     * @return bool
     */
    public static function compareHex(string $hex1, string $hex2) : bool
    {
        $pattern = str_replace('??', '(.*)', $hex2);
        preg_match(sprintf('/%s/', $pattern), $hex1, $match);
        return !empty($match);
    }

    /**
     * 获取图片分辨率
     * @param string $file
     * @return array
     */
    public static function getImageSize(string $file) : array
    {
        $imageInfo = getimagesize($file);
        return [
            'w' => $imageInfo[0] ?? 0,
            'h' => $imageInfo[1] ?? 0,
        ];
    }

    /**
     * 获取文件md5值
     * @param string $file
     * @return string
     */
    public static function md5(string $file) : string
    {
        return md5_file($file);
    }

    /**
     * 创建目录
     * @param $pathname
     * @param bool $recursive
     * @param int $mode
     * @return bool
     */
    public static function mkdir($pathname, $recursive = true, $mode = 0777) : bool
    {
        if(file_exists($pathname))
        {
            return true;
        }
        return mkdir($pathname, $mode, $recursive);
    }

    /**
     * 对路径进行处理，使其没有多余的符号
     * @param string $path
     * @return string
     */
    public static function realPath(string $path) : string
    {
        $path_arr = explode(DIRECTORY_SEPARATOR, $path);
        $real_path = [];
        foreach ($path_arr as $node)
        {
            if($node !== DIRECTORY_SEPARATOR && $node !== '.')
            {
                $real_path[] = $node;
            }
        }
        return implode(DIRECTORY_SEPARATOR, $real_path);
    }

    /**
     * 将文件复制到指定目录，并生成一个随机名字
     * @param string $source
     * @param string $target_dir
     * @return string
     */
    public static function copyNew(string $source, string $target_dir) : string
    {
        self::mkdir($target_dir);
        $new_name = uniqid('_se_');
        $target = self::realPath($target_dir.'/'.$new_name);

        return copy($source, $target) ? $target : '';
    }
}