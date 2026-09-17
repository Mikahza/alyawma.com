<?php

namespace App\Models;

use App\Enums\FoodUnit;
use Database\Factories\FoodFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * A single food or whole meal, with the five nutritional values it provides for
 * one serving. It is never a recipe composed of ingredients: the values are
 * entered by hand, whether the row describes a raw ingredient or a full plate.
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $reference_quantity
 * @property FoodUnit $reference_unit
 * @property string $protein_grams
 * @property int $calories
 * @property string $fibre_grams
 * @property string $fat_grams
 * @property string $carbohydrate_grams
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 */
#[Fillable([
    'name',
    'reference_quantity',
    'reference_unit',
    'protein_grams',
    'calories',
    'fibre_grams',
    'fat_grams',
    'carbohydrate_grams',
])]
class Food extends Model
{
    /** @use HasFactory<FoodFactory> */
    use HasFactory, SoftDeletes;

    /**
     * Laravel's inflector treats "food" as uncountable. This catalogue holds
     * distinct items, so the plural is the honest name.
     */
    protected $table = 'foods';

    /**
     * Get the attributes that should be cast.
     *
     * Grams are cast to a fixed-precision string rather than a float: the value
     * that leaves the database is the value that reaches the browser, with no
     * binary rounding in between.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'reference_quantity' => 'decimal:1',
            'reference_unit' => FoodUnit::class,
            'protein_grams' => 'decimal:1',
            'calories' => 'integer',
            'fibre_grams' => 'decimal:1',
            'fat_grams' => 'decimal:1',
            'carbohydrate_grams' => 'decimal:1',
        ];
    }

    /**
     * The account this food belongs to.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Limit the query to the foods owned by the given user.
     *
     * @param  Builder<Food>  $query
     */
    public function scopeOwnedBy(Builder $query, User $user): void
    {
        $query->where('user_id', $user->id);
    }

    /**
     * Limit the query to the foods whose name matches the given terms.
     *
     * @param  Builder<Food>  $query
     */
    public function scopeMatching(Builder $query, ?string $terms): void
    {
        $terms = trim((string) $terms);

        if ($terms === '') {
            return;
        }

        $query->whereLike('name', '%'.$terms.'%');
    }
}
