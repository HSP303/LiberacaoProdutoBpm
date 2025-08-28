<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('liberacao_produtos', function (Blueprint $table) {
            $table->string('laudo')->nullable()->after('observacao');
            $table->string('realizador')->nullable()->after('laudo');
            $table->string('analista')->nullable()->after('realizador');
        });
    }

    public function down(): void
    {
        Schema::table('liberacao_produtos', function (Blueprint $table) {
            $table->dropColumn(['laudo', 'realizador', 'analista']);
        });
    }
};
