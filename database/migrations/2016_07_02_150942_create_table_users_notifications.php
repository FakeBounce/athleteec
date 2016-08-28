<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUsersNotifications extends Migration
{
class CreateTableUsersNotifications extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
       Schema::create('users_notifications', function (Blueprint $table) {
           $table->increments('id');
           $table->bigInteger('user_id');
           $table->bigInteger('userL_id');
           $table->string('notification');
           $table->string('libelle');
           $table->bigInteger('action_id');
           $table->string('action_name');
           $table->boolean('accepter')->default(0);
           $table->boolean('afficher');
           $table->timestamps();
       });
   }

   /**
    * Reverse the migrations.
    *
    * @return void
    */
   public function down()
   {
       Schema::drop('users_notifications');
   }
}
