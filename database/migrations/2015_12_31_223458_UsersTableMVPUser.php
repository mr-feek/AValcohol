<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersTableMVPUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		/**
		 * For the beta, users are not required to create an account. This means we will have null fields for first name, last name,
		 * and phone number. MVP_USER being true will help us filter out this lost data in the future
		 */
        Schema::table('users', function(Blueprint $table) {
			$table->boolean('mvp_user')->after('phone_number');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('users', function(Blueprint $table) {
			$table->dropColumn('mvp_user');
		});
    }
}
