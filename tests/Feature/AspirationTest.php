<?php

namespace Tests\Feature;

use App\Models\Aspiration;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AspirationTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $student1;
    protected User $student2;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Sarpras',
            'email' => 'admin@schoolcare.sch.id',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        $this->student1 = User::create([
            'name' => 'Ahmad Fauzi',
            'email' => 'ahmad@siswa.schoolcare.sch.id',
            'nisn' => '20241001',
            'kelas' => 'XII RPL 1',
            'role' => 'siswa',
            'password' => Hash::make('password'),
        ]);

        $this->student2 = User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@siswa.schoolcare.sch.id',
            'nisn' => '20241002',
            'kelas' => 'XI TKJ 2',
            'role' => 'siswa',
            'password' => Hash::make('password'),
        ]);

        $this->category = Category::create([
            'name' => 'Fasilitas Kelas',
            'slug' => 'fasilitas-kelas',
            'description' => 'Kerusakan sarana meja, kursi, papan tulis, AC, dll.',
            'icon' => 'bi-door-closed',
            'is_active' => true,
        ]);
    }

    public function test_student_can_create_aspiration_with_photo(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('rusak.jpg');

        $response = $this->actingAs($this->student1)->post(route('siswa.aspirasi.store'), [
            'title' => 'Kipas Angin Kelas XII RPL 1 Berisik dan Berasap',
            'category_id' => $this->category->id,
            'location' => 'Gedung B Lantai 2 R. Kelas XII RPL 1',
            'description' => 'Kipas angin mengeluarkan suara mendecit keras dan tercium bau hangus terbakar.',
            'photo' => $file,
        ]);

        $this->assertDatabaseHas('aspirations', [
            'user_id' => $this->student1->id,
            'category_id' => $this->category->id,
            'title' => 'Kipas Angin Kelas XII RPL 1 Berisik dan Berasap',
            'status' => 'Menunggu',
        ]);

        $aspiration = Aspiration::where('user_id', $this->student1->id)->first();
        $this->assertNotNull($aspiration->ticket_number);
        $this->assertStringStartsWith('SC-', $aspiration->ticket_number);

        // Assert history was logged
        $this->assertDatabaseHas('aspiration_status_histories', [
            'aspiration_id' => $aspiration->id,
            'new_status' => 'Menunggu',
        ]);

        $response->assertRedirect(route('siswa.aspirasi.show', $aspiration->id));
    }

    public function test_student_can_only_view_their_own_aspiration(): void
    {
        $aspiration1 = Aspiration::create([
            'ticket_number' => 'SC-20261112-0001',
            'user_id' => $this->student1->id,
            'category_id' => $this->category->id,
            'title' => 'Pengaduan Milik Siswa 1',
            'location' => 'Ruang 1',
            'description' => 'Deskripsi kerusakan fasilitas sarpras.',
            'status' => 'Menunggu',
        ]);

        // Student 1 can view own aspiration
        $response1 = $this->actingAs($this->student1)->get(route('siswa.aspirasi.show', $aspiration1->id));
        $response1->assertStatus(200);
        $response1->assertSee('Pengaduan Milik Siswa 1');

        // Student 2 CANNOT view student 1's aspiration (403 Forbidden)
        $response2 = $this->actingAs($this->student2)->get(route('siswa.aspirasi.show', $aspiration1->id));
        $response2->assertStatus(403);
    }

    public function test_admin_can_view_all_aspirations_and_filter(): void
    {
        Aspiration::create([
            'ticket_number' => 'SC-20261112-0002',
            'user_id' => $this->student1->id,
            'category_id' => $this->category->id,
            'title' => 'Proyektor Rusak di Kelas',
            'location' => 'Lab Komputer',
            'description' => 'Lampu proyektor mati mendadak.',
            'status' => 'Diproses',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.aspirasi.index', ['status' => 'Diproses']));

        $response->assertStatus(200);
        $response->assertSee('Proyektor Rusak di Kelas');
    }

    public function test_admin_can_update_status_and_add_feedback(): void
    {
        $aspiration = Aspiration::create([
            'ticket_number' => 'SC-20261112-0003',
            'user_id' => $this->student1->id,
            'category_id' => $this->category->id,
            'title' => 'Pintu Kamar Mandi Tidak Bisa Dikunci',
            'location' => 'Toilet Lantai 1',
            'description' => 'Kunci selot pintu kamar mandi nomor 2 lepas.',
            'status' => 'Menunggu',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.aspirasi.update-status', $aspiration->id), [
            'status' => 'Diproses',
            'notes' => 'Petugas tukang kunci sarpras ditugaskan ke lokasi.',
            'feedback' => 'Laporan telah kami terima dan petugas sedang menuju lokasi perbaikan.',
        ]);

        $aspiration->refresh();
        $this->assertEquals('Diproses', $aspiration->status);

        $this->assertDatabaseHas('aspiration_status_histories', [
            'aspiration_id' => $aspiration->id,
            'previous_status' => 'Menunggu',
            'new_status' => 'Diproses',
        ]);

        $this->assertDatabaseHas('feedback', [
            'aspiration_id' => $aspiration->id,
            'user_id' => $this->admin->id,
            'response' => 'Laporan telah kami terima dan petugas sedang menuju lokasi perbaikan.',
        ]);

        $response->assertRedirect(route('admin.aspirasi.show', $aspiration->id));
    }

    public function test_admin_can_delete_aspiration(): void
    {
        $aspiration = Aspiration::create([
            'ticket_number' => 'SC-20261112-0004',
            'user_id' => $this->student1->id,
            'category_id' => $this->category->id,
            'title' => 'Aspirasi Percobaan Dihapus',
            'location' => 'Gudang',
            'description' => 'Akan dihapus oleh admin.',
            'status' => 'Menunggu',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.aspirasi.destroy', $aspiration->id));

        $this->assertDatabaseMissing('aspirations', [
            'id' => $aspiration->id,
        ]);

        $response->assertRedirect(route('admin.aspirasi.index'));
    }
}

