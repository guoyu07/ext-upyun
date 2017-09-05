<?php
/**
 * This file is part of Notadd.
 *
 * @datetime 2017-07-10 18:16:51
 */

use Notadd\Foundation\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateCloudsTable.
 */
class CreateExtCloudDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema->create('ext_cloud_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('path')->comment('文件名称')->unique();
            $table->string('ori_type')->comment('原始文件类型');
            $table->string('ori_filename')->comment('原始文件名');
            $table->string('type')->comment('文件类型')->nullable();
            $table->string('video_thumb')->comment('视频截图')->nullable();
            $table->string('width')->comment('width宽度')->nullable();
            $table->string('height')->comment('height高度')->nullable();
            $table->string('size')->comment('文件大小');
            $table->string('folder')->comment('又拍云文件夹');
            $table->string('bucket')->comment('又拍云服务名称');
            $table->boolean('is_token')->comment('是否是防盗链空间');
            $table->integer('user_id')->comment('用户ID');
            $table->string('module')->comment('模块');
            $table->string('tag')->comment('文件标签');
            $table->string('driver')->comment('又拍云or七牛or本地');
            $table->string('task_id')->comment('预处理id')->nullable();
            $table->string('md5')->comment('文件MD5值');
            $table->string('sha1')->comment('文件SHA1值')->nullable();
            $table->string('sha256')->comment('文件SHA256值')->nullable();
            $table->string('hash')->comment('文件hash值')->unique()->nullable();
//            $table->string('extend_type')->nullable();
//            $table->string('extend_value')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema->drop('ext_cloud_details');
    }
}
