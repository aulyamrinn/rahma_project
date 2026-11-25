<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Fasilitas Kelas',
                'description' => 'Kerusakan sarana meja, kursi, papan tulis, proyektor, AC, kipas angin, dan lampu ruang kelas.',
                'icon' => 'bi-door-closed',
            ],
            [
                'name' => 'Kebersihan',
                'description' => 'Masalah kebersihan lingkungan, tempat sampah rusak/penuh, dan tumpukan sampah.',
                'icon' => 'bi-trash',
            ],
            [
                'name' => 'Toilet',
                'description' => 'Kerusakan kran air, kunci pintu, wastafel bocor, closet, dan saluran pembuangan toilet.',
                'icon' => 'bi-droplet-half',
            ],
            [
                'name' => 'Laboratorium',
                'description' => 'Kendala komputer lab, peralatan praktikum sains/komputer, dan instalasi kelistrikan lab.',
                'icon' => 'bi-pc-display',
            ],
            [
                'name' => 'Perpustakaan',
                'description' => 'Kondisi ruang baca perpustakaan, rak buku, pendingin ruangan, atau sarana literasi.',
                'icon' => 'bi-book',
            ],
            [
                'name' => 'Lapangan',
                'description' => 'Kerusakan lapangan olahraga, tiang gawang futsal, ring basket, net voli, dan fasilitas outdoor.',
                'icon' => 'bi-dribbble',
            ],
            [
                'name' => 'Internet',
                'description' => 'Akses jaringan Wi-Fi sekolah lambat, hotspot tidak dapat diakses, atau kabel LAN terputus.',
                'icon' => 'bi-wifi',
            ],
            [
                'name' => 'Lainnya',
                'description' => 'Pengaduan sarana dan prasarana umum sekolah lainnya yang belum tercakup di kategori utama.',
                'icon' => 'bi-grid',
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['name' => $cat['name']],
                [
                    'slug' => Str::slug($cat['name']),
                    'description' => $cat['description'],
                    'icon' => $cat['icon'],
                    'is_active' => true,
                ]
            );
        }
    }
}

