<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anexos_liberacao', function (Blueprint $table) {
        $table->unsignedBigInteger('id');      // mesmo ID da liberacao_produto
        $table->integer('id_anx');             // agora faz parte da PK
        $table->string('nome_arquivo');
        $table->binary('arquivo');
        $table->timestamps();

        // chave primária composta
        $table->primary(['id', 'id_anx']);

        // relação com liberacao_produtos
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
