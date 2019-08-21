<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username', 20)->default('')->comment('用户名');
            $table->char('mobile_number', 11)->default('')->comment('手机号码');
            $table->string('password')->comment('密码');
            $table->string('real_name', 20)->comment('真实姓名');
            $table->boolean('enabled')->default(true)->comment('是否启用');
            $table->timestamps();
            $table->softDeletes();

            $table->unique('username');
            $table->unique('mobile_number');
        });

        DB::statement("ALTER TABLE `admins` COMMENT '管理员用户表';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
