<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $student;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@schoolcare.sch.id',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        $this->student = User::create([
            'name' => 'Siswa Pertama',
            'email' => 'siswa1@schoolcare.sch.id',
            'role' => 'siswa',
            'nisn' => '20241001',
            'kelas' => 'XII RPL 1',
            'password' => Hash::make('password'),
        ]);
    }

    public function test_admin_can_view_and_filter_users(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.pengguna.index'));
        $response->assertStatus(200);
        $response->assertSee('Admin Utama');
        $response->assertSee('Siswa Pertama');

        $filtered = $this->actingAs($this->admin)->get(route('admin.pengguna.index', ['role' => 'admin']));
        $filtered->assertStatus(200);
        $filtered->assertSee('Admin Utama');
        $filtered->assertDontSee('Siswa Pertama');
    }

    public function test_student_cannot_access_user_management(): void
    {
        $response = $this->actingAs($this->student)->get(route('admin.pengguna.index'));
        $response->assertRedirect(route('siswa.dashboard'));
        $response->assertSessionHas('error');
    }

    public function test_admin_can_create_new_admin_and_siswa(): void
    {
        $adminResponse = $this->actingAs($this->admin)->post(route('admin.pengguna.store'), [
            'name' => 'Admin Kedua',
            'email' => 'admin2@schoolcare.sch.id',
            'role' => 'admin',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $adminResponse->assertRedirect(route('admin.pengguna.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'admin2@schoolcare.sch.id',
            'role' => 'admin',
            'nisn' => null,
            'kelas' => null,
        ]);

        $siswaResponse = $this->actingAs($this->admin)->post(route('admin.pengguna.store'), [
            'name' => 'Siswa Baru',
            'email' => 'siswabaru@schoolcare.sch.id',
            'role' => 'siswa',
            'nisn' => '20249991',
            'kelas' => 'XI TKJ 1',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $siswaResponse->assertRedirect(route('admin.pengguna.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'siswabaru@schoolcare.sch.id',
            'role' => 'siswa',
            'nisn' => '20249991',
            'kelas' => 'XI TKJ 1',
        ]);
    }

    public function test_admin_can_update_user_without_changing_password_and_shows_updated_timestamp(): void
    {
        $originalPasswordHash = $this->student->password;

        $response = $this->actingAs($this->admin)->put(route('admin.pengguna.update', $this->student->id), [
            'name' => 'Siswa Pertama Diubah',
            'email' => 'siswa1@schoolcare.sch.id',
            'role' => 'siswa',
            'nisn' => '20241001',
            'kelas' => 'XII RPL 2',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertRedirect(route('admin.pengguna.index'));
        $this->student->refresh();

        $this->assertEquals('Siswa Pertama Diubah', $this->student->name);
        $this->assertEquals('XII RPL 2', $this->student->kelas);
        $this->assertEquals($originalPasswordHash, $this->student->password);

        $editPage = $this->actingAs($this->admin)->get(route('admin.pengguna.edit', $this->student->id));
        $editPage->assertStatus(200);
        $editPage->assertSee('Terakhir Diperbarui:');
    }

    public function test_admin_can_delete_other_user_but_cannot_delete_self(): void
    {
        $selfDelete = $this->actingAs($this->admin)->delete(route('admin.pengguna.destroy', $this->admin->id));
        $selfDelete->assertRedirect(route('admin.pengguna.index'));
        $selfDelete->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);

        $otherDelete = $this->actingAs($this->admin)->delete(route('admin.pengguna.destroy', $this->student->id));
        $otherDelete->assertRedirect(route('admin.pengguna.index'));
        $otherDelete->assertSessionHas('success');
        $this->assertDatabaseMissing('users', ['id' => $this->student->id]);
    }
}
