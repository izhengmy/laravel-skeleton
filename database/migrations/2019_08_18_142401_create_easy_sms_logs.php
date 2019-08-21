<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateEasySmsLogs extends Migration
{
    /**
     * The table name.
     *
     * @var string
     */
    private $table = 'easy_sms_logs';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->uuid('id');
            $table->char('mobile_number', 11)->default('')->comment('手机号码');
            $table->json('message')->comment('数据包');
            $table->json('results')->comment('结果集');
            $table->boolean('successful')->default(true)->comment('是否成功');
            $table->timestamps();

            $table->primary('id');
            $table->index('mobile_number');
        });

        DB::statement("ALTER TABLE `{$this->table}` COMMENT 'Easy Sms 日志表';");
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
