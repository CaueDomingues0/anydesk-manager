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
        Schema::table('acessos_anydesk', function (Blueprint $table) {
            $table->boolean('favorito')->default(false)->after('observacoes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('acessos_anydesks', function (Blueprint $table) {
            $table->dropColumn('favorito');
        });
    }
};