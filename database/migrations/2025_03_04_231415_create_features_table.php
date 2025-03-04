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
        Schema::create("features", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_feature_id')->foreignIdFor(App\Models\FeatureGroup::class)->nullable()->index();
            $table->string('code', 3)->unique()->index();
            $table->string('name', 20)->unique();
            $table->string('description', 255)->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("features");
    }
};
