<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\KnowledgeBase;
use App\Models\User;

class KnowledgeBaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari user IT Support pertama (atau Admin)
        $author = User::where('role', 'it_support')->first() ?? User::where('role', 'admin')->first();

        // Jika tidak ada user sama sekali, buat 1 dummy user sebagai author
        if (!$author) {
            $author = User::create([
                'username' => 'it.system',
                'full_name' => 'IT System Admin',
                'email' => 'it@system.internal',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'division' => 'IT'
            ]);
        }

        KnowledgeBase::create([
            'title' => 'SOP Instalasi Printer Jaringan (Network Printer)',
            'category' => 'Hardware',
            'author_id' => $author->id,
            'content' => "Langkah-langkah menambahkan printer di jaringan lokal untuk PC User:\n\n1. Pastikan PC dan Printer terhubung pada segmen jaringan yang sama (misal: 192.168.1.xxx).\n2. Buka Control Panel -> Devices and Printers -> Add a printer.\n3. Pilih 'Add a network, wireless or Bluetooth printer'.\n4. Biarkan sistem memindai, atau pilih 'The printer that I want isn\'t listed'.\n5. Masukkan IP Address printer secara spesifik (Contoh: 192.168.1.250).\n6. Tunggu instalasi driver selesai (Gunakan driver bawaan Windows jika ada, jika tidak arahkan ke file .inf dari driver resmi).\n7. Lakukan Print Test Page untuk memastikan printer bekerja dengan baik.\n\nCatatan: Pastikan firewall pada PC / Antivirus tidak memblokir port 9100 atau koneksi printer sharing."
        ]);

        KnowledgeBase::create([
            'title' => 'Panduan Troubleshooting Outlook (Tidak Bisa Send/Receive)',
            'category' => 'Software',
            'author_id' => $author->id,
            'content' => "Apabila user melapor Outlook tiba-tiba error tidak bisa sinkron email, lakukan pengecekan berikut secara berurutan:\n\n1. Cek konektivitas internet user. Buka web portal email (Webmail) untuk memastikan server email memang sedang tidak down.\n2. Cek ukuran file PST/OST dari Outlook user. File OST yang melebihi batas toleransi 50GB sering menyebabkan Outlook korup. Lakukan Archive jika penuh.\n3. Periksa port IMAP/POP3 dan SMTP:\n   - IMAP: 993 (SSL/TLS)\n   - POP3: 995 (SSL/TLS)\n   - SMTP: 465 atau 587 (SSL/TLS)\n4. Buka Outlook Mode Aman (Safe Mode) dengan menekan tuts 'Windows + R' lalu ketik 'outlook.exe /safe'. Jika dalam safe mode bisa sinkron, berarti ada kelainan pada add-ins pihak ketiga.\n5. Jika profil korup, masuk ke Control Panel -> Mail -> Show Profiles. Lalu buat profil email baru dan tes login ulang."
        ]);

        KnowledgeBase::create([
            'title' => 'SOP Penanganan Kendala Wi-Fi Tidak Koneksi (Limited Access)',
            'category' => 'Jaringan',
            'author_id' => $author->id,
            'content' => "Saat ada PC/Laptop yang tersambung Wi-Fi namun 'No Internet Access' atau 'Limited':\n\n1. Buka Command Prompt (CMD) sebagai administrator.\n2. Jalankan perintah `ipconfig /release`.\n3. Jalankan perintah `ipconfig /renew`.\n4. Jalankan perintah `ipconfig /flushdns` (Menghapus antrean cache DNS yang mungkin bengkok).\n5. Lakukan Netsh Winsock Reset jika TCP/IP stack korup dengan mengetik `netsh winsock reset`.\n6. Restart PC user tersebut.\n7. Jika masih bermasalah, cek router / access point fisik di area tersebut, cek DHCP pool table apakah sudah penuh kapasitas IP-nya (Exhausted DHCP Pool)."
        ]);

        KnowledgeBase::create([
            'title' => 'Prosedur Retur Aset IT Rusak ke Vendor',
            'category' => 'SOP IT',
            'author_id' => $author->id,
            'content' => "Jika ada pengecekan IT yang menyimpulkan unit memang rusak secara hardware dan masih dalam masa garansi:\n\n1. Kumpulkan unit beserta dus/buku nota pembelian jika ada.\n2. Buat dokumentasi (foto label Serial Number, nota pembelian/garansi).\n3. Ubah status barang di aplikasi inventaris menjadi 'Maintenance' atau 'Rusak'.\n4. Keluarkan/Lepaskan aset tersebut dari penanggung jawab User menjadi tanggung jawab IT (simpan di Gudang Maintenance).\n5. Hubungi nomor RMA / WhatsApp Service Center resmi vendor terkait.\n6. Buat Berita Acara Serah Terima (BAST) jika menyerahkan barang fisik ke kurir vendor.\n7. Terus pantau dan update di menu 'Riwayat Kendala / Tiket' terkait progres service dari vendor."
        ]);
    }
}
