<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('properties_features', function (Blueprint $table) {
            $table->id();
            $table->string('icon');
            $table->string('title');
            $table->text('description');
            $table->timestamps();
        });

        $features = [
            ['icon' => 'flaticon-house', 'title' => 'Our Properties', 'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptates, accusamus.'],
            ['icon' => 'flaticon-building', 'title' => 'Property for Sale', 'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptates, accusamus.'],
            ['icon' => 'flaticon-house-2', 'title' => 'House for Rent', 'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptates, accusamus.'],
            ['icon' => 'flaticon-house-1', 'title' => 'House for Sale', 'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptates, accusamus.'],
        ];

        foreach ($features as $feature) {
            \App\Models\PropertiesFeature::create($feature);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties_features');
    }
};
