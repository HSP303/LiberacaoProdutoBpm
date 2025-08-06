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
        Schema::create('itens_liberacao', function (Blueprint $table) {
            $table->unsignedBigInteger('id'); // FK para liberacao_produtos
            $table->unsignedBigInteger('id_item'); // PK composta com id
            $table->string('especificado')->nullable();
            $table->string('equipamento')->nullable();
            $table->string('resultado')->nullable(); // lista: Ok / Não OK

            $table->primary(['id', 'id_item']);

            $table->foreign('id')->references('id')->on('liberacao_produtos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itens_liberacao');
    }
};
