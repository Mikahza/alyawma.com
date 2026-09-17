<?php

namespace App\Enums;

/**
 * The unit a food's nutritional values are expressed against.
 *
 * This is what removes mental arithmetic from logging. A food stored as
 * "31 g of protein per 100 g" lets someone eating 137 g enter `137`, rather
 * than working out that it is 1.37 servings while standing in a kitchen.
 */
enum FoodUnit: string
{
    case Grams = 'g';
    case Millilitres = 'ml';
    case Piece = 'piece';

    /**
     * The reference quantity most foods use for this unit.
     *
     * Weighed and measured foods are labelled per 100; countable ones are
     * described one at a time.
     */
    public function defaultReferenceQuantity(): float
    {
        return match ($this) {
            self::Grams, self::Millilitres => 100.0,
            self::Piece => 1.0,
        };
    }
}
