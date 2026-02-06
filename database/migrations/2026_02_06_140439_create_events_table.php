<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->dateTime('occurrence');
            $table->text('description')->nullable();
            $table->foreignIdFor(User::class)->constrained('users');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
