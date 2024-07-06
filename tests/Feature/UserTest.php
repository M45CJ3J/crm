<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Hash;
use Spatie\Permission\Models\Role;
class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    use RefreshDatabase, WithoutMiddleware;

    /** @test */
    public function it_can_create_a_user_with_roles()
    {
        // Create a role
        $role = Role::create(['name' => 'admin']);

        // Create a user and assign a role
        $response = $this->post('/users', [
             'fname'=>'Test',
            'lname'=>'User',
            'username'=>'User',
            'email' => 'testuser2@example.com',
            'password' => Hash::make('password'),
            'active'=>0,
        ]);

        $response->assertStatus(302); // Check for redirect status

        $user = User::where('email', 'testuser@example.com')->first();

        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('admin'));
    }

    /** @test */
    public function it_can_read_a_user_and_their_roles()
    {
        $user = User::factory()->create();
        $role = Role::create(['name' => 'editor']);
        $user->assignRole($role);

        $response = $this->get('/users/' . $user->id);

        $response->assertStatus(200);
        $response->assertSee($user->name);
        $response->assertSee($role->name);
    }

    /** @test */
    public function it_can_update_a_user_and_assign_a_new_role()
    {
        $user = User::factory()->create();
        $oldRole = Role::create(['name' => 'viewer']);
        $newRole = Role::create(['name' => 'admin']);
        $user->assignRole($oldRole);

        $response = $this->put('/users/' . $user->id, [
            'fname'=>'Test',
            'lname'=>'User',
            'username'=>'User',
            'email' => 'testuser2@example.com',
            'password' => Hash::make('password'),
            'active'=>0,
        ]);

        $response->assertStatus(302); // Check for redirect status

        $user->refresh();
        $this->assertTrue($user->hasRole('admin'));
        $this->assertFalse($user->hasRole('viewer'));
    }

    /** @test */
    public function it_can_delete_a_user()
    {
        $user = User::factory()->create(
           [ 'fname'=>'Test',
            'lname'=>'User',
            'username'=>'User',
            'email' => 'testuser2@example.com',
            'password' => Hash::make('password'),
            'active'=>0]
        );
        $response = $this->delete('/users/' . $user->id);

        $response->assertStatus(302); // Check for redirect status
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

}
