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
        Schema::create('foods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');

            // The values below are expressed for this much of this unit: "per
            // 100 g", "per 1 piece". Typed rather than free text, because a
            // journal entry divides by it — that division is what spares the
            // user from converting 137 g into 1.37 servings by hand.
            $table->decimal('reference_quantity', 6, 1);
            $table->string('reference_unit');

            // Fixed-precision, never floats: totals are summed across dozens of
            // entries a day, and a drift of a tenth shows on a gauge.
            $table->decimal('protein_grams', 6, 1);
            $table->unsignedSmallInteger('calories');
            $table->decimal('fibre_grams', 6, 1);
            $table->decimal('fat_grams', 6, 1)->default(0);
            $table->decimal('carbohydrate_grams', 6, 1)->default(0);

            $table->softDeletes();
            $table->timestamps();

            // Every read is scoped to the owner, and the list is ordered by name.
            $table->index(['user_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foods');
    }
};
