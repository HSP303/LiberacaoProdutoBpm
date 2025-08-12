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
            $table->unsignedBigInteger('id'); // FK para liberacao_produtos.id
            $table->unsignedBigInteger('id_cavidade'); // PK da tabela
            $table->string('descricao')->nullable();

            $table->primary(['id', 'id_cavidade']);

            $table->foreign('id')
                ->references('id')
                ->on('liberacao_produtos')
                ->onDelete('cascade');
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
