<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('SchoolCare');
    }

    public function test_admin_can_login_and_is_redirected_to_admin_dashboard(): void
    {
        $admin = User::create([
            'name' => 'Admin Sarpras',
            'email' => 'admin@schoolcare.sch.id',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        $response = $this->post('/login', [
            'login' => 'admin@schoolcare.sch.id',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($admin);
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_student_can_login_and_is_redirected_to_siswa_dashboard(): void
    {
        $student = User::create([
            'name' => 'Siswa Test',
            'email' => 'siswa@schoolcare.sch.id',
            'nisn' => '1234567890',
            'kelas' => 'XII RPL 1',
            'role' => 'siswa',
            'password' => Hash::make('password'),
        ]);

        $response = $this->post('/login', [
            'login' => '1234567890', // Test login via NISN
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($student);
        $response->assertRedirect(route('siswa.dashboard'));
    }

    public function test_student_cannot_access_admin_dashboard(): void
    {
        $student = User::create([
            'name' => 'Siswa Test',
            'email' => 'siswa@schoolcare.sch.id',
            'nisn' => '1234567890',
            'kelas' => 'XII RPL 1',
            'role' => 'siswa',
            'password' => Hash::make('password'),
        ]);

        $response = $this->actingAs($student)->get('/admin/dashboard');

        // Should be redirected to student dashboard with error flash message
        $response->assertRedirect(route('siswa.dashboard'));
        $response->assertSessionHas('error');
    }

    public function test_student_can_register_new_account(): void
    {
        $response = $this->post('/register', [
            'name' => 'Bambang Siswa',
            'nisn' => '20249999',
            'kelas' => 'X RPL 1',
            'email' => 'bambang@sekolah.sch.id',
            'phone' => '081299998888',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'bambang@sekolah.sch.id',
            'nisn' => '20249999',
            'role' => 'siswa',
        ]);
        $response->assertRedirect(route('siswa.dashboard'));
    }

    public function test_user_can_logout(): void
    {
        $user = User::create([
            'name' => 'User Test',
            'email' => 'test@schoolcare.sch.id',
            'role' => 'siswa',
            'password' => Hash::make('password'),
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect(route('login'));
    }
}

