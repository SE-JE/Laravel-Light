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
        Schema::create("feature_accesses", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('feature_id')->foreignIdFor(App\Models\Feature::class)->nullable()->index();
            $table->string('code')->unique()->index();
            $table->string('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("feature_accesses");
    }
};
