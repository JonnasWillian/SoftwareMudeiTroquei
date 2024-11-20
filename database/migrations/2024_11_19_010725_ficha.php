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
        //
        Schema::create('fichas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('telefone');
            $table->string('email');
            $table->string('produto');
            $table->string('desmontagem');
            $table->string('marca');
            $table->string('dtCompra');
            $table->decimal('valor', 12, 2);
            $table->decimal('valorEstimado', 12, 2);
            $table->string('marcaUso');
            $table->string('descricao');
            $table->decimal('altura', 12, 2);
            $table->decimal('largura', 12, 2);
            $table->decimal('profundidade', 12, 2);
            $table->string('foto1');
            $table->string('foto2');
            $table->string('foto3');
            $table->string('status');
            $table->string('urgente');
            $table->decimal('quantidade', 12, 2);
            $table->string('outroBairro')->nullable();
            $table->string('voltagem');
            // Flags
            $table->string('bairro');
            $table->string('tipoEstado')->nullable();
            $table->boolean('novo')->default(false);
            $table->boolean('usado')->default(false);
            $table->string('troca')->nullable();
            $table->string('nf')->nullable();
            $table->string('sujo')->nullable();
            $table->boolean('mofo')->default(false);
            $table->boolean('cupim')->default(false);
            $table->boolean('trincado')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
