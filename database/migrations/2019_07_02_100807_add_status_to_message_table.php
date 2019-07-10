<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mc_messages', function (Blueprint $table) {
            //
            $table->smallInteger('status_id')->unsigned()->default(1);
            $table->foreign('status_id')->references('id')->on('mc_msg_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mc_messages', function (Blueprint $table) {
            //
            $table->dropForeign('mc_messages_status_id_foreign');
            $table->dropColumn('status_id');
           
        });
    }
}
