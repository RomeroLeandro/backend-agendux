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
            Schema::table('users', function (Blueprint $table) {
        $table->renameColumn('name', 'first_name');
        $table->string('last_name')->after('first_name');
        $table->string('phone')->nullable()->after('email');
        $table->string('profession')->nullable()->after('phone');
        $table->string('business_name')->nullable()->after('profession');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
