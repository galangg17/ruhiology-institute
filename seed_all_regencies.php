<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Regency;

$regenciesData = [
    1 => ['Banda Aceh', 'Sabang', 'Lhokseumawe', 'Langsa', 'Subulussalam', 'Aceh Besar', 'Aceh Pidie', 'Pidie Jaya', 'Aceh Utara', 'Aceh Timur', 'Aceh Selatan', 'Aceh Barat', 'Aceh Barat Daya', 'Aceh Jaya', 'Aceh Singkil', 'Aceh Tamiang', 'Aceh Tenggara', 'Aceh Tengah', 'Bener Meriah', 'Gayo Lues', 'Nagan Raya', 'Simeulue'],
    2 => ['Medan', 'Pematangsiantar', 'Sibolga', 'Tanjungbalai', 'Binjai', 'Tebing Tinggi', 'Padangsidimpuan', 'Gunungsitoli', 'Asahan', 'Batu Bara', 'Dairi', 'Deli Serdang', 'Humbang Hasundutan', 'Karo', 'Labuhanbatu', 'Labuhanbatu Selatan', 'Labuhanbatu Utara', 'Langkat', 'Mandailing Natal', 'Nias', 'Nias Barat', 'Nias Selatan', 'Nias Utara', 'Padang Lawas', 'Padang Lawas Utara', 'Pakpak Bharat', 'Samosir', 'Serdang Bedagai', 'Simalungun', 'Tapanuli Selatan', 'Tapanuli Tengah', 'Tapanuli Utara', 'Toba'],
    3 => ['Padang', 'Bukittinggi', 'Payakumbuh', 'Pariaman', 'Padang Panjang', 'Solok', 'Sawahlunto', 'Agam', 'Dharmasraya', 'Kepulauan Mentawai', 'Lima Puluh Kota', 'Padang Pariaman', 'Pasaman', 'Pasaman Barat', 'Pesisir Selatan', 'Sijunjung', 'Solok Selatan', 'Tanah Datar'],
    4 => ['Pekanbaru', 'Dumai', 'Bengkalis', 'Indragiri Hilir', 'Indragiri Hulu', 'Kampar', 'Kepulauan Meranti', 'Kuantan Singingi', 'Pelalawan', 'Rokan Hilir', 'Rokan Hulu', 'Siak'],
    5 => ['Kota Jambi', 'Kota Sungai Penuh', 'Kerinci', 'Merangin', 'Sarolangun', 'Batanghari', 'Muaro Jambi', 'Tanjung Jabung Barat', 'Tanjung Jabung Timur', 'Bungo', 'Tebo'],
    6 => ['Palembang', 'Prabumulih', 'Pagar Alam', 'Lubuklinggau', 'Banyuasin', 'Empat Lawang', 'Lahat', 'Muara Enim', 'Musi Banyuasin', 'Musi Rawas', 'Musi Rawas Utara', 'Ogan Ilir', 'Ogan Komering Ilir', 'Ogan Komering Ulu', 'Ogan Komering Ulu Selatan', 'Ogan Komering Ulu Timur', 'Penukal Abab Lematang Ilir'],
    7 => ['Kota Bengkulu', 'Bengkulu Selatan', 'Bengkulu Tengah', 'Bengkulu Utara', 'Kaur', 'Kepahiang', 'Lebong', 'Mukomuko', 'Rejang Lebong', 'Seluma'],
    8 => ['Bandar Lampung', 'Metro', 'Lampung Barat', 'Lampung Selatan', 'Lampung Tengah', 'Lampung Timur', 'Lampung Utara', 'Mesuji', 'Pesawaran', 'Pesisir Barat', 'Pringsewu', 'Tanggamus', 'Tulang Bawang', 'Tulang Bawang Barat', 'Way Kanan'],
    9 => ['Pangkalpinang', 'Bangka', 'Bangka Barat', 'Bangka Selatan', 'Bangka Tengah', 'Belitung', 'Belitung Timur'],
    10 => ['Batam', 'Tanjungpinang', 'Bintan', 'Karimun', 'Kepulauan Anambas', 'Lingga', 'Natuna'],
    11 => ['Jakarta Selatan', 'Jakarta Timur', 'Jakarta Pusat', 'Jakarta Barat', 'Jakarta Utara', 'Kepulauan Seribu'],
    12 => ['Kota Bandung', 'Kota Bekasi', 'Kota Depok', 'Kota Bogor', 'Kota Cimahi', 'Kota Tasikmalaya', 'Kota Cirebon', 'Kota Sukabumi', 'Kota Banjar', 'Bandung', 'Bandung Barat', 'Bekasi', 'Bogor', 'Ciamis', 'Cianjur', 'Cirebon', 'Garut', 'Indramayu', 'Karawang', 'Kuningan', 'Majalengka', 'Pangandaran', 'Purwakarta', 'Subang', 'Sukabumi', 'Sumedang', 'Tasikmalaya'],
    13 => ['Kota Semarang', 'Kota Surakarta (Solo)', 'Kota Magelang', 'Kota Salatiga', 'Kota Pekalongan', 'Kota Tegal', 'Banjarnegara', 'Banyumas', 'Batang', 'Blora', 'Boyolali', 'Brebes', 'Cilacap', 'Demak', 'Grobogan', 'Jepara', 'Karanganyar', 'Kebumen', 'Kendal', 'Klaten', 'Kudus', 'Magelang', 'Pati', 'Pekalongan', 'Pemalang', 'Purbalingga', 'Purworejo', 'Rembang', 'Semarang', 'Sragen', 'Sukoharjo', 'Tegal', 'Temanggung', 'Wonogiri', 'Wonosobo'],
    14 => ['Kota Yogyakarta', 'Bantul', 'Gunungkidul', 'Kulon Progo', 'Sleman'],
    15 => ['Kota Surabaya', 'Kota Malang', 'Kota Kediri', 'Kota Blitar', 'Kota Madiun', 'Kota Pasuruan', 'Kota Probolinggo', 'Kota Batu', 'Kota Mojokerto', 'Bangkalan', 'Banyuwangi', 'Blitar', 'Bojonegoro', 'Bondowoso', 'Gresik', 'Jember', 'Jombang', 'Kediri', 'Lamongan', 'Lumajang', 'Madiun', 'Magetan', 'Malang', 'Mojokerto', 'Nganjuk', 'Ngawi', 'Pacitan', 'Pamekasan', 'Pasuruan', 'Ponorogo', 'Probolinggo', 'Sampang', 'Sidoarjo', 'Situbondo', 'Sumenep', 'Trenggalek', 'Tuban', 'Tulungagung'],
    16 => ['Kota Tangerang', 'Kota Tangerang Selatan', 'Kota Serang', 'Kota Cilegon', 'Tangerang', 'Serang', 'Lebak', 'Pandeglang'],
    17 => ['Kota Denpasar', 'Badung', 'Bangli', 'Buleleng', 'Gianyar', 'Jembrana', 'Karangasem', 'Klungkung', 'Tabanan'],
    18 => ['Kota Mataram', 'Kota Bima', 'Lombok Barat', 'Lombok Tengah', 'Lombok Timur', 'Lombok Utara', 'Sumbawa', 'Sumbawa Barat', 'Bima', 'Dompu'],
    19 => ['Kota Kupang', 'Alor', 'Belu', 'Ende', 'Flores Timur', 'Kupang', 'Lembata', 'Malaka', 'Manggarai', 'Manggarai Barat', 'Manggarai Timur', 'Nagekeo', 'Ngada', 'Rote Ndao', 'Sabu Raijua', 'Sikka', 'Sumba Barat', 'Sumba Barat Daya', 'Sumba Tengah', 'Sumba Timur', 'Timor Tengah Selatan', 'Timor Tengah Utara'],
    20 => ['Kota Pontianak', 'Kota Singkawang', 'Bengkayang', 'Kapuas Hulu', 'Kayong Utara', 'Ketapang', 'Kubu Raya', 'Landak', 'Melawi', 'Mempawah', 'Sambas', 'Sanggau', 'Sekadau', 'Sintang'],
    21 => ['Kota Palangka Raya', 'Barito Selatan', 'Barito Timur', 'Barito Utara', 'Gunung Mas', 'Kapuas', 'Katingan', 'Kotawaringin Barat', 'Kotawaringin Timur', 'Lamandau', 'Murung Raya', 'Pulang Pisau', 'Seruyan', 'Sukamara'],
    22 => ['Kota Banjarmasin', 'Kota Banjarbaru', 'Balangan', 'Banjar', 'Barito Kuala', 'Hulu Sungai Selatan', 'Hulu Sungai Tengah', 'Hulu Sungai Utara', 'Kotabaru', 'Tabalong', 'Tanah Bumbu', 'Tanah Laut', 'Tapin'],
    23 => ['Kota Samarinda', 'Kota Balikpapan', 'Kota Bontang', 'Berau', 'Kutai Barat', 'Kutai Kartanegara', 'Kutai Timur', 'Mahakam Ulu', 'Paser', 'Penajam Paser Utara'],
    24 => ['Kota Tarakan', 'Bulungan', 'Malinau', 'Nunukan', 'Tana Tidung'],
    25 => ['Kota Manado', 'Kota Bitung', 'Kota Tomohon', 'Kota Kotamobagu', 'Bolaang Mongondow', 'Bolaang Mongondow Selatan', 'Bolaang Mongondow Timur', 'Bolaang Mongondow Utara', 'Kepulauan Sangihe', 'Kepulauan Siau Tagulandang Biaro', 'Kepulauan Talaud', 'Minahasa', 'Minahasa Selatan', 'Minahasa Tenggara', 'Minahasa Utara'],
    26 => ['Kota Palu', 'Banggai', 'Banggai Kepulauan', 'Banggai Laut', 'Buol', 'Donggala', 'Morowali', 'Morowali Utara', 'Parigi Moutong', 'Poso', 'Sigi', 'Tojo Una-Una', 'Tolitoli'],
    27 => ['Kota Makassar', 'Kota Parepare', 'Kota Palopo', 'Bantaeng', 'Barru', 'Bone', 'Bulukumba', 'Enrekang', 'Gowa', 'Jeneponto', 'Kepulauan Selayar', 'Luwu', 'Luwu Timur', 'Luwu Utara', 'Maros', 'Pangkajene dan Kepulauan', 'Pinrang', 'Sidenreng Rappang', 'Sinjai', 'Soppeng', 'Takalar', 'Tana Toraja', 'Toraja Utara', 'Wajo'],
    28 => ['Kota Kendari', 'Kota Baubau', 'Bombana', 'Buton', 'Buton Selatan', 'Buton Tengah', 'Buton Utara', 'Kolaka', 'Kolaka Timur', 'Kolaka Utara', 'Konawe', 'Konawe Kepulauan', 'Konawe Selatan', 'Konawe Utara', 'Muna', 'Muna Barat', 'Wakatobi'],
    29 => ['Kota Gorontalo', 'Boalemo', 'Bone Bolango', 'Gorontalo', 'Gorontalo Utara', 'Pohuwato'],
    30 => ['Majene', 'Mamasa', 'Mamuju', 'Mamuju Tengah', 'Pasangkayu', 'Polewali Mandar'],
    31 => ['Kota Ambon', 'Kota Tual', 'Buru', 'Buru Selatan', 'Kepulauan Aru', 'Kepulauan Tanimbar', 'Maluku Barat Daya', 'Maluku Tengah', 'Maluku Tenggara', 'Seram Bagian Barat', 'Seram Bagian Timur'],
    32 => ['Kota Ternate', 'Kota Tidore Kepulauan', 'Halmahera Barat', 'Halmahera Tengah', 'Halmahera Timur', 'Halmahera Selatan', 'Halmahera Utara', 'Kepulauan Sula', 'Pulau Morotai', 'Pulau Taliabu'],
    33 => ['Fakfak', 'Kaimana', 'Manokwari', 'Manokwari Selatan', 'Pegunungan Arfak', 'Teluk Bintuni', 'Teluk Wondama'],
    34 => ['Kota Jayapura', 'Biak Numfor', 'Jayapura', 'Keerom', 'Kepulauan Yapen', 'Mamberamo Raya', 'Sarmi', 'Supiori', 'Waropen'],
    35 => ['Merauke', 'Asmat', 'Mappi', 'Boven Digoel'],
    36 => ['Nabire', 'Puncak', 'Puncak Jaya', 'Paniai', 'Mimika', 'Dogiyai', 'Intan Jaya', 'Deiyai'],
    37 => ['Jayawijaya', 'Lanny Jaya', 'Mamberamo Tengah', 'Nduga', 'Pegunungan Bintang', 'Yahukimo', 'Yalimo', 'Tolikara'],
    38 => ['Kota Sorong', 'Sorong', 'Sorong Selatan', 'Raja Ampat', 'Tambrauw', 'Maybrat']
];

$count = 0;
foreach ($regenciesData as $provId => $names) {
    foreach ($names as $idx => $name) {
        $isKota = str_starts_with($name, 'Kota') || str_starts_with($name, 'Jakarta');
        $type = $isKota ? 'Kota' : 'Kabupaten';
        $code = sprintf('REG-%02d-%03d', $provId, $idx + 1);
        
        $reg = Regency::where('province_id', $provId)->where('name', $name)->first();
        if (!$reg) {
            Regency::create([
                'province_id' => $provId,
                'name' => $name,
                'code' => $code,
                'type' => $type,
                'status' => 'active'
            ]);
            $count++;
        }
    }
}

$total = Regency::count();
echo "SUKSES: Added {$count} new regencies. Total Kabupaten/Kota di database sekarang: {$total}!\n";
