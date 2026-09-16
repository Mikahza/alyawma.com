<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * This runs on every deployment, so it must be idempotent and must never
     * create an account whose credentials can be guessed from the repository.
     */
    public function run(): void
    {
        $this->seedAdministrator();

        if (app()->isProduction() || User::whereEmail('test@example.com')->exists()) {
            return;
        }

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }

    /**
     * Create or update the single administrator account.
     *
     * Both the address and the password come from the environment. When either
     * is missing, no administrator is created at all — failing to seed is far
     * better than seeding a default nobody changed.
     */
    private function seedAdministrator(): void
    {
        $email = config('alyawma.administrator.email');
        $password = config('alyawma.administrator.password');

        if (blank($email) || blank($password)) {
            return;
        }

        $administrator = User::firstOrNew(['email' => $email]);

        // Assigned directly rather than through mass assignment: `is_admin` is
        // not fillable, and the `hashed` cast takes care of the password.
        $administrator->name = config('alyawma.administrator.name');
        $administrator->password = $password;
        $administrator->is_admin = true;
        $administrator->save();
    }
}
