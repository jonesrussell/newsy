<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;

test('guests cannot access admin user index', function () {
    $response = $this->get(route('dashboard.admin.users.index'));

    $response->assertRedirect(route('login'));
});

test('authenticated users can view admin user index', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard.admin.users.index'));

    $response->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/users/Index')
            ->has('users.data')
        );
});

test('authenticated users can view create user page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard.admin.users.create'));

    $response->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/users/Create')
        );
});

test('authenticated users can create a user', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post(route('dashboard.admin.users.store'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertRedirect(route('dashboard.admin.users.index'));

    $this->assertDatabaseHas('users', [
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);

    $createdUser = User::where('email', 'test@example.com')->first();
    expect(Hash::check('password123', $createdUser->password))->toBeTrue();
});

test('user creation requires valid data', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post(route('dashboard.admin.users.store'), []);

    $response->assertSessionHasErrors(['name', 'email', 'password']);
});

test('user email must be unique', function () {
    $existingUser = User::factory()->create(['email' => 'existing@example.com']);
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post(route('dashboard.admin.users.store'), [
        'name' => 'Test User',
        'email' => 'existing@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertSessionHasErrors(['email']);
});

test('authenticated users can view user details', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $targetUser = User::factory()->create();

    $response = $this->get(route('dashboard.admin.users.show', $targetUser));

    $response->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/users/Show')
            ->where('user.id', $targetUser->id)
        );
});

test('authenticated users can view edit user page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $targetUser = User::factory()->create();

    $response = $this->get(route('dashboard.admin.users.edit', $targetUser));

    $response->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/users/Edit')
            ->where('user.id', $targetUser->id)
        );
});

test('authenticated users can update a user', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $targetUser = User::factory()->create();

    $response = $this->put(route('dashboard.admin.users.update', $targetUser), [
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
    ]);

    $response->assertRedirect(route('dashboard.admin.users.index'));

    $this->assertDatabaseHas('users', [
        'id' => $targetUser->id,
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
    ]);
});

test('authenticated users can update user password', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $targetUser = User::factory()->create();
    $oldPassword = $targetUser->password;

    $response = $this->put(route('dashboard.admin.users.update', $targetUser), [
        'name' => $targetUser->name,
        'email' => $targetUser->email,
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);

    $response->assertRedirect(route('dashboard.admin.users.index'));

    $targetUser->refresh();
    expect($targetUser->password)->not->toBe($oldPassword);
    expect(Hash::check('newpassword123', $targetUser->password))->toBeTrue();
});

test('authenticated users can delete a user', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $targetUser = User::factory()->create();

    $response = $this->delete(route('dashboard.admin.users.destroy', $targetUser));

    $response->assertRedirect(route('dashboard.admin.users.index'));

    $this->assertDatabaseMissing('users', [
        'id' => $targetUser->id,
    ]);
});

test('authenticated users can search users', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    User::factory()->create(['name' => 'John Doe', 'email' => 'john@example.com']);
    User::factory()->create(['name' => 'Jane Smith', 'email' => 'jane@example.com']);

    $response = $this->get(route('dashboard.admin.users.index', ['search' => 'John']));

    $response->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/users/Index')
            ->has('users.data', 1)
            ->where('users.data.0.name', 'John Doe')
        );
});
