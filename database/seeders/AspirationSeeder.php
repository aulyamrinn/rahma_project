<?php

namespace Database\Seeders;

use App\Models\Aspiration;
use App\Models\AspirationStatusHistory;
use App\Models\Category;
use App\Models\Feedback;
use App\Models\User;
use Illuminate\Database\Seeder;

class AspirationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $ahmad = User::where('email', 'ahmad@siswa.schoolcare.sch.id')->first();
        $siti = User::where('email', 'siti@siswa.schoolcare.sch.id')->first();
        $budi = User::where('email', 'budi@siswa.schoolcare.sch.id')->first();

        $catKelas = Category::where('name', 'Fasilitas Kelas')->first();
        $catToilet = Category::where('name', 'Toilet')->first();
        $catInternet = Category::where('name', 'Internet')->first();
        $catLapangan = Category::where('name', 'Lapangan')->first();
        $catLainnya = Category::where('name', 'Lainnya')->first();
        $catLab = Category::where('name', 'Laboratorium')->first();

        // 1. Aspiration: Diproses
        if ($ahmad && $catKelas) {
            $asp1 = Aspiration::updateOrCreate(
                ['ticket_number' => 'SC-20261101-1001'],
                [
                    'user_id' => $ahmad->id,
                    'category_id' => $catKelas->id,
                    'title' => 'Proyektor Ruang Kelas XII RPL 1 Tampilan Berkedip dan Mati Sendiri',
                    'location' => 'Gedung B Lantai 2, Ruang Kelas XII RPL 1',
                    'description' => 'Proyektor sering mati mendadak saat jam pelajaran berlangsung dan gambar berkedip warna hijau. Sangat mengganggu jalannya kegiatan belajar mengajar.',
                    'photo' => null,
                    'status' => 'Diproses',
                    'created_at' => now()->subDays(3),
                    'updated_at' => now()->subDay(),
                ]
            );

            AspirationStatusHistory::firstOrCreate(
                [
                    'aspiration_id' => $asp1->id,
                    'new_status' => 'Menunggu',
                ],
                [
                    'user_id' => $ahmad->id,
                    'previous_status' => null,
                    'notes' => 'Pengaduan sarana kelas diajukan oleh siswa.',
                    'created_at' => now()->subDays(3),
                ]
            );

            AspirationStatusHistory::firstOrCreate(
                [
                    'aspiration_id' => $asp1->id,
                    'new_status' => 'Diproses',
                ],
                [
                    'user_id' => $admin?->id,
                    'previous_status' => 'Menunggu',
                    'notes' => 'Laporan diverifikasi. Teknisi sarpras dijadwalkan melakukan pengecekan kabel power dan lampu proyektor.',
                    'created_at' => now()->subDay(),
                ]
            );

            if ($admin) {
                Feedback::firstOrCreate(
                    [
                        'aspiration_id' => $asp1->id,
                        'user_id' => $admin->id,
                    ],
                    [
                        'response' => 'Terima kasih atas laporannya. Teknisi telah dihubungi dan akan melakukan pengecekan fisik proyektor siang ini.',
                        'created_at' => now()->subDay(),
                    ]
                );
            }
        }

        // 2. Aspiration: Selesai
        if ($ahmad && $catToilet) {
            $asp2 = Aspiration::updateOrCreate(
                ['ticket_number' => 'SC-20261102-1002'],
                [
                    'user_id' => $ahmad->id,
                    'category_id' => $catToilet->id,
                    'title' => 'Kran Air Wastafel Toilet Pria Rusak dan Terus Mengalir',
                    'location' => 'Toilet Siswa Pria Lantai 1 Dekat Kantin',
                    'description' => 'Gagang kran air wastafel patah sehingga air bersih terbuang terus menerus. Mohon segera diperbaiki agar tidak memboroskan air.',
                    'photo' => null,
                    'status' => 'Selesai',
                    'created_at' => now()->subDays(5),
                    'updated_at' => now()->subDays(2),
                ]
            );

            AspirationStatusHistory::firstOrCreate(
                [
                    'aspiration_id' => $asp2->id,
                    'new_status' => 'Menunggu',
                ],
                [
                    'user_id' => $ahmad->id,
                    'previous_status' => null,
                    'notes' => 'Pengaduan fasilitas sanitasi toilet diajukan.',
                    'created_at' => now()->subDays(5),
                ]
            );

            AspirationStatusHistory::firstOrCreate(
                [
                    'aspiration_id' => $asp2->id,
                    'new_status' => 'Diproses',
                ],
                [
                    'user_id' => $admin?->id,
                    'previous_status' => 'Menunggu',
                    'notes' => 'Petugas perbaikan toilet membeli kran air pengganti.',
                    'created_at' => now()->subDays(4),
                ]
            );

            AspirationStatusHistory::firstOrCreate(
                [
                    'aspiration_id' => $asp2->id,
                    'new_status' => 'Selesai',
                ],
                [
                    'user_id' => $admin?->id,
                    'previous_status' => 'Diproses',
                    'notes' => 'Kran wastafel baru telah dipasang dan aliran air telah normal.',
                    'created_at' => now()->subDays(2),
                ]
            );

            if ($admin) {
                Feedback::firstOrCreate(
                    [
                        'aspiration_id' => $asp2->id,
                        'user_id' => $admin->id,
                    ],
                    [
                        'response' => 'Perbaikan kran wastafel telah selesai dilakukan oleh tim kebersihan & sarpras. Terima kasih atas kepedulian Anda terhadap sarana sekolah.',
                        'created_at' => now()->subDays(2),
                    ]
                );
            }
        }

        // 3. Aspiration: Menunggu
        if ($siti && $catInternet) {
            $asp3 = Aspiration::updateOrCreate(
                ['ticket_number' => 'SC-20261105-1003'],
                [
                    'user_id' => $siti->id,
                    'category_id' => $catInternet->id,
                    'title' => 'Wi-Fi Perpustakaan Tidak Terkoneksi ke Internet',
                    'location' => 'Perpustakaan Sekolah Lantai 1 (Area Sudut Baca)',
                    'description' => 'Sinyal Wi-Fi "HOTSPOT-PERPUS" muncul tetapi berstatus No Internet Access sejak pagi. Siswa kesulitan mencari materi literasi online.',
                    'photo' => null,
                    'status' => 'Menunggu',
                    'created_at' => now()->subHours(6),
                    'updated_at' => now()->subHours(6),
                ]
            );

            AspirationStatusHistory::firstOrCreate(
                [
                    'aspiration_id' => $asp3->id,
                    'new_status' => 'Menunggu',
                ],
                [
                    'user_id' => $siti->id,
                    'previous_status' => null,
                    'notes' => 'Pengaduan jaringan internet perpustakaan diajukan.',
                    'created_at' => now()->subHours(6),
                ]
            );
        }

        // 4. Aspiration: Diproses
        if ($budi && $catLapangan) {
            $asp4 = Aspiration::updateOrCreate(
                ['ticket_number' => 'SC-20261106-1004'],
                [
                    'user_id' => $budi->id,
                    'category_id' => $catLapangan->id,
                    'title' => 'Papan Akrilik Ring Basket Timur Retak dan Goyang',
                    'location' => 'Lapangan Basket Outdoor Bagian Timur',
                    'description' => 'Papan pantul ring basket retak cukup lebar dan baut penyangga tiang mulai goyang. Berbahaya jika mengenai siswa saat berolahraga.',
                    'photo' => null,
                    'status' => 'Diproses',
                    'created_at' => now()->subDays(2),
                    'updated_at' => now()->subHours(12),
                ]
            );

            AspirationStatusHistory::firstOrCreate(
                [
                    'aspiration_id' => $asp4->id,
                    'new_status' => 'Menunggu',
                ],
                [
                    'user_id' => $budi->id,
                    'previous_status' => null,
                    'notes' => 'Pengaduan fasilitas sarana olahraga diajukan.',
                    'created_at' => now()->subDays(2),
                ]
            );

            AspirationStatusHistory::firstOrCreate(
                [
                    'aspiration_id' => $asp4->id,
                    'new_status' => 'Diproses',
                ],
                [
                    'user_id' => $admin?->id,
                    'previous_status' => 'Menunggu',
                    'notes' => 'Area telah diberi garis pembatas kuning dan pemesanan papan pengganti sedang diproses ke pihak sekolah.',
                    'created_at' => now()->subHours(12),
                ]
            );

            if ($admin) {
                Feedback::firstOrCreate(
                    [
                        'aspiration_id' => $asp4->id,
                        'user_id' => $admin->id,
                    ],
                    [
                        'response' => 'Terima kasih atas laporannya. Pihak sarpras sudah memberi pembatas pengaman di area ring basket timur. Penggantian sedang dalam pengadaan barang.',
                        'created_at' => now()->subHours(12),
                    ]
                );
            }
        }

        // 5. Aspiration: Ditolak
        if ($siti && $catLainnya) {
            $asp5 = Aspiration::updateOrCreate(
                ['ticket_number' => 'SC-20261107-1005'],
                [
                    'user_id' => $siti->id,
                    'category_id' => $catLainnya->id,
                    'title' => 'Usulan Pemasangan Speaker Aktif Tambahan untuk Musik di Kantin',
                    'location' => 'Area Kantin Sehat Sekolah',
                    'description' => 'Mengusulkan pengadaan speaker aktif bluetooth di kantin agar suasana jam istirahat lebih ramai dan ada hiburan musik.',
                    'photo' => null,
                    'status' => 'Ditolak',
                    'created_at' => now()->subDays(4),
                    'updated_at' => now()->subDays(3),
                ]
            );

            AspirationStatusHistory::firstOrCreate(
                [
                    'aspiration_id' => $asp5->id,
                    'new_status' => 'Menunggu',
                ],
                [
                    'user_id' => $siti->id,
                    'previous_status' => null,
                    'notes' => 'Aspirasi sarana diajukan oleh siswa.',
                    'created_at' => now()->subDays(4),
                ]
            );

            AspirationStatusHistory::firstOrCreate(
                [
                    'aspiration_id' => $asp5->id,
                    'new_status' => 'Ditolak',
                ],
                [
                    'user_id' => $admin?->id,
                    'previous_status' => 'Menunggu',
                    'notes' => 'Usulan tidak sesuai dengan fokus prioritas perbaikan sarana belajar sekolah.',
                    'created_at' => now()->subDays(3),
                ]
            );

            if ($admin) {
                Feedback::firstOrCreate(
                    [
                        'aspiration_id' => $asp5->id,
                        'user_id' => $admin->id,
                    ],
                    [
                        'response' => 'Terima kasih atas aspirasi yang disampaikan. Namun fasilitas musik di kantin tidak termasuk dalam lingkup program perbaikan dan pemeliharaan sarana prasarana sekolah demi menjaga ketertiban lingkungan.',
                        'created_at' => now()->subDays(3),
                    ]
                );
            }
        }
    }
}

