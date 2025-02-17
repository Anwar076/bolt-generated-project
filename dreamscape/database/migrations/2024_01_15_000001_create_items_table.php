<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('type'); // Weapon, Armor, Accessory, etc.
            $table->integer('rarity'); // 0-100
            $table->integer('power');   // 0-100
            $table->integer('speed');   // 0-100
            $table->integer('durability'); // 0-100
            $table->text('magical_properties')->nullable(); // JSON or string
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
