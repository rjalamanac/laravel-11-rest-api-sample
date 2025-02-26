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
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion');
            $table->timestamps();
        });

        Schema::create('actividades', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion');
            $table->string('horario');
            $table->string('etapa_educativa');
            $table->integer('cuota');
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('alumnos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellidos');
            $table->string('nombre_responsable');
            $table->string('apellido_responsable');
            $table->string('email_responsable');
            $table->string('telefono_responsable', 15);
            $table->timestamps();
        });

        Schema::create('actividad_pertence', function (Blueprint $table) {
            $table->foreignId('actividad_id')->constrained('actividades')->onDelete('cascade');
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
            $table->primary(['actividad_id', 'categoria_id']);
        });

        Schema::create('solicitud_actividades', function (Blueprint $table) {
            $table->id();
            $table->string('estado');
            $table->dateTime('fecha');
            $table->foreignId('actividad_id')->constrained('actividades')->onDelete('cascade');
            $table->foreignId('alumno_id')->constrained('alumnos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud_actividades');
        Schema::dropIfExists('actividad_pertence');
        Schema::dropIfExists('alumnos');
        Schema::dropIfExists('categorias');
        Schema::dropIfExists('actividades');
    }
};
