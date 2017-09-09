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
class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema->create('groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('文件夹名称')->unique();
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
        $this->schema->drop('groups');
    }
}
