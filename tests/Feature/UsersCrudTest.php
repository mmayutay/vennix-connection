<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

function createAdmin(): User {
    return User::factory()->create([
        'email' => 'admin@example.com',
        'is_admin' => true,
        'password' => Hash::make('password'),
    ]);
}

function createRegularUser(): User {
    return User::factory()->create([
        'email' => 'user@example.com',
        'is_admin' => false,
        'password' => Hash::make('password'),
    ]);
}

test('non-admin cannot access users index', function () {
    $user = createRegularUser();

    $this->actingAs($user)
        ->get('/users')
        ->assertForbidden();
});

test('admin can see users index', function () {
    $admin = createAdmin();

    $this->actingAs($admin)
        ->get('/users')
        ->assertOk()
        ->assertSee('Users');
});

test('admin can create a user', function () {
    $admin = createAdmin();

    $response = $this->actingAs($admin)->post('/users', [
        'name' => 'New User',
        'email' => 'new@example.com',
        'password' => 'Password1!',
        'password_confirmation' => 'Password1!',
        'verified' => false,
    ]);

    $response
        ->assertRedirect(route('users.index'));

    $this->assertDatabaseHas('users', [
        'email' => 'new@example.com',
    ]);
});

test('admin can update a user without changing password', function () {
    $admin = createAdmin();
    $user = User::factory()->create([
        'email' => 'update@example.com',
    ]);

    $originalPassword = $user->password;

    $response = $this->actingAs($admin)->put("/users/{$user->id}", [
        'name' => 'Updated Name',
        'email' => 'update@example.com',
        'password' => '',
        'password_confirmation' => '',
        'verified' => true,
    ]);

    $response
        ->assertRedirect(route('users.edit', $user));

    $user->refresh();
    expect($user->name)->toBe('Updated Name');
    expect($user->password)->toBe($originalPassword);
});

test('admin cannot delete themselves', function () {
    $admin = createAdmin();

    $this->actingAs($admin)
        ->delete("/users/{$admin->id}")
        ->assertForbidden();
});

