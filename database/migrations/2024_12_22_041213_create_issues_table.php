<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string(column: 'title', length: 100)->comment(comment: '標題');
            $table->text(column: 'content')->comment(comment: '內容');
            $table->bigInteger(column: 'user_no')->comment(comment: '作者');

            $table->foreign(columns: 'user_no')->references(columns: 'no')->on(table: 'users');
        });

        Schema::create('issues_assignees', function (Blueprint $table) {
            $table->timestamps();
            // $table->foreignId(column: 'issues_id')->comment(comment: '議題編號')->constrained(table: 'issues');
            $table->unsignedBigInteger(column: 'issues_id')->comment(comment: '議題編號');
            $table->bigInteger(column: 'user_no')->comment(comment: '被分派者');
            $table->primary(columns: ['issues_id', 'user_no']);

            $table->foreign(columns: 'issues_id')->references(columns: 'id')->on(table: 'issues');
            $table->foreign(columns: 'user_no')->references(columns: 'no')->on(table: 'users');
        });

        Schema::create('issues_comments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger(column: 'issues_id')->comment(comment: '議題編號');
            $table->bigInteger(column: 'user_no')->comment(comment: '評論者');
            $table->text(column: 'content')->comment(comment: '內容');

            $table->foreign(columns: 'issues_id')->references(columns: 'id')->on(table: 'issues');
            $table->foreign(columns: 'user_no')->references(columns: 'no')->on(table: 'users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issues');
        Schema::dropIfExists('issues_assignee');
        Schema::dropIfExists('issues_comment');
    }
};
