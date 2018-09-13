<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserteamTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //create Trigger 
         DB::unprepared('
        CREATE TRIGGER tr_totalnumber_inc AFTER INSERT ON `userteam` FOR EACH ROW
            BEGIN
                UPDATE teams SET totalMembers=totalMembers+1 WHERE id=NEW.teams_id;
            END
        ');

         DB::unprepared('
        CREATE TRIGGER tr_totalnumber_dec AFTER DELETE ON `userteam` FOR EACH ROW
            BEGIN
                UPDATE teams SET totalMembers=totalMembers-1 WHERE id=OLD.teams_id;
            END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        DB::unprepared('DROP TRIGGER `tr_totalnumber_inc`');
        DB::unprepared('DROP TRIGGER `tr_totalnumber_dec`');
    }

}
