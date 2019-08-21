<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * The table name.
     *
     * @var string
     */
    private $table = 'menus';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('lft')->default(0)->comment('左边索引值');
            $table->unsignedBigInteger('rgt')->default(0)->comment('右边索引值');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('父级菜单 ID');
            $table->string('path', 2048)->default('')->comment('菜单路径');
            $table->string('name', 20)->default('')->comment('菜单名称');
            $table->string('icon', 20)->default('')->comment('菜单图标');
            $table->unsignedTinyInteger('sort', 0)->comment('排序值');
            $table->boolean('new_window')->default(false)->comment('是否新窗口打开');
            $table->boolean('enabled')->default(true)->comment('是否启用');
            $table->timestamps();

            $table->index(['lft', 'rgt', 'parent_id']);
        });

        DB::statement("ALTER TABLE `{$this->table}` COMMENT '菜单表';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}
