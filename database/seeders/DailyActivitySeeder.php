<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Asset;
use App\Models\Consumable;
use App\Models\Ticket;
use App\Models\MaintenanceSchedule;
use App\Models\AssetLoan;
use App\Models\ConsumableRequest;
use App\Models\Procurement;
use App\Models\Appointment;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class DailyActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $now = Carbon::now();

        // Data Realistis untuk Seeder
        $ticketScenarios = [
            ['title' => 'WiFi Lantai 2 Putus-putus', 'desc' => 'Koneksi WiFi di area marketing sering terputus saat jam makan siang.', 'cat' => 'Jaringan'],
            ['title' => 'Printer Macet (Paper Jam)', 'desc' => 'Printer Epson di Finance macet total, sudah dicoba tarik kertas tetap error.', 'cat' => 'Hardware'],
            ['title' => 'Install Adobe Acrobat Pro', 'desc' => 'Butuh instalasi Adobe Acrobat untuk edit dokumen kontrak hukum.', 'cat' => 'Software'],
            ['title' => 'Email Tidak Bisa Kirim Lampiran', 'desc' => 'Muncul error saat mencoba mengirim lampiran PDF di Outlook.', 'cat' => 'Software'],
            ['title' => 'Lupa Password Login Windows', 'desc' => 'User baru lupa password setelah libur akhir pekan.', 'cat' => 'Software'],
            ['title' => 'Monitor Bergaris', 'desc' => 'Monitor di meja admin muncul garis merah vertikal di tengah layar.', 'cat' => 'Hardware'],
            ['title' => 'Mouse Tidak Terdeteksi', 'desc' => 'Sudah ganti port USB tapi mouse tetap tidak jalan di PC Marketing.', 'cat' => 'Hardware'],
            ['title' => 'Aplikasi Internal Error 500', 'desc' => 'Tidak bisa input data absen, muncul error server 500.', 'cat' => 'Software'],
            ['title' => 'Kabel LAN Terkelupas', 'desc' => 'Kabel jaringan di bawah meja resepsionis terjepit kursi dan terkelupas.', 'cat' => 'Jaringan'],
            ['title' => 'Permintaan Hak Akses Folder Sharing', 'desc' => 'Mohon buka akses folder Keuangan untuk staf baru.', 'cat' => 'Software'],
        ];

        $loanNotes = [
            'Peminjaman laptop sementara karena laptop utama sedang diservis.',
            'Butuh proyektor untuk presentasi meeting bulanan di Ruang Melati.',
            'Pinjam adapter Mini DP to HDMI untuk event outdoor.',
            'Peminjaman mouse wireless untuk pengganti sementara.',
            'Laptop dipinjam untuk kebutuhan dinas luar kota selama 3 hari.',
        ];

        $requestReasons = [
            'Tinta hitam printer admin sudah habis total.',
            'Butuh kertas A4 tambahan untuk cetak laporan akhir tahun.',
            'Baterai mouse wireless habis, butuh pengganti isi 2.',
            'Stok kertas di lantai 3 sudah menipis (sisa 1 rim).',
            'Tinta warna magenta menunjukkan indikator low.',
        ];

        $procurementDescriptions = [
            'Upgrade RAM server database agar proses query lebih cepat.',
            'Pembelian lisensi antivirus tahunan untuk 50 workstation.',
            'Pengadaan headset baru untuk tim Customer Service.',
            'Ganti SSD untuk laptop direksi yang sudah mulai lemot.',
            'Beli modem cadangan untuk backup koneksi internet utama.',
        ];

        $maintenanceReports = [
            'Pembersihan debu internal PC dan ganti thermal paste.',
            'Update patch keamanan OS dan scan antivirus rutin.',
            'Cek koneksi kabel switch dan penyusunan kabel (cable management).',
            'Cleaning head printer dan pengisian tinta ulang.',
            'Pemeriksaan integritas storage server dan backup data mingguan.',
        ];

        $appointmentTitles = [
            'Meeting Vendor Internet',
            'Briefing Mingguan IT Support',
            'Training User Aplikasi Baru',
            'Maintenance Server Rutin',
            'Visit Cabang untuk Audit IT',
            'Review Kontrak Lisensi Software',
        ];

        // Ambil data referensi
        $users = User::all();
        $itSupports = User::where('role', 'it_support')->get();
        if ($itSupports->isEmpty()) {
            $itSupports = $users;
        }
        $admins = User::where('role', 'admin')->get();
        if ($admins->isEmpty()) {
            $admins = $users;
        }

        $categories = Category::all();
        $assets = Asset::all();
        $consumables = Consumable::all();

        if ($users->isEmpty() || $categories->isEmpty() || $assets->isEmpty() || $consumables->isEmpty()) {
            $this->command->error('Pastikan DatabaseSeeder sudah dijalankan sebelumnya untuk mengisi data master.');
            return;
        }

        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $this->command->info("Seeding realistic data for: " . $date->format('Y-m-d'));

            $totalDataPerDay = rand(21, 25);

            for ($j = 0; $j < $totalDataPerDay; $j++) {
                $activityType = rand(1, 6);
                $randomTime = $date->copy()->setHour(rand(8, 17))->setMinute(rand(0, 59));

                switch ($activityType) {
                    case 1: // Ticket
                        $scenario = $faker->randomElement($ticketScenarios);
                        $category = Category::where('name', 'LIKE', '%' . $scenario['cat'] . '%')->first() ?: $categories->random();
                        
                        Ticket::create([
                            'ticket_code' => 'TIK-' . strtoupper($faker->bothify('???###')),
                            'user_id' => $users->random()->id,
                            'category_id' => $category->id,
                            'assigned_to' => $itSupports->random()->id,
                            'title' => $scenario['title'],
                            'description' => $scenario['desc'],
                            'priority' => $faker->randomElement(['low', 'medium', 'high', 'critical']),
                            'status' => $faker->randomElement(['open', 'in_progress', 'resolved', 'closed']),
                            'queue_number' => $j + 1,
                            'created_at' => $randomTime,
                            'updated_at' => $randomTime,
                        ]);
                        break;

                    case 2: // Asset Loan
                        DB::table('asset_loans')->insert([
                            'user_id' => $users->random()->id,
                            'asset_id' => $assets->random()->id,
                            'admin_id' => $admins->random()->id,
                            'loan_date' => $randomTime,
                            'due_date' => $randomTime->copy()->addDays(rand(1, 7)),
                            'return_date' => $faker->optional(0.6)->randomElement([$randomTime->copy()->addDays(rand(1, 7))]),
                            'status' => $faker->randomElement(['pending', 'active', 'returned', 'overdue', 'rejected']),
                            'notes' => $faker->randomElement($loanNotes),
                            'created_at' => $randomTime,
                            'updated_at' => $randomTime,
                        ]);
                        break;

                    case 3: // Consumable Request
                        DB::table('consumable_requests')->insert([
                            'user_id' => $users->random()->id,
                            'consumable_id' => $consumables->random()->id,
                            'amount' => rand(1, 5),
                            'status' => $faker->randomElement(['pending', 'approved', 'rejected']),
                            'admin_id' => $admins->random()->id,
                            'reason' => $faker->randomElement($requestReasons),
                            'created_at' => $randomTime,
                            'updated_at' => $randomTime,
                        ]);
                        break;

                    case 4: // Procurement
                        DB::table('procurements')->insert([
                            'user_id' => $users->random()->id,
                            'ticket_id' => null,
                            'item_name' => $faker->words(2, true),
                            'description' => $faker->randomElement($procurementDescriptions),
                            'quantity' => rand(1, 5),
                            'estimated_price' => rand(500000, 10000000),
                            'priority' => $faker->randomElement(['low', 'medium', 'high', 'critical']),
                            'status' => $faker->randomElement(['pending', 'approved', 'rejected', 'purchased']),
                            'admin_note' => $faker->optional(0.4)->randomElement(['Sudah diproses', 'Menunggu persetujuan direksi', 'Dana disetujui']),
                            'created_at' => $randomTime,
                            'updated_at' => $randomTime,
                        ]);
                        break;

                    case 5: // Maintenance Schedule
                        DB::table('maintenance_schedules')->insert([
                            'asset_id' => $assets->random()->id,
                            'technician_id' => $itSupports->random()->id,
                            'scheduled_date' => $date->format('Y-m-d'),
                            'completion_date' => $faker->optional(0.7)->randomElement([$date->format('Y-m-d')]),
                            'status' => $faker->randomElement(['scheduled', 'in_progress', 'completed', 'skipped']),
                            'report' => $faker->randomElement($maintenanceReports),
                            'created_at' => $randomTime,
                            'updated_at' => $randomTime,
                        ]);
                        break;

                    case 6: // Appointment
                        Appointment::create([
                            'title' => $faker->randomElement($appointmentTitles),
                            'date' => $date->format('Y-m-d'),
                            'color' => $faker->randomElement(['blue', 'red', 'green', 'yellow', 'purple']),
                            'user_id' => $users->random()->id,
                            'created_at' => $randomTime,
                            'updated_at' => $randomTime,
                        ]);
                        break;
                }
            }
        }
    }
}
