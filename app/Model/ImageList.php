<?php

declare (strict_types=1);

namespace App\Model;

/**
 * @property int $id 
 * @property string $name 
 * @property int $width 
 * @property int $height 
 * @property string $hash 
 * @property string $type 
 * @property string $path 
 * @property string $engine 
 * @property string $create_time
 * @property int $pv 
 * @property int $zan 
 * @property string $tags 
 * @property int $uid 
 * @property string $ip
 * @property string $code
 */
class ImageList extends Model
{
    const CREATED_AT = 'create_time';
    const UPDATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'image_list';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'width' => 'integer', 'height' => 'integer', 'create_time' => 'string', 'pv' => 'integer', 'zan' => 'integer', 'uid' => 'integer'];
}