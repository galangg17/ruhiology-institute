<?php

namespace Database\Seeders;

use App\Models\Province;
use App\Models\Regency;
use Illuminate\Database\Seeder;

class IndonesiaRegenciesSeeder extends Seeder
{
    public function run(): void
    {
        $regenciesByProvince = [
            'Aceh' => [
                'Kota Banda Aceh', 'Kota Sabang', 'Kota Lhokseumawe', 'Kota Langsa', 'Kota Subulussalam',
                'Kabupaten Aceh Besar', 'Kabupaten Pidie', 'Kabupaten Pidie Jaya', 'Kabupaten Aceh Utara',
                'Kabupaten Aceh Timur', 'Kabupaten Aceh Selatan', 'Kabupaten Aceh Barat', 'Kabupaten Aceh Barat Daya',
                'Kabupaten Aceh Jaya', 'Kabupaten Aceh Singkil', 'Kabupaten Aceh Tamiang', 'Kabupaten Aceh Tenggara',
                'Kabupaten Aceh Tengah', 'Kabupaten Bener Meriah', 'Kabupaten Gayo Lues', 'Kabupaten Nagan Raya',
                'Kabupaten Simeulue'
            ],
            'Sumatera Utara' => [
                'Kota Medan', 'Kota Pematangsiantar', 'Kota Sibolga', 'Kota Tanjungbalai', 'Kota Binjai',
                'Kota Tebing Tinggi', 'Kota Padangsidimpuan', 'Kota Gunungsitoli', 'Kabupaten Asahan',
                'Kabupaten Batu Bara', 'Kabupaten Dairi', 'Kabupaten Deli Serdang', 'Kabupaten Humbang Hasundutan',
                'Kabupaten Karo', 'Kabupaten Labuhanbatu', 'Kabupaten Labuhanbatu Selatan', 'Kabupaten Labuhanbatu Utara',
                'Kabupaten Langkat', 'Kabupaten Mandailing Natal', 'Kabupaten Nias', 'Kabupaten Nias Barat',
                'Kabupaten Nias Selatan', 'Kabupaten Nias Utara', 'Kabupaten Padang Lawas', 'Kabupaten Padang Lawas Utara',
                'Kabupaten Pakpak Bharat', 'Kabupaten Samosir', 'Kabupaten Serdang Bedagai', 'Kabupaten Simalungun',
                'Kabupaten Tapanuli Selatan', 'Kabupaten Tapanuli Tengah', 'Kabupaten Tapanuli Utara', 'Kabupaten Toba'
            ],
            'Sumatera Barat' => [
                'Kota Padang', 'Kota Bukittinggi', 'Kota Payakumbuh', 'Kota Pariaman', 'Kota Padang Panjang',
                'Kota Solok', 'Kota Sawahlunto', 'Kabupaten Agam', 'Kabupaten Dharmasraya', 'Kabupaten Kepulauan Mentawai',
                'Kabupaten Lima Puluh Kota', 'Kabupaten Padang Pariaman', 'Kabupaten Pasaman', 'Kabupaten Pasaman Barat',
                'Kabupaten Pesisir Selatan', 'Kabupaten Sijunjung', 'Kabupaten Solok', 'Kabupaten Solok Selatan', 'Kabupaten Tanah Datar'
            ],
            'Riau' => [
                'Kota Pekanbaru', 'Kota Dumai', 'Kabupaten Bengkalis', 'Kabupaten Indragiri Hilir',
                'Kabupaten Indragiri Hulu', 'Kabupaten Kampar', 'Kabupaten Kepulauan Meranti', 'Kabupaten Kuantan Singingi',
                'Kabupaten Pelalawan', 'Kabupaten Rokan Hilir', 'Kabupaten Rokan Hulu', 'Kabupaten Siak'
            ],
            'Jambi' => [
                'Kota Jambi', 'Kota Sungai Penuh', 'Kabupaten Kerinci', 'Kabupaten Merangin', 'Kabupaten Sarolangun',
                'Kabupaten Batanghari', 'Kabupaten Muaro Jambi', 'Kabupaten Tanjung Jabung Barat',
                'Kabupaten Tanjung Jabung Timur', 'Kabupaten Bungo', 'Kabupaten Tebo'
            ],
            'Sumatera Selatan' => [
                'Kota Palembang', 'Kota Prabumulih', 'Kota Pagar Alam', 'Kota Lubuklinggau', 'Kabupaten Banyuasin',
                'Kabupaten Empat Lawang', 'Kabupaten Lahat', 'Kabupaten Muara Enim', 'Kabupaten Musi Banyuasin',
                'Kabupaten Musi Rawas', 'Kabupaten Musi Rawas Utara', 'Kabupaten Ogan Ilir', 'Kabupaten Ogan Komering Ilir',
                'Kabupaten Ogan Komering Ulu', 'Kabupaten Ogan Komering Ulu Selatan', 'Kabupaten Ogan Komering Ulu Timur',
                'Kabupaten Penukal Abab Lematang Ilir'
            ],
            'Bengkulu' => [
                'Kota Bengkulu', 'Kabupaten Bengkulu Selatan', 'Kabupaten Bengkulu Tengah', 'Kabupaten Bengkulu Utara',
                'Kabupaten Kaur', 'Kabupaten Kepahiang', 'Kabupaten Lebong', 'Kabupaten Mukomuko',
                'Kabupaten Rejang Lebong', 'Kabupaten Seluma'
            ],
            'Lampung' => [
                'Kota Bandar Lampung', 'Kota Metro', 'Kabupaten Lampung Barat', 'Kabupaten Lampung Selatan',
                'Kabupaten Lampung Tengah', 'Kabupaten Lampung Timur', 'Kabupaten Lampung Utara', 'Kabupaten Mesuji',
                'Kabupaten Pesawaran', 'Kabupaten Pesisir Barat', 'Kabupaten Pringsewu', 'Kabupaten Tanggamus',
                'Kabupaten Tulang Bawang', 'Kabupaten Tulang Bawang Barat', 'Kabupaten Way Kanan'
            ],
            'Kepulauan Bangka Belitung' => [
                'Kota Pangkalpinang', 'Kabupaten Bangka', 'Kabupaten Bangka Barat', 'Kabupaten Bangka Selatan',
                'Kabupaten Bangka Tengah', 'Kabupaten Belitung', 'Kabupaten Belitung Timur'
            ],
            'Kepulauan Riau' => [
                'Kota Batam', 'Kota Tanjungpinang', 'Kabupaten Bintan', 'Kabupaten Karimun',
                'Kabupaten Kepulauan Anambas', 'Kabupaten Lingga', 'Kabupaten Natuna'
            ],
            'DKI Jakarta' => [
                'Kota Jakarta Selatan', 'Kota Jakarta Timur', 'Kota Jakarta Pusat', 'Kota Jakarta Barat',
                'Kota Jakarta Utara', 'Kabupaten Kepulauan Seribu'
            ],
            'Jawa Barat' => [
                'Kota Bandung', 'Kota Bekasi', 'Kota Depok', 'Kota Bogor', 'Kota Cimahi', 'Kota Tasikmalaya',
                'Kota Cirebon', 'Kota Sukabumi', 'Kota Banjar', 'Kabupaten Bandung', 'Kabupaten Bandung Barat',
                'Kabupaten Bekasi', 'Kabupaten Bogor', 'Kabupaten Ciamis', 'Kabupaten Cianjur', 'Kabupaten Cirebon',
                'Kabupaten Garut', 'Kabupaten Indramayu', 'Kabupaten Karawang', 'Kabupaten Kuningan',
                'Kabupaten Majalengka', 'Kabupaten Pangandaran', 'Kabupaten Purwakarta', 'Kabupaten Subang',
                'Kabupaten Sukabumi', 'Kabupaten Sumedang', 'Kabupaten Tasikmalaya'
            ],
            'Jawa Tengah' => [
                'Kota Semarang', 'Kota Surakarta (Solo)', 'Kota Magelang', 'Kota Salatiga', 'Kota Pekalongan',
                'Kota Tegal', 'Kabupaten Banjarnegara', 'Kabupaten Banyumas', 'Kabupaten Batang', 'Kabupaten Blora',
                'Kabupaten Boyolali', 'Kabupaten Brebes', 'Kabupaten Cilacap', 'Kabupaten Demak', 'Kabupaten Grobogan',
                'Kabupaten Jepara', 'Kabupaten Karanganyar', 'Kabupaten Kebumen', 'Kabupaten Kendal', 'Kabupaten Klaten',
                'Kabupaten Kudus', 'Kabupaten Magelang', 'Kabupaten Pati', 'Kabupaten Pekalongan', 'Kabupaten Pemalang',
                'Kabupaten Purbalingga', 'Kabupaten Purworejo', 'Kabupaten Rembang', 'Kabupaten Semarang',
                'Kabupaten Sragen', 'Kabupaten Sukoharjo', 'Kabupaten Tegal', 'Kabupaten Temanggung',
                'Kabupaten Wonogiri', 'Kabupaten Wonosobo'
            ],
            'DI Yogyakarta' => [
                'Kota Yogyakarta', 'Kabupaten Bantul', 'Kabupaten Gunungkidul', 'Kabupaten Kulon Progo', 'Kabupaten Sleman'
            ],
            'Jawa Timur' => [
                'Kota Surabaya', 'Kota Malang', 'Kota Kediri', 'Kota Blitar', 'Kota Madiun', 'Kota Pasuruan',
                'Kota Probolinggo', 'Kota Batu', 'Kota Mojokerto', 'Kabupaten Bangkalan', 'Kabupaten Banyuwangi',
                'Kabupaten Blitar', 'Kabupaten Bojonegoro', 'Kabupaten Bondowoso', 'Kabupaten Gresik', 'Kabupaten Jember',
                'Kabupaten Jombang', 'Kabupaten Kediri', 'Kabupaten Lamongan', 'Kabupaten Lumajang', 'Kabupaten Madiun',
                'Kabupaten Magetan', 'Kabupaten Malang', 'Kabupaten Mojokerto', 'Kabupaten Nganjuk', 'Kabupaten Ngawi',
                'Kabupaten Pacitan', 'Kabupaten Pamekasan', 'Kabupaten Pasuruan', 'Kabupaten Ponorogo',
                'Kabupaten Probolinggo', 'Kabupaten Sampang', 'Kabupaten Sidoarjo', 'Kabupaten Situbondo',
                'Kabupaten Sumenep', 'Kabupaten Trenggalek', 'Kabupaten Tuban', 'Kabupaten Tulungagung'
            ],
            'Banten' => [
                'Kota Tangerang', 'Kota Tangerang Selatan', 'Kota Serang', 'Kota Cilegon', 'Kabupaten Tangerang',
                'Kabupaten Serang', 'Kabupaten Lebak', 'Kabupaten Pandeglang'
            ],
            'Bali' => [
                'Kota Denpasar', 'Kabupaten Badung', 'Kabupaten Bangli', 'Kabupaten Buleleng', 'Kabupaten Gianyar',
                'Kabupaten Jembrana', 'Kabupaten Karangasem', 'Kabupaten Klungkung', 'Kabupaten Tabanan'
            ],
            'Nusa Tenggara Barat' => [
                'Kota Mataram', 'Kota Bima', 'Kabupaten Lombok Barat', 'Kabupaten Lombok Tengah',
                'Kabupaten Lombok Timur', 'Kabupaten Lombok Utara', 'Kabupaten Sumbawa', 'Kabupaten Sumbawa Barat',
                'Kabupaten Bima', 'Kabupaten Dompu'
            ],
            'Nusa Tenggara Timur' => [
                'Kota Kupang', 'Kabupaten Alor', 'Kabupaten Belu', 'Kabupaten Ende', 'Kabupaten Flores Timur',
                'Kabupaten Kupang', 'Kabupaten Lembata', 'Kabupaten Malaka', 'Kabupaten Manggarai',
                'Kabupaten Manggarai Barat', 'Kabupaten Manggarai Timur', 'Kabupaten Nagekeo', 'Kabupaten Ngada',
                'Kabupaten Rote Ndao', 'Kabupaten Sabu Raijua', 'Kabupaten Sikka', 'Kabupaten Sumba Barat',
                'Kabupaten Sumba Barat Daya', 'Kabupaten Sumba Tengah', 'Kabupaten Sumba Timur',
                'Kabupaten Timor Tengah Selatan', 'Kabupaten Timor Tengah Utara'
            ],
            'Kalimantan Barat' => [
                'Kota Pontianak', 'Kota Singkawang', 'Kabupaten Bengkayang', 'Kabupaten Kapuas Hulu',
                'Kabupaten Kayong Utara', 'Kabupaten Ketapang', 'Kabupaten Kubu Raya', 'Kabupaten Landak',
                'Kabupaten Melawi', 'Kabupaten Mempawah', 'Kabupaten Sambas', 'Kabupaten Sanggau',
                'Kabupaten Sekadau', 'Kabupaten Sintang'
            ],
            'Kalimantan Tengah' => [
                'Kota Palangka Raya', 'Kabupaten Barito Selatan', 'Kabupaten Barito Timur', 'Kabupaten Barito Utara',
                'Kabupaten Gunung Mas', 'Kabupaten Kapuas', 'Kabupaten Katingan', 'Kabupaten Kotawaringin Barat',
                'Kabupaten Kotawaringin Timur', 'Kabupaten Lamandau', 'Kabupaten Murung Raya', 'Kabupaten Pulang Pisau',
                'Kabupaten Seruyan', 'Kabupaten Sukamara'
            ],
            'Kalimantan Selatan' => [
                'Kota Banjarmasin', 'Kota Banjarbaru', 'Kabupaten Balangan', 'Kabupaten Banjar',
                'Kabupaten Barito Kuala', 'Kabupaten Hulu Sungai Selatan', 'Kabupaten Hulu Sungai Tengah',
                'Kabupaten Hulu Sungai Utara', 'Kabupaten Kotabaru', 'Kabupaten Tabalong', 'Kabupaten Tanah Bumbu',
                'Kabupaten Tanah Laut', 'Kabupaten Tapin'
            ],
            'Kalimantan Timur' => [
                'Kota Samarinda', 'Kota Balikpapan', 'Kota Bontang', 'Kabupaten Berau', 'Kabupaten Kutai Barat',
                'Kabupaten Kutai Kartanegara', 'Kabupaten Kutai Timur', 'Kabupaten Mahakam Ulu', 'Kabupaten Paser',
                'Kabupaten Penajam Paser Utara'
            ],
            'Kalimantan Utara' => [
                'Kota Tarakan', 'Kabupaten Bulungan', 'Kabupaten Malinau', 'Kabupaten Nunukan', 'Kabupaten Tana Tidung'
            ],
            'Sulawesi Utara' => [
                'Kota Manado', 'Kota Bitung', 'Kota Tomohon', 'Kota Kotamobagu', 'Kabupaten Bolaang Mongondow',
                'Kabupaten Bolaang Mongondow Selatan', 'Kabupaten Bolaang Mongondow Timur', 'Kabupaten Bolaang Mongondow Utara',
                'Kabupaten Kepulauan Sangihe', 'Kabupaten Kepulauan Siau Tagulandang Biaro', 'Kabupaten Kepulauan Talaud',
                'Kabupaten Minahasa', 'Kabupaten Minahasa Selatan', 'Kabupaten Minahasa Tenggara', 'Kabupaten Minahasa Utara'
            ],
            'Sulawesi Tengah' => [
                'Kota Palu', 'Kabupaten Banggai', 'Kabupaten Banggai Kepulauan', 'Kabupaten Banggai Laut',
                'Kabupaten Buol', 'Kabupaten Donggala', 'Kabupaten Morowali', 'Kabupaten Morowali Utara',
                'Kabupaten Parigi Moutong', 'Kabupaten Poso', 'Kabupaten Sigi', 'Kabupaten Tojo Una-Una', 'Kabupaten Tolitoli'
            ],
            'Sulawesi Selatan' => [
                'Kota Makassar', 'Kota Parepare', 'Kota Palopo', 'Kabupaten Bantaeng', 'Kabupaten Barru',
                'Kabupaten Bone', 'Kabupaten Bulukumba', 'Kabupaten Enrekang', 'Kabupaten Gowa', 'Kabupaten Jeneponto',
                'Kabupaten Kepulauan Selayar', 'Kabupaten Luwu', 'Kabupaten Luwu Timur', 'Kabupaten Luwu Utara',
                'Kabupaten Maros', 'Kabupaten Pangkajene dan Kepulauan', 'Kabupaten Pinrang', 'Kabupaten Sidenreng Rappang',
                'Kabupaten Sinjai', 'Kabupaten Soppeng', 'Kabupaten Takalar', 'Kabupaten Tana Toraja',
                'Kabupaten Toraja Utara', 'Kabupaten Wajo'
            ],
            'Sulawesi Tenggara' => [
                'Kota Kendari', 'Kota Baubau', 'Kabupaten Bombana', 'Kabupaten Buton', 'Kabupaten Buton Selatan',
                'Kabupaten Buton Tengah', 'Kabupaten Buton Utara', 'Kabupaten Kolaka', 'Kabupaten Kolaka Timur',
                'Kabupaten Kolaka Utara', 'Kabupaten Konawe', 'Kabupaten Konawe Kepulauan', 'Kabupaten Konawe Selatan',
                'Kabupaten Konawe Utara', 'Kabupaten Muna', 'Kabupaten Muna Barat', 'Kabupaten Wakatobi'
            ],
            'Gorontalo' => [
                'Kota Gorontalo', 'Kabupaten Boalemo', 'Kabupaten Bone Bolango', 'Kabupaten Gorontalo',
                'Kabupaten Gorontalo Utara', 'Kabupaten Pohuwato'
            ],
            'Sulawesi Barat' => [
                'Kabupaten Majene', 'Kabupaten Mamasa', 'Kabupaten Mamuju', 'Kabupaten Mamuju Tengah',
                'Kabupaten Pasangkayu', 'Kabupaten Polewali Mandar'
            ],
            'Maluku' => [
                'Kota Ambon', 'Kota Tual', 'Kabupaten Buru', 'Kabupaten Buru Selatan', 'Kabupaten Kepulauan Aru',
                'Kabupaten Kepulauan Tanimbar', 'Kabupaten Maluku Barat Daya', 'Kabupaten Maluku Tengah',
                'Kabupaten Maluku Tenggara', 'Kabupaten Seram Bagian Barat', 'Kabupaten Seram Bagian Timur'
            ],
            'Maluku Utara' => [
                'Kota Ternate', 'Kota Tidore Kepulauan', 'Kabupaten Halmahera Barat', 'Kabupaten Halmahera Tengah',
                'Kabupaten Halmahera Timur', 'Kabupaten Halmahera Selatan', 'Kabupaten Halmahera Utara',
                'Kabupaten Kepulauan Sula', 'Kabupaten Pulau Morotai', 'Kabupaten Pulau Taliabu'
            ],
            'Papua Barat' => [
                'Kabupaten Fakfak', 'Kabupaten Kaimana', 'Kabupaten Manokwari', 'Kabupaten Manokwari Selatan',
                'Kabupaten Pegunungan Arfak', 'Kabupaten Teluk Bintuni', 'Kabupaten Teluk Wondama'
            ],
            'Papua' => [
                'Kota Jayapura', 'Kabupaten Biak Numfor', 'Kabupaten Jayapura', 'Kabupaten Keerom',
                'Kabupaten Kepulauan Yapen', 'Kabupaten Mamberamo Raya', 'Kabupaten Sarmi', 'Kabupaten Supiori', 'Kabupaten Waropen'
            ],
            'Papua Selatan' => [
                'Kabupaten Merauke', 'Kabupaten Asmat', 'Kabupaten Mappi', 'Kabupaten Boven Digoel'
            ],
            'Papua Tengah' => [
                'Kabupaten Nabire', 'Kabupaten Puncak', 'Kabupaten Puncak Jaya', 'Kabupaten Paniai',
                'Kabupaten Mimika', 'Kabupaten Dogiyai', 'Kabupaten Intan Jaya', 'Kabupaten Deiyai'
            ],
            'Papua Pegunungan' => [
                'Kabupaten Jayawijaya', 'Kabupaten Lanny Jaya', 'Kabupaten Mamberamo Tengah', 'Kabupaten Nduga',
                'Kabupaten Pegunungan Bintang', 'Kabupaten Yahukimo', 'Kabupaten Yalimo', 'Kabupaten Tolikara'
            ],
            'Papua Barat Daya' => [
                'Kota Sorong', 'Kabupaten Sorong', 'Kabupaten Sorong Selatan', 'Kabupaten Raja Ampat',
                'Kabupaten Tambrauw', 'Kabupaten Maybrat'
            ]
        ];

        $insertedCount = 0;

        foreach ($regenciesByProvince as $provName => $names) {
            $province = Province::where('name', $provName)
                ->orWhere('name', 'like', "%{$provName}%")
                ->first();

            if (!$province) {
                continue;
            }

            foreach ($names as $idx => $fullName) {
                $isKota = str_starts_with($fullName, 'Kota') || str_contains($fullName, 'Jakarta');
                $type = $isKota ? 'Kota' : 'Kabupaten';
                $cleanName = trim(str_replace(['Kota ', 'Kabupaten '], '', $fullName));
                $code = sprintf('REG-%02d-%03d', $province->id, $idx + 1);

                $existing = Regency::where(function ($q) use ($province, $cleanName, $fullName, $code) {
                        $q->where('code', $code)
                          ->orWhere(function ($q2) use ($province, $cleanName, $fullName) {
                              $q2->where('province_id', $province->id)
                                 ->whereIn('name', [$cleanName, $fullName]);
                          });
                    })
                    ->first();

                if (!$existing) {
                    Regency::create([
                        'province_id' => $province->id,
                        'name' => $cleanName,
                        'code' => $code,
                        'type' => $type,
                        'status' => 'active'
                    ]);
                    $insertedCount++;
                }
            }
        }
    }
}
