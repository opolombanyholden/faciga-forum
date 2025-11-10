<?php

// database/migrations/2025_01_01_000001_create_companies_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('country'); // Côte d'Ivoire ou Gabon
            $table->string('sector');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone');
            $table->string('logo')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->boolean('confirmed')->default(false);
            $table->text('motivation')->nullable();
            $table->json('sectors_interest')->nullable(); // Secteurs d'intérêt
            $table->boolean('wants_btob')->default(false);
            $table->boolean('wants_btog')->default(false);
            $table->text('other_meetings')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('companies');
    }
};
