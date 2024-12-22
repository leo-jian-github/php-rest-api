<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigInteger(column: 'no')->autoIncrement()->primary()->comment(comment: '用戶編號');
            $table->string(column: 'account', length: 20)->unique()->comment(comment: '帳號');
            $table->string(column: 'password', length: 32)->comment(comment: '密碼(32位元 MD5)');
            $table->string(column: 'name', length: 100)->comment(comment: '暱稱');
            $table->timestamps();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('user_tokens', function (Blueprint $table) {
            $table->string(column: 'token', length: 32)->comment(comment: 'API 交互令牌');
            $table->bigInteger(column: 'user_no')->comment(comment: '用戶編號');
            $table->timestamps();
            $table->primary(['token', 'user_no']);

            $table->foreign('user_no')->references('no')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('user_token');
    }
};
