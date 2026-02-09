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
        Schema::table('playlist_histories', function (Blueprint $table) {
            $table->string('action_type')->default('status_change')->after('model_id'); // 'status_change' or 'assignment'
            $table->unsignedBigInteger('assigned_to_user_id')->nullable()->after('user_id'); // Who was assigned
            $table->string('assignment_type')->nullable()->after('assigned_to_user_id'); // 'designer', 'manufacturer', 'preparer', 'shipmenter'
            
            $table->index('action_type', 'playlist_histories_action_type_index');
            $table->index('assigned_to_user_id', 'playlist_histories_assigned_to_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('playlist_histories', function (Blueprint $table) {
            $table->dropIndex('playlist_histories_action_type_index');
            $table->dropIndex('playlist_histories_assigned_to_index');
            $table->dropColumn(['action_type', 'assigned_to_user_id', 'assignment_type']);
        });
    }
};
