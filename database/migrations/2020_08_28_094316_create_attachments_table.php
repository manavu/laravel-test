<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->uuid('id');     // 整数じゃなくて UUID にしてみる
            $table->primary('id');  // プライマリーキーを明示する必要がある
            $table->string('name');
            // $table->binary('data');  // mysql だと blob 型になるので大きなものは入らない。中途半端な抽象化な気がする
            $table->longText('data');
            $table->string('content_type');
            $table->unsignedInteger('size');
            $table->bigInteger('post_id')->unsigned();  // 参照するテーブルの列の型と合わせること
            $table->foreign('post_id')->references('id')->on('posts'); // 外部キー参照
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
        Schema::dropIfExists('attachments');
    }
}
