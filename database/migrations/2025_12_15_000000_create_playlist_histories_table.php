<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('playlist_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            // social | company | order (same as view_playlist_data.model_type)
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->string('from_status')->nullable();
            $table->string('to_status')->nullable();
            $table->boolean('is_return')->default(false);
            $table->text('reason')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            $table->index(['model_type', 'model_id'], 'playlist_histories_model_index');
            $table->index('user_id', 'playlist_histories_user_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('playlist_histories');
    }
};

