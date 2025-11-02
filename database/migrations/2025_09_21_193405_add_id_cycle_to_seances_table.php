<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('seances', function (Blueprint $table) {
            // Ajouter la colonne id_cycle
            $table->unsignedBigInteger('id_cycle')->nullable()->after('id_filiere');
            
            // Ajouter la clé étrangère
            $table->foreign('id_cycle')
                  ->references('id')
                  ->on('cycles')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('seances', function (Blueprint $table) {
            // Supprimer la clé étrangère d'abord
            $table->dropForeign(['id_cycle']);
            
            // Supprimer la colonne
            $table->dropColumn('id_cycle');
        });
    }
};