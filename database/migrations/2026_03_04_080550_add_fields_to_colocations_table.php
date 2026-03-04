<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('colocations', function (Blueprint $table) {
            $table->string('address')->nullable()->after('description');
            $table->integer('max_members')->default(4)->after('address');
            $table->decimal('monthly_rent', 10, 2)->nullable()->after('max_members');
        });
    }

    public function down(): void
    {
        Schema::table('colocations', function (Blueprint $table) {
            $table->dropColumn(['address', 'max_members', 'monthly_rent']);
        });
    }
};
