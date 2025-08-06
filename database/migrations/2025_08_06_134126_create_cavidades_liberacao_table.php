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
        Schema::create('cavidades_liberacao', function (Blueprint $table) {
            $table->unsignedBigInteger('id'); // FK para itens_liberacao.id
            $table->unsignedBigInteger('id_item'); // FK para itens_liberacao.id_item
            $table->unsignedBigInteger('id_cavidade'); // PK da tabela
            $table->string('descricao')->nullable();
            $table->string('minimo')->nullable();
            $table->string('maximo')->nullable();

            $table->primary(['id', 'id_item', 'id_cavidade']);

            $table->foreign(['id', 'id_item'])->references(['id', 'id_item'])->on('itens_liberacao')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cavidades_liberacao');
    }
};
