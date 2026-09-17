<?php

use App\Enums\FoodUnit;
use App\Models\Food;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('guests are redirected to the login screen', function () {
    $this->get(route('foods.index'))->assertRedirect(route('login'));
});

test('a user sees their own foods', function () {
    $user = User::factory()->create();
    Food::factory()->ownedBy($user)->create(['name' => 'Chicken breast']);

    $this->actingAs($user)
        ->get(route('foods.index'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('foods/Index')
            ->has('foods', 1)
            ->where('foods.0.name', 'Chicken breast'),
        );
});

test('a user never sees the foods of another account', function () {
    $user = User::factory()->create();
    $stranger = User::factory()->create();
    Food::factory()->ownedBy($stranger)->create(['name' => 'Not mine']);

    $this->actingAs($user)
        ->get(route('foods.index'))
        ->assertInertia(fn (Assert $page) => $page->has('foods', 0));
});

test('searching narrows the list to matching names', function () {
    $user = User::factory()->create();
    Food::factory()->ownedBy($user)->create(['name' => 'Greek yoghurt']);
    Food::factory()->ownedBy($user)->create(['name' => 'Chicken breast']);

    $this->actingAs($user)
        ->get(route('foods.index', ['search' => 'yogh']))
        ->assertInertia(fn (Assert $page) => $page
            ->has('foods', 1)
            ->where('foods.0.name', 'Greek yoghurt')
            ->where('search', 'yogh'),
        );
});

test('a user can add a food, and its decimals survive the round trip', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('foods.store'), [
            'name' => 'My usual breakfast',
            'reference_quantity' => '1',
            'reference_unit' => 'piece',
            'protein_grams' => '32.5',
            'calories' => 540,
            'fibre_grams' => '7.1',
            'fat_grams' => '12.4',
            'carbohydrate_grams' => '61.8',
        ])
        ->assertRedirect(route('foods.index'));

    $food = $user->foods()->sole();

    expect($food->name)->toBe('My usual breakfast')
        ->and($food->reference_quantity)->toBe('1.0')
        ->and($food->reference_unit)->toBe(FoodUnit::Piece)
        ->and($food->protein_grams)->toBe('32.5')
        ->and($food->calories)->toBe(540)
        ->and($food->fibre_grams)->toBe('7.1')
        ->and($food->fat_grams)->toBe('12.4')
        ->and($food->carbohydrate_grams)->toBe('61.8');
});

test('negative and non numeric values are refused', function (string $field, mixed $value) {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('foods.create'))
        ->post(route('foods.store'), [
            'name' => 'Anything',
            'reference_quantity' => '100',
            'reference_unit' => 'g',
            'protein_grams' => '10',
            'calories' => 100,
            'fibre_grams' => '1',
            'fat_grams' => '1',
            'carbohydrate_grams' => '1',
            $field => $value,
        ])
        ->assertSessionHasErrors($field);

    expect(Food::count())->toBe(0);
})->with([
    'negative protein' => ['protein_grams', '-1'],
    'text in calories' => ['calories', 'a lot'],
    'text in fibre' => ['fibre_grams', 'some'],
    'two decimals' => ['fat_grams', '12.34'],
    'missing name' => ['name', ''],
    'unknown unit' => ['reference_unit', 'spoon'],
    'zero reference quantity' => ['reference_quantity', '0'],
]);

test('the two informing values are optional and default to zero', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('foods.store'), [
            'name' => 'Only what I know',
            'reference_quantity' => '100',
            'reference_unit' => 'g',
            'protein_grams' => '31',
            'calories' => 165,
            'fibre_grams' => '0',
        ])
        ->assertRedirect(route('foods.index'));

    $food = $user->foods()->sole();

    expect($food->fat_grams)->toBe('0.0')
        ->and($food->carbohydrate_grams)->toBe('0.0');
});

test('the same name can not be used twice in one catalogue', function () {
    $user = User::factory()->create();
    Food::factory()->ownedBy($user)->create(['name' => 'Chicken breast']);

    $this->actingAs($user)
        ->from(route('foods.create'))
        ->post(route('foods.store'), [
            'name' => 'Chicken breast',
            'reference_quantity' => '100',
            'reference_unit' => 'g',
            'protein_grams' => '31',
            'calories' => 165,
            'fibre_grams' => '0',
        ])
        ->assertSessionHasErrors('name');

    expect($user->foods()->count())->toBe(1);
});

test('a name freed by a deletion can be used again', function () {
    $user = User::factory()->create();
    $food = Food::factory()->ownedBy($user)->create(['name' => 'Chicken breast']);
    $food->delete();

    $this->actingAs($user)
        ->post(route('foods.store'), [
            'name' => 'Chicken breast',
            'reference_quantity' => '100',
            'reference_unit' => 'g',
            'protein_grams' => '31',
            'calories' => 165,
            'fibre_grams' => '0',
        ])
        ->assertSessionHasNoErrors();

    expect($user->foods()->count())->toBe(1);
});

test('two accounts may each hold a food with the same name', function () {
    $user = User::factory()->create();
    Food::factory()->create(['name' => 'Chicken breast']);

    $this->actingAs($user)
        ->post(route('foods.store'), [
            'name' => 'Chicken breast',
            'reference_quantity' => '100',
            'reference_unit' => 'g',
            'protein_grams' => '31',
            'calories' => 165,
            'fibre_grams' => '0',
        ])
        ->assertSessionHasNoErrors();

    expect($user->foods()->count())->toBe(1);
});

test('a user can update their own food', function () {
    $user = User::factory()->create();
    $food = Food::factory()->ownedBy($user)->create(['name' => 'Old name']);

    $this->actingAs($user)
        ->put(route('foods.update', $food), [
            'name' => 'New name',
            'reference_quantity' => '100',
            'reference_unit' => 'g',
            'protein_grams' => '20.0',
            'calories' => 200,
            'fibre_grams' => '2.0',
            'fat_grams' => '3.0',
            'carbohydrate_grams' => '4.0',
        ])
        ->assertRedirect(route('foods.index'));

    expect($food->refresh()->name)->toBe('New name');
});

test('a user can not reach the foods of another account', function (string $route, string $method) {
    $user = User::factory()->create();
    $food = Food::factory()->create();

    $this->actingAs($user)
        ->call($method, route($route, $food))
        ->assertForbidden();
})->with([
    'edit' => ['foods.edit', 'GET'],
    'update' => ['foods.update', 'PUT'],
    'delete' => ['foods.destroy', 'DELETE'],
]);

test('deleting a food is reversible', function () {
    $user = User::factory()->create();
    $food = Food::factory()->ownedBy($user)->create();

    $this->actingAs($user)
        ->delete(route('foods.destroy', $food))
        ->assertRedirect(route('foods.index'));

    expect(Food::count())->toBe(0)
        ->and(Food::withTrashed()->count())->toBe(1);
});
