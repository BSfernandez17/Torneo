<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Nette\Schema\Schema as SchemaSchema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('participantes', function (Blueprint $table) {
            $table->id();
            $table->string('Nombre');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('participantes');
    }
};
