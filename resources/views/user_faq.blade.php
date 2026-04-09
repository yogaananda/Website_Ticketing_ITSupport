@extends('layouts.layout')

@section('title', 'Pusat Bantuan & FAQ')

@section('content')

<div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto space-y-8">
    
    <div class="text-center max-w-2xl mx-auto mb-10">
        <h2 class="text-3xl font-bold text-gray-900 border-gray-200 pb-2">Pusat Bantuan & FAQ</h2>
        <p class="mt-2 text-sm text-gray-500">Pertanyaan yang sering diajukan seputar penggunaan aplikasi IT Support Ticketing.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- Kategori: Tiket Kerusakan -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-indigo-700 flex items-center gap-2 border-b border-gray-100 pb-3 mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Pelaporan Tiket (Kerusakan)
            </h3>
            
            <div class="space-y-4">
                <div class="group">
                    <h4 class="text-sm font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">Bagaimana jika saya salah membuat laporan tiket?</h4>
                    <p class="text-sm text-gray-600 mt-1 pl-3 border-l-2 border-indigo-200 leading-relaxed">Anda dapat menghapus tiket tersebut langsung dari menu "Detail" di halaman Dasbor. Opsi "Batalkan & Hapus" hanya tersedia jika tiket masih berstatus <strong>Menunggu Penanganan (Open)</strong>.</p>
                </div>
                <div class="group">
                    <h4 class="text-sm font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">Berapa lama tiket saya akan diproses?</h4>
                    <p class="text-sm text-gray-600 mt-1 pl-3 border-l-2 border-indigo-200 leading-relaxed">Kecepatan penanganan bergantung pada <strong>Tingkat Urgensi</strong> yang Anda pilih dan panjang antrean. Tiket High/Critical akan diprioritaskan oleh teknisi dibanding tiket Low.</p>
                </div>
                <div class="group">
                    <h4 class="text-sm font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">Apakah saya bisa mengubah detail tiket?</h4>
                    <p class="text-sm text-gray-600 mt-1 pl-3 border-l-2 border-indigo-200 leading-relaxed">Saat ini Anda tidak dapat mengubah deskripsi tiket yang sudah tersubmit. Jika Anda salah ketik, silakan batalkan tiket tersebut (selama masih berstatus Open) dan buat tiket baru.</p>
                </div>
            </div>
        </div>

        <!-- Kategori: Peminjaman & Barang -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-green-700 flex items-center gap-2 border-b border-gray-100 pb-3 mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                Peminjaman Aset & Logistik (ATK)
            </h3>
            
            <div class="space-y-4">
                <div class="group">
                    <h4 class="text-sm font-semibold text-gray-900 group-hover:text-green-600 transition-colors">Apa bedanya Pinjam Aset dan Minta Logistik?</h4>
                    <p class="text-sm text-gray-600 mt-1 pl-3 border-l-2 border-green-200 leading-relaxed"><strong>Pinjam Aset</strong> berlaku untuk barang hardware (contoh: Proyektor, Laptop Cadangan) yang berstatus dipinjam dan harus dikembalikan. Sedangkan <strong>Minta Logistik</strong> berlaku untuk barang sekali habis pakai (Tinta, Kertas, Mouse rusak ganti baru).</p>
                </div>
                <div class="group">
                    <h4 class="text-sm font-semibold text-gray-900 group-hover:text-green-600 transition-colors">Kenapa permintaan barang saya ditolak?</h4>
                    <p class="text-sm text-gray-600 mt-1 pl-3 border-l-2 border-green-200 leading-relaxed">Pengajuan barang bisa saja ditolak oleh tim IT karena stok habis, jadwal peminjaman bentrok, atau perangkat rusak. Anda bisa melihat alasan jelas penolakan pada kolom 'Keterangan IT' di tabel daftar pengajuan Anda.</p>
                </div>
                 <div class="group">
                    <h4 class="text-sm font-semibold text-gray-900 group-hover:text-green-600 transition-colors">Kapan aset pinjaman harus dikembalikan?</h4>
                    <p class="text-sm text-gray-600 mt-1 pl-3 border-l-2 border-green-200 leading-relaxed">Harap kembalikan barang maksimal sesuai dengan waktu kesepakatan peminjaman. Admin atau IT Support akan merubah status log bahwa barang sudah dikembalikan (Returned) ke dalam asalnya.</p>
                </div>
            </div>
        </div>

    </div>

    <div class="mt-8 bg-blue-50 border border-blue-100 rounded-xl p-6 text-center max-w-3xl mx-auto shadow-sm">
        <svg class="w-8 h-8 text-blue-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        <h4 class="text-lg font-bold text-gray-900">Masih punya pertanyaan lain?</h4>
        <p class="text-sm text-gray-600 mt-2 mb-4">Jika FAQ di atas belum menjawab masalah Anda, silakan hubungi langsung tim IT Support lewat nomor saluran perusahaan atau email it@company.com.</p>
    </div>

</div>

@endsection
