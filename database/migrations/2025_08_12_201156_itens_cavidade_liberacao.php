<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('itens_cavidade_liberacao', function (Blueprint $table) {
            $table->unsignedBigInteger('id'); // FK para liberacao_produtos.id
            $table->unsignedBigInteger('id_item'); // FK para itens_liberacao.id_item
            $table->unsignedBigInteger('id_cavidade'); // FK para cavidades_liberacao.id_cavidade

            $table->string('minimo')->nullable();
            $table->string('maximo')->nullable();

            $table->primary(['id', 'id_item', 'id_cavidade']);

            $table->foreign('id')
                ->references('id')
                ->on('liberacao_produtos')
                ->onDelete('cascade');

            $table->foreign(['id', 'id_item'])
                ->references(['id', 'id_item'])
                ->on('itens_liberacao')
                ->onDelete('cascade');

            $table->foreign(['id', 'id_cavidade'])
                ->references(['id', 'id_cavidade'])
                ->on('cavidades_liberacao')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itens_cavidade_liberacao');
    }
};
