<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('scans', function (Blueprint $table) {
        $table->boolean('uses_https')->nullable();
        $table->integer('sql_injections_detected')->default(0);
        $table->integer('open_ports_count')->default(0);
        $table->integer('access_control_issues')->default(0);
        $table->integer('weak_passwords_detected')->default(0);
        $table->boolean('has_logging_enabled')->nullable();
        $table->boolean('ssrf_detected')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scans', function (Blueprint $table) {
            //
        });
    }
};
