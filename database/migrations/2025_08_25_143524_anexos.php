<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anexos_liberacao', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary(); // mesmo ID da liberacao_produto
            $table->integer('id_anx')->nullable();
            $table->string('nome_arquivo');
            $table->binary('arquivo');
            $table->timestamps();

            $table->foreign('id')
                  ->references('id')
                  ->on('liberacao_produtos')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anexos_liberacao');
    }
};
