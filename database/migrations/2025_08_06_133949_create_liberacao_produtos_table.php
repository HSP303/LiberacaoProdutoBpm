<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('liberacao_produtos', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('empresa');
            $table->string('produto');
            $table->string('fornecedor');
            $table->date('data_revisao')->nullable();
            $table->string('revisao')->nullable();
            $table->float('qtd_avaliada')->nullable();
            $table->string('lote')->nullable();
            $table->date('data')->nullable();

            $table->string('interferencia_montagem')->nullable();
            $table->string('folga_componentes')->nullable();
            $table->string('teste_pratico')->nullable();
            $table->string('aparencia')->nullable();
            $table->string('tratamentos_especificacoes')->nullable();
            $table->string('teste_queda')->nullable();
            $table->string('teste_vida')->nullable();
            $table->string('outro_cinco')->nullable();
            $table->string('impedir_desmontagem')->nullable();
            $table->string('introducao_funcionamento')->nullable();
            $table->string('giro_livre')->nullable();
            $table->string('funcionamento_valvula')->nullable();
            $table->string('introducao_bocal')->nullable();
            $table->string('retirada_bocal')->nullable();
            $table->string('estanqueidade')->nullable();
            $table->string('altura_requisitos')->nullable();
            $table->string('aparencia_visual')->nullable();
            $table->string('teste_campo')->nullable();
            $table->string('outro_tres')->nullable();

            $table->text('observacao')->nullable();
            $table->string('aprovado_reprovado')->nullable(); // Aprovado / Condicional / Reprovado
            $table->string('usuario_analise')->nullable();
            $table->date('data_analise')->nullable();

            // Campos OK_
            $table->string('ok_interferencia_montagem')->nullable();
            $table->string('ok_folga_componentes')->nullable();
            $table->string('ok_teste_pratico')->nullable();
            $table->string('ok_aparencia')->nullable();
            $table->string('ok_tratamentos_especificacoes')->nullable();
            $table->string('ok_teste_queda')->nullable();
            $table->string('ok_teste_vida')->nullable();
            $table->string('ok_outro_cinco')->nullable();
            $table->string('ok_impedir_desmontagem')->nullable();
            $table->string('ok_introducao_funcionamento')->nullable();
            $table->string('ok_giro_livre')->nullable();
            $table->string('ok_funcionamento_valvula')->nullable();
            $table->string('ok_introducao_bocal')->nullable();
            $table->string('ok_retirada_bocal')->nullable();
            $table->string('ok_estanqueidade')->nullable();
            $table->string('ok_altura_requisitos')->nullable();
            $table->string('ok_aparencia_visual')->nullable();
            $table->string('ok_teste_campo')->nullable();
            $table->string('ok_outro_tres')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('liberacao_produtos');
    }
};
