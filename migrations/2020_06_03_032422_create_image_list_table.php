<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateImageListTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('image_list', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->integer('width')->default(0);
            $table->integer('height')->default(0);
            $table->char('hash', 32)->comment('图片哈希值，这里用MD5算法')->index();
            $table->string('type', 5)->comment('图片类型');
            $table->string('path')->comment('图片路径（根据引擎识别）');
            $table->string('engine', 50)->comment('图片存储引擎（本地、阿里云、腾讯云等）');
            //$table->integer('create_time')->default(0);
            $table->timestamp('create_time')->comment('上传时间');
            $table->integer('pv')->default(0)->comment('访问次数');
            $table->integer('zan')->default(0)->comment('点赞数');
            $table->string('tags')->index()->comment('图片标签');
            $table->integer('uid')->default(-1)->comment('上传用户，保留字段');
            $table->string('ip', 15)->comment('上传者IP地址');
            $table->char('code', 32)->unique()->comment('唯一编码');
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('image_list');
    }
}
