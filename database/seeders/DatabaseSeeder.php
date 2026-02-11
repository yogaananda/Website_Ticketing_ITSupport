<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. BERSIHKAN DATABASE
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('ticket_comments')->truncate();
        DB::table('tickets')->truncate();
        DB::table('users')->truncate();
        DB::table('categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::transaction(function () {

            // ---------------------------------------------------------
            // 2. KATEGORI
            // ---------------------------------------------------------
            $catHardware = Category::create(['name' => 'Hardware', 'description' => 'Monitor, CPU, Printer, Mouse']);
            $catSoftware = Category::create(['name' => 'Software', 'description' => 'OS, Office, Antivirus']);
            $catNetwork  = Category::create(['name' => 'Jaringan', 'description' => 'Wifi, LAN, Internet Access']);


            // ---------------------------------------------------------
            // 3. USER (ADMIN, TEKNISI, KARYAWAN)
            // ---------------------------------------------------------
            
            // Admin
            User::create([
                'username' => 'admin', 'full_name' => 'Super Admin', 'email' => 'admin@kantor.com',
                'password' => Hash::make('password'), 'role' => 'admin', 'division' => 'IT Dept'
            ]);

            // Teknisi 1 (Andi - Rajin, banyak tiket selesai)
            $techAndi = User::create([
                'username' => 'tech_andi', 'full_name' => 'Andi Saputra', 'email' => 'andi@kantor.com',
                'password' => Hash::make('password'), 'role' => 'it_support', 'division' => 'IT Support'
            ]);

            // Teknisi 2 (Budi - Sedang handle tiket sulit)
            $techBudi = User::create([
                'username' => 'tech_budi', 'full_name' => 'Budi Santoso', 'email' => 'budi@kantor.com',
                'password' => Hash::make('password'), 'role' => 'it_support', 'division' => 'IT Support'
            ]);

            // Teknisi 3 (Citra - Baru, belum ada tiket)
            $techCitra = User::create([
                'username' => 'tech_citra', 'full_name' => 'Citra Lestari', 'email' => 'citra@kantor.com',
                'password' => Hash::make('password'), 'role' => 'it_support', 'division' => 'IT Support'
            ]);

            // Karyawan Pelapor
            $userMkt = User::create([
                'username' => 'user_mkt', 'full_name' => 'Rina Marketing', 'email' => 'rina@kantor.com',
                'password' => Hash::make('password'), 'role' => 'user', 'division' => 'Marketing'
            ]);

            $userFin = User::create([
                'username' => 'user_fin', 'full_name' => 'Doni Finance', 'email' => 'doni@kantor.com',
                'password' => Hash::make('password'), 'role' => 'user', 'division' => 'Finance'
            ]);

            $userHR = User::create([
                'username' => 'user_hr', 'full_name' => 'Eka HRD', 'email' => 'eka@kantor.com',
                'password' => Hash::make('password'), 'role' => 'user', 'division' => 'HRD'
            ]);


            // ---------------------------------------------------------
            // 4. HISTORI TIKET SELESAI (Backdate 7 Hari Terakhir)
            // ---------------------------------------------------------
            // Kita buat 20 tiket selesai acak
            for ($i = 0; $i < 20; $i++) {
                $daysAgo = rand(0, 7);
                $date = Carbon::now()->subDays($daysAgo)->setHour(rand(8, 16));
                
                // Random pelapor & teknisi
                $randomUser = [$userMkt, $userFin, $userHR][rand(0, 2)];
                $randomTech = [$techAndi, $techBudi][rand(0, 1)]; // Citra belum handle tiket selesai
                $randomCat  = [$catHardware, $catSoftware, $catNetwork][rand(0, 2)];

                $ticket = Ticket::create([
                    'ticket_code'   => 'DONE-' . $date->timestamp . $i,
                    'user_id'       => $randomUser->id,
                    'assigned_to'   => $randomTech->id, // Mengisi kolom assigned_to
                    'category_id'   => $randomCat->id,
                    'title'         => 'Perbaikan Rutin #' . ($i+1),
                    'description'   => 'Kendala operasional harian yang sudah diselesaikan.',
                    'priority'      => ['low', 'medium'][rand(0, 1)],
                    'status'        => 'resolved',
                    'queue_number'  => $i + 1,
                    'created_at'    => $date,
                    'updated_at'    => $date->copy()->addHours(rand(1, 5)),
                ]);

                // Komentar Penyelesaian
                Comment::create([
                    'ticket_id' => $ticket->id,
                    'user_id'   => $randomTech->id,
                    'message'   => 'SELESAI: Masalah sudah ditangani dan user sudah konfirmasi OK.',
                    'created_at' => $date->copy()->addHours(rand(1, 5))
                ]);
            }


            // ---------------------------------------------------------
            // 5. TIKET "IN PROGRESS" (Sedang Dikerjakan)
            // ---------------------------------------------------------
            
            // Tiket 1: Dipegang Andi (Hardware)
            $t1 = Ticket::create([
                'ticket_code' => 'PROG-001', 'user_id' => $userMkt->id, 'assigned_to' => $techAndi->id,
                'category_id' => $catHardware->id, 'title' => 'Printer Macet Total',
                'description' => 'Printer Epson di meja depan paper jam terus.',
                'priority' => 'medium', 'status' => 'in_progress', 'queue_number' => 100,
                'created_at' => now()->subHours(4), 'updated_at' => now()
            ]);
            Comment::create(['ticket_id' => $t1->id, 'user_id' => $techAndi->id, 'message' => 'PROGRES: Sedang membongkar roller printer.']);

            // Tiket 2: Dipegang Andi (Network)
            $t2 = Ticket::create([
                'ticket_code' => 'PROG-002', 'user_id' => $userHR->id, 'assigned_to' => $techAndi->id,
                'category_id' => $catNetwork->id, 'title' => 'Kabel LAN Putus',
                'description' => 'Kabel LAN di ruang meeting tidak connect.',
                'priority' => 'low', 'status' => 'in_progress', 'queue_number' => 101,
                'created_at' => now()->subHours(2), 'updated_at' => now()
            ]);

            // Tiket 3: Dipegang Budi (Software - CRITICAL)
            $t3 = Ticket::create([
                'ticket_code' => 'PROG-CRIT', 'user_id' => $userFin->id, 'assigned_to' => $techBudi->id,
                'category_id' => $catSoftware->id, 'title' => 'SERVER ACCURATE DOWN',
                'description' => 'Divisi finance tidak bisa input faktur, urgent!',
                'priority' => 'high', 'status' => 'in_progress', 'queue_number' => 102,
                'created_at' => now()->subHours(1), 'updated_at' => now()
            ]);
            Comment::create(['ticket_id' => $t3->id, 'user_id' => $techBudi->id, 'message' => 'PROGRES: Restarting service database server...']);


            // ---------------------------------------------------------
            // 6. TIKET "OPEN" (Belum Ada yang Ambil)
            // ---------------------------------------------------------

            Ticket::create([
                'ticket_code' => 'OPEN-001', 'user_id' => $userMkt->id, 'assigned_to' => null,
                'category_id' => $catSoftware->id, 'title' => 'Install Adobe Photoshop',
                'description' => 'Mohon install photoshop untuk desainer baru.',
                'priority' => 'medium', 'status' => 'open', 'queue_number' => 103,
                'created_at' => now()
            ]);

            Ticket::create([
                'ticket_code' => 'OPEN-002', 'user_id' => $userHR->id, 'assigned_to' => null,
                'category_id' => $catHardware->id, 'title' => 'Request Mouse Baru',
                'description' => 'Mouse klik kiri kadang macet.',
                'priority' => 'low', 'status' => 'open', 'queue_number' => 104,
                'created_at' => now()
            ]);

            Ticket::create([
                'ticket_code' => 'OPEN-003', 'user_id' => $userFin->id, 'assigned_to' => null,
                'category_id' => $catNetwork->id, 'title' => 'Wifi Lantai 3 Lemot',
                'description' => 'Koneksi putus nyambung sejak pagi.',
                'priority' => 'high', 'status' => 'open', 'queue_number' => 105,
                'created_at' => now()
            ]);

        });
    }
}