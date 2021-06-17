<?php

use App\Enum\AccountTypes;
use Faker\Provider\ar_JO\Company;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('agency');
            $table->string('number');
            $table->string('digit');
            $table->string('corporate_name')->nullable();
            $table->string('trade_name')->nullable();
            $table->string('corporate_document')->nullable();
            $table->integer('user_id')->unsigned();
            $table->string('type')->default(AccountTypes::Person);
            $table->timestamps();
        });

        Schema::table('accounts', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('accounts');
    }
}
