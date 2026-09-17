<?php

namespace App\Policies;

use App\Models\Food;
use App\Models\User;

class FoodPolicy
{
    /**
     * Determine whether the user may view the food.
     */
    public function view(User $user, Food $food): bool
    {
        return $this->owns($user, $food);
    }

    /**
     * Determine whether the user may update the food.
     */
    public function update(User $user, Food $food): bool
    {
        return $this->owns($user, $food);
    }

    /**
     * Determine whether the user may delete the food.
     */
    public function delete(User $user, Food $food): bool
    {
        return $this->owns($user, $food);
    }

    /**
     * A personal catalogue is strictly personal. The shared catalogue arrives
     * later and will be governed by its own ability, not by this policy.
     */
    private function owns(User $user, Food $food): bool
    {
        return $food->user_id === $user->id;
    }
}
