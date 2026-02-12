<?php

use App\Models\HelpdeskTicket;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('helpdesk_messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('content');
            $table->foreignIdFor(HelpdeskTicket::class)->constrained('helpdesk_tickets');
            $table->foreignIdFor(User::class)->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('helpdesk_messages');
    }
};
