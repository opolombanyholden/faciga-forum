<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// database/migrations/2025_01_01_000003_create_meeting_requests_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('meeting_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('receiver_company_id')->constrained('companies')->onDelete('cascade');
            $table->enum('meeting_type', ['btob', 'btog', 'other']);
            $table->text('message')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('meeting_requests');
    }
};
