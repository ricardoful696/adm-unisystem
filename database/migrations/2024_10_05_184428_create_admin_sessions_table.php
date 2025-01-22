<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminSessionsTable extends Migration
{
    public function up()
    {
        Schema::create('admin_sessions', function (Blueprint $table) {
            $table->bigIncrements('id'); // ID da sessão
            $table->string('user_id'); // ID do administrador
            $table->text('payload'); // Payload da sessão
            $table->integer('last_activity'); // Última atividade
            $table->string('ip_address'); // Endereço IP
            $table->string('user_agent'); // User agent
            $table->timestamps(); // timestamps padrão
        });

        // Se você quiser adicionar uma chave estrangeira para `user_id`
        // Certifique-se de que a tabela admins já existe
        // $table->foreign('user_id')->references('id')->on('admins')->onDelete('cascade');
    }

    public function down()
    {
        Schema::dropIfExists('admin_sessions');
    }
}
