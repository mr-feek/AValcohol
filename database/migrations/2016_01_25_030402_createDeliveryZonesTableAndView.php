<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateDeliveryZonesTableAndView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_zones', function(Blueprint $table) {
		   $table->increments('id');
		   $table->string('name');
		   $table->timestamps();
        });
        
        DB::statement('ALTER TABLE delivery_zones ADD location POLYGON NOT NULL AFTER name;');
        
        $view = <<<SQL
            CREATE VIEW delivery_zones_astext AS 
                SELECT avalcohol.delivery_zones.id AS id,
                    avalcohol.delivery_zones.name AS name,
                    st_astext(avalcohol.delivery_zones.location) AS points,
                    avalcohol.delivery_zones.location AS location,
                    avalcohol.delivery_zones.created_at AS created_at,
                    avalcohol.delivery_zones.updated_at AS updated_at from avalcohol.delivery_zones;
SQL;
        DB::statement($view);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW delivery_zones_astext');
        Schema::drop('delivery_zones');
    }
}
