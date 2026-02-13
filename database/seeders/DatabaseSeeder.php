<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Category;
use App\Models\Asset;
use App\Models\Consumable;
use App\Models\Ticket;
use App\Models\MaintenanceSchedule;
use App\Models\AssetLoan;
use App\Models\ConsumableRequest;
use App\Models\Procurement;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // =========================================================
        // 1. BERSIHKAN DATABASE (TRUNCATE SEMUA TABEL)
        // =========================================================
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        $tables = [
            'procurements', 
            'asset_loans', 
            'consumable_requests', 
            'maintenance_schedules', 
            'ticket_comments', 
            'tickets', 
            'consumables', 
            'assets', 
            'categories', 
            'users'
        ];

        foreach ($tables as $table) {
            if (DB::getSchemaBuilder()->hasTable($table)) {
                DB::table($table)->truncate();
            }
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        // =========================================================
        // 2. MULAI SEEDING DATA
        // =========================================================
        DB::transaction(function () {
            
            $now = Carbon::now();

            // --- A. USER ---
            $admin = User::create([
                'username' => 'admin', 'full_name' => 'Super Admin', 'email' => 'admin@kantor.com',
                'password' => Hash::make('password'), 'role' => 'admin', 'division' => 'IT Dept'
            ]);

            $techAndi = User::create([
                'username' => 'tech_andi', 'full_name' => 'Andi Saputra', 'email' => 'andi@kantor.com',
                'password' => Hash::make('password'), 'role' => 'it_support', 'division' => 'IT Support'
            ]);

            $techBudi = User::create([
                'username' => 'tech_budi', 'full_name' => 'Budi Santoso', 'email' => 'budi@kantor.com',
                'password' => Hash::make('password'), 'role' => 'it_support', 'division' => 'IT Support'
            ]);

            $userMkt = User::create([
                'username' => 'user_mkt', 'full_name' => 'Rina Marketing', 'email' => 'rina@kantor.com',
                'password' => Hash::make('password'), 'role' => 'user', 'division' => 'Marketing'
            ]);

            $userFin = User::create([
                'username' => 'user_fin', 'full_name' => 'Doni Finance', 'email' => 'doni@kantor.com',
                'password' => Hash::make('password'), 'role' => 'user', 'division' => 'Finance'
            ]);

            // --- B. KATEGORI (Hanya untuk Tiket) ---
            $catHard = Category::create(['name' => 'Hardware', 'description' => 'Perangkat keras fisik']);
            $catSoft = Category::create(['name' => 'Software', 'description' => 'Aplikasi dan OS']);
            $catNet  = Category::create(['name' => 'Jaringan', 'description' => 'Internet dan LAN']);

            // --- C. ASET FISIK (DIHAPUS CATEGORY_ID NYA) ---
            $laptop = Asset::create([
                'code' => 'AST-LPT-001', 'name' => 'Laptop Dell Latitude 7490', 'serial_number' => 'SN-LPT-001',
                'condition' => 'good', 'status' => 'ready', 'location' => 'Gudang IT', 'purchase_date' => '2023-01-01',
            ]);

            $pc = Asset::create([
                'code' => 'AST-PC-010', 'name' => 'PC Rakitan Core i5', 'serial_number' => 'SN-PC-010',
                'condition' => 'good', 'status' => 'in_use', 'user_id' => $userFin->id, 'location' => 'Ruang Finance', 'purchase_date' => '2022-06-20',
            ]);

            $printer = Asset::create([
                'code' => 'AST-PRT-005', 'name' => 'Printer Epson L3210', 'serial_number' => 'SN-PRT-005',
                'condition' => 'maintenance', 'status' => 'ready', 'location' => 'Meja Servis IT', 'purchase_date' => '2023-05-10',
            ]);

            // --- D. LOGISTIK (CONSUMABLES) ---
            $tinta = Consumable::create([
                'name' => 'Tinta Epson 664 Black', 'category' => 'Tinta', 'stock' => 10, 'min_stock' => 5, 'unit' => 'Botol', 'location' => 'Lemari A'
            ]);
            
            $kertas = Consumable::create([
                'name' => 'Kertas A4 PaperOne', 'category' => 'ATK', 'stock' => 50, 'min_stock' => 10, 'unit' => 'Rim', 'location' => 'Gudang Utama'
            ]);

            // --- E. TIKET (OPERASIONAL) ---
            Ticket::create([
                'ticket_code' => 'TIK-001', 'user_id' => $userMkt->id, 'category_id' => $catNet->id,
                'title' => 'WiFi Marketing Lemot', 'description' => 'Tidak bisa upload file besar.',
                'priority' => 'high', 'status' => 'open', 
                'queue_number' => 1, 
                'created_at' => $now
            ]);

            Ticket::create([
                'ticket_code' => 'TIK-002', 'user_id' => $userFin->id, 'category_id' => $catHard->id, 'assigned_to' => $techAndi->id,
                'title' => 'PC Suka Restart Sendiri', 'description' => 'Layar biru lalu restart.',
                'priority' => 'medium', 'status' => 'in_progress', 
                'queue_number' => 2, 
                'created_at' => $now->copy()->subHours(3)
            ]);

            // --- F. PEMINJAMAN ASET ---
            DB::table('asset_loans')->insert([
                'user_id'     => $userMkt->id,
                'asset_id'    => $laptop->id,
                'admin_id'    => null,
                'loan_date'   => $now->copy()->addDay(),
                'due_date'    => $now->copy()->addDays(4),
                'return_date' => null,
                'status'      => 'pending',
                'notes'       => 'Laptop inventaris ruangan rusak, butuh pengganti.', 
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);

            DB::table('asset_loans')->insert([
                'user_id'     => $userFin->id,
                'asset_id'    => $pc->id,
                'admin_id'    => $admin->id,
                'loan_date'   => $now->copy()->subMonths(6),
                'due_date'    => $now->copy()->addMonths(6),
                'return_date' => null,
                'status'      => 'active',
                'notes'       => 'Peminjaman jangka panjang untuk operasional.',
                'created_at'  => $now->copy()->subMonths(6),
                'updated_at'  => $now->copy()->subMonths(6),
            ]);

            // --- G. PENGAJUAN PEMBELIAN (PROCUREMENT) ---
            DB::table('procurements')->insert([
                'user_id'         => $techBudi->id,
                'ticket_id'       => null,
                'item_name'       => 'SSD NVMe Samsung 980 1TB',
                'description'     => 'Upgrade server database yang lemot.',
                'quantity'        => 1,
                'estimated_price' => 1850000,
                'link_reference'  => 'https://tokopedia.com/ssd-samsung',
                'priority'        => 'critical',
                'status'          => 'pending',
                'admin_note'      => null,
                'created_at'      => $now,
                'updated_at'      => $now,
            ]);

            DB::table('procurements')->insert([
                'user_id'         => $techAndi->id,
                'ticket_id'       => null,
                'item_name'       => 'Tang Crimping LAN',
                'description'     => 'Alat lama hilang.',
                'quantity'        => 1,
                'estimated_price' => 150000,
                'link_reference'  => null,
                'priority'        => 'medium',
                'status'          => 'approved',
                'admin_note'      => 'ACC, segera beli.',
                'created_at'      => $now->copy()->subDays(2),
                'updated_at'      => $now->copy()->subDays(1),
            ]);

            // --- H. JADWAL MAINTENANCE ---
            DB::table('maintenance_schedules')->insert([
                'asset_id'       => $printer->id,
                'technician_id'  => $techAndi->id,
                'scheduled_date' => $now->format('Y-m-d'),
                'status'         => 'scheduled',
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);

        });
    }
}