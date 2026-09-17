<?php

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

test('a new account is not an administrator', function () {
    $user = User::factory()->create();

    expect($user->isAdministrator())->toBeFalse();
});

test('a standard user may not manage the shared catalogue', function () {
    $user = User::factory()->create();

    expect(Gate::forUser($user)->allows('manage-shared-catalogue'))->toBeFalse();
});

test('an administrator may manage the shared catalogue', function () {
    $administrator = User::factory()->administrator()->create();

    expect(Gate::forUser($administrator)->allows('manage-shared-catalogue'))->toBeTrue();
});

test('the administrator flag can not be granted through mass assignment', function () {
    $user = User::factory()->create();

    $user->fill(['is_admin' => true]);

    expect($user->isAdministrator())->toBeFalse();
});

test('the seeder creates the administrator from the configured credentials', function () {
    config([
        'alyawma.administrator.name' => 'Hakim',
        'alyawma.administrator.email' => 'admin@example.com',
        'alyawma.administrator.password' => 'a-password-only-the-server-knows',
    ]);

    $this->seed(DatabaseSeeder::class);

    $administrator = User::whereEmail('admin@example.com')->sole();

    expect($administrator->isAdministrator())->toBeTrue()
        ->and($administrator->name)->toBe('Hakim')
        ->and(Hash::check('a-password-only-the-server-knows', $administrator->password))->toBeTrue();
});

test('the seeder creates no administrator when the password is missing', function () {
    config([
        'alyawma.administrator.email' => 'admin@example.com',
        'alyawma.administrator.password' => null,
    ]);

    $this->seed(DatabaseSeeder::class);

    expect(User::whereEmail('admin@example.com')->exists())->toBeFalse();
});

test('seeding twice does not duplicate the administrator', function () {
    config([
        'alyawma.administrator.email' => 'admin@example.com',
        'alyawma.administrator.password' => 'a-password-only-the-server-knows',
    ]);

    $this->seed(DatabaseSeeder::class);
    $this->seed(DatabaseSeeder::class);

    expect(User::whereEmail('admin@example.com')->count())->toBe(1);
});
