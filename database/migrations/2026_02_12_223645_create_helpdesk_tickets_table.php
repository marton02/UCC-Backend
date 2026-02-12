<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('helpdesk_tickets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(User::class)->constrained('users');
            $table->foreignIdFor(User::class, 'agent_id')->nullable();
            $table->string('subject');
            $table->string('status');
            $table->timestamps();
            $table->unique(['id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('helpdesk_tickets');
    }
};
