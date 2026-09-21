<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

$role = Role::first();
$role_id = $role ? $role->id : 1; // fallback to 1 if no roles exist

$user = User::firstOrCreate(
    ['email' => 'admin@admin.com'],
    [
        'name' => 'Admin',
        'password' => Hash::make('password123'),
        'role_id' => $role_id,
        'estado' => 'activo'
    ]
);

// If the user already existed, update password
$user->password = Hash::make('password123');
$user->save();

echo "User created/updated successfully!\n";
echo "Email: admin@admin.com\n";
echo "Password: password123\n";
