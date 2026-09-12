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
    Schema::create('acessos_anydesk', function (Blueprint $table) {
        $table->id();
        $table->string('nome_cliente');
        $table->string('codigo_anydesk');
        $table->string('cidade_orgao')->nullable();
        $table->text('observacoes')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acesso_anydesks');
    }
};
