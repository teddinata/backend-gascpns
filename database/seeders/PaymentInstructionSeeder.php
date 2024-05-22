<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentInstructionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('payment_instructions')->insert([
            // BRI
            [
            'bank_code' => 'BRI',
            'method' => 'ATM',
            'title' => 'LANGKAH 1: TEMUKAN ATM TERDEKAT',
            'instructions' => '1. Masukkan kartu dan pilih bahasa "Bahasa Indonesia".
2. Masukkan PIN, lalu tekan "Benar".
3. Pilih menu "Pembayaran".
4. Pilih "Multi Payment".',
            'created_at' => now(),
            'updated_at' => now()
            ],
            [
                'bank_code' => 'BRI',
                'method' => 'ATM',
                'title' => 'LANGKAH 2: DETAIL PEMBAYARAN',
                'instructions' => '1. Masukkan Nomor Virtual Account 92001988572642231 dan jumlah yang ingin anda bayarkan.
2. Periksa data transaksi dan tekan "YA"',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'BRI',
                'method' => 'ATM',
                'title' => 'LANGKAH 3: TRANSAKSI BERHASIL',
                'instructions' => '1. Setelah transaksi anda selesai, invoice ini akan diupdate secara otomatis. Proses ini mungkin memakan waktu hingga 5 menit',
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'bank_code' => 'BRI',
                'method' => 'Internet Banking',
                'title' => 'LANGKAH 1: MASUK KE AKUN ANDA',
                'instructions' => '1. Buka situs https://ib.bri.co.id/ib-bri/, dan masukkan USER ID dan PASSWORD anda.
2. Pilih "Pembayaran" dan pilih "Briva".',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'BRI',
                'method' => 'Internet Banking',
                'title' => 'LANGKAH 2: DETAIL PEMBAYARAN',
                'instructions' => '1. Masukkan Nomor Virtual Account 92001988572642231 dan jumlah yang ingin anda bayarkan.
2. Masukkan password anda kemudian masukkan mToken internet banking',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'BRI',
                'method' => 'Internet Banking',
                'title' => 'LANGKAH 3: TRANSAKSI BERHASIL',
                'instructions' => '1. Setelah transaksi anda selesai, invoice ini akan diupdate secara otomatis. Proses ini mungkin memakan waktu hingga 5 menit',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'BRI',
                'method' => 'M-Banking',
                'title' => 'LANGKAH 1: MASUK KE AKUN ANDA',
                'instructions' => '1. Buka aplikasi BRI Mobile Banking, masukkan USER ID dan PIN anda.
2. Pilih "Pembayaran" dan pilih "Briva"',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'BRI',
                'method' => 'M-Banking',
                'title' => 'LANGKAH 2: DETAIL PEMBAYARAN',
                'instructions' => '1. Masukkan Nomor Virtual Account anda 92001988572642231 dan jumlah yang ingin anda bayarkan.
2. Masukkan PIN Mobile Banking BRI anda',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'BRI',
                'method' => 'M-Banking',
                'title' => 'LANGKAH 3: TRANSAKSI BERHASIL',
                'instructions' => '1. Setelah transaksi anda selesai, invoice ini akan diupdate secara otomatis. Proses ini mungkin memakan waktu hingga 5 menit',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // MANDIRI - ATM
            [
                'bank_code' => 'MANDIRI',
                'method' => 'ATM',
                'title' => 'LANGKAH 1: TEMUKAN ATM TERDEKAT',
                'instructions' => '1. Masukkan ATM dan tekan "Bahasa Indonesia".
2. Masukkan PIN, lalu tekan "Benar".
3. Pilih "Pembayaran", lalu pilih "Multi Payment"',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'MANDIRI',
                'method' => 'ATM',
                'title' => 'LANGKAH 2: DETAIL PEMBAYARAN',
                'instructions' => '1. Masukkan kode perusahaan "88908" (88908 XENDIT) untuk closed amount VA dan ‘88608’ (88608 XENDIT) untuk open amount VA, lalu tekan `BENAR`
2. Masukkan Nomor Virtual Account 88908988586665460 (contoh), lalu tekan `BENAR`
3. Untuk open amount VA, masukkan nominal yang ingin di transfer, lalu tekan "BENAR"
4. Informasi pelanggan akan ditampilkan, pilih nomor 1 sesuai dengan nominal pembayaran kemudian tekan "YA"
5. Konfirmasi pembayaran akan muncul, tekan "YES", untuk melanjutkan"',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'MANDIRI',
                'method' => 'ATM',
                'title' => 'LANGKAH 3: TRANSAKSI BERHASIL',
                'instructions' => '1. Simpan bukti transaksi anda
2. Transaksi anda berhasil
3. Setelah transaksi anda selesai, invoice ini akan diupdate secara otomatis. Proses ini mungkin memakan waktu hingga 5 menit"',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // MANDIRI IBANKING
            [
                'bank_code' => 'MANDIRI',
                'method' => 'Internet Banking',
                'title' => 'LANGKAH 1: MASUK KE AKUN ANDA',
                'instructions' => '1. Buka situs Mandiri Internet Banking https://ibank.bankmandiri.co.id
2. Masuk menggunakan USER ID dan PASSWORD anda
3. Buka halaman beranda, kemudian pilih "Pembayaran"
4. Pilih "Multi Payment".',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'MANDIRI',
                'method' => 'Internet Banking',
                'title' => 'LANGKAH 2: DETAIL PEMBAYARAN',
                'instructions' => '1. Pilih 88908 XENDIT (untuk closed VA) dan 88608 XENDIT (untuk open VA) sebagai penyedia jasa.
2. Masukkan Nomor Virtual Account 88908988586665460 (contoh)
3. Lalu pilih Lanjut
4. Apabila semua detail benar tekan "KONFIRMASI"
5. Masukkan PIN / Challenge Code Token',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'MANDIRI',
                'method' => 'Internet Banking',
                'title' => 'LANGKAH 3: TRANSAKSI BERHASIL',
                'instructions' => '1. Setelah transaksi pembayaran Anda selesai, simpan bukti pembayaran
2. Invoice ini akan diperbarui secara otomatis. Ini bisa memakan waktu hingga 5 menit.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // MANDIRI MBANKING
            [
                'bank_code' => 'MANDIRI',
                'method' => 'M-Banking',
                'title' => 'LANGKAH 1: MASUK KE AKUN ANDA',
                'instructions' => '1. Buka aplikasi Livin by Mandiri, masukkan PASSWORD atau lakukan verifikasi wajah
2. Pilih menu "IDR Transfer".
3. Pilih “Transfer to new recipient”.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'MANDIRI',
                'method' => 'M-Banking',
                'title' => 'LANGKAH 2: DETAIL PEMBAYARAN',
                'instructions' => '1. Masukkan Nomor Virtual Account 88908988586665460 (contoh)
2. Konfirmasi detail VA dan klik “Continue”
3. Masukkan nominal yang ingin dibayarkan (Jika VA merupakan closed VA, maka nominal akan otomatis terisi)
4. Tinjau dan konfirmasi detail transaksi anda, lalu klik “Continue”
5. Selesaikan transaksi dengan memasukkan MPIN anda.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'MANDIRI',
                'method' => 'M-Banking',
                'title' => 'LANGKAH 3: TRANSAKSI BERHASIL',
                'instructions' => '1. Setelah transaksi pembayaran Anda selesai, simpan bukti pembayaran
2. Invoice ini akan diperbarui secara otomatis.',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // BNI ATM
            [
                'bank_code' => 'BNI',
                'method' => 'ATM',
                'title' => 'LANGKAH 1: TEMUKAN ATM TERDEKAT',
                'instructions' => '1. Masukkan kartu ATM anda.
2. Pilih bahasa.
3. Masukkan PIN ATM anda.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'BNI',
                'method' => 'ATM',
                'title' => 'LANGKAH 2: DETAIL PEMBAYARAN',
                'instructions' => '1. Pilih "Menu Lainnya"
2. Pilih "Transfer"
3. Pilih jenis rekening yang akan anda gunakan (contoh: "Dari Rekening Tabungan")
4. Pilih "Virtual Account Billing"
5. Masukkan Nomor Virtual Account anda contoh: 8808988556620621
6. Tagihan yang harus dibayarkan akan muncul pada layar konfirmasi
7. Konfirmasi, apabila telah sesuai, lanjutkan transaksi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'BNI',
                'method' => 'ATM',
                'title' => 'LANGKAH 3: TRANSAKSI BERHASIL',
                'instructions' => '1. Transaksi Anda telah selesai
2. Setelah transaksi anda selesai, invoice ini akan diupdate secara otomatis. Proses ini mungkin memakan waktu hingga 5 menit',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // BNI I-BANKING
            [
                'bank_code' => 'BNI',
                'method' => 'Internet Banking',
                'title' => 'LANGKAH 1: MASUK KE AKUN ANDA',
                'instructions' => '1. Buka situs https://ibank.bni.co.id
2. Masukkan User ID dan Password',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'BNI',
                'method' => 'Internet Banking',
                'title' => 'LANGKAH 2: DETAIL PEMBAYARAN',
                'instructions' => '1. Pilih menu "Transfer".
2. Pilih menu "Virtual Account Billing".
3. Masukkan Nomor Virtual Account contoh: 8808988556620621.
4. Lalu pilih rekening debet yang akan digunakan. Kemudian tekan "Lanjut".
5. Tagihan yang harus dibayarkan akan muncul pada layar konfirmasi.
6. Masukkan Kode Otentikasi Token',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'BNI',
                'method' => 'Internet Banking',
                'title' => 'LANGKAH 3: TRANSAKSI BERHASIL',
                'instructions' => '1. Transaksi Anda telah selesai
2. Setelah transaksi anda selesai, invoice ini akan diupdate secara otomatis. Proses ini mungkin memakan waktu hingga 5 menit',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // BNI M-BANKING
            [
                'bank_code' => 'BNI',
                'method' => 'M-Banking',
                'title' => 'LANGKAH 1: MASUK KE AKUN ANDA',
                'instructions' => '1. Akses BNI Mobile Banking melalui handphone
2. Masukkan User ID dan Password
3. Pilih menu "Transfer"',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'BNI',
                'method' => 'M-Banking',
                'title' => 'LANGKAH 2: DETAIL PEMBAYARAN',
                'instructions' => '1. Pilih menu "Virtual Account Billing", lalu pilih rekening debet
2. Masukkan Nomor Virtual Account anda contoh:
8808988556620621 pada menu "Input Baru"
3. Tagihan yang harus dibayarkan akan muncul pada layar konfirmasi
4. Konfirmasi transaksi dan masukkan Password Transaksi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'BNI',
                'method' => 'M-Banking',
                'title' => 'LANGKAH 3: TRANSAKSI BERHASIL',
                'instructions' => '1. Transaksi Anda telah selesai
2. Setelah transaksi anda selesai, invoice ini akan diupdate secara otomatis. Proses ini mungkin memakan waktu hingga 5 menit',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // BCA - ATM
            [
                'bank_code' => 'BCA',
                'method' => 'ATM',
                'title' => 'LANGKAH 1: TEMUKAN ATM TERDEKAT',
                'instructions' => '1. Masukkan Kartu ATM BCA
2. Masukkan PIN',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'BCA',
                'method' => 'ATM',
                'title' => 'LANGKAH 2: DETAIL PEMBAYARAN',
                'instructions' => '1. Pilih menu "Transaksi Lainnya"
2. Pilih menu "Transfer"
3. Pilih menu "ke Rekening BCA Virtual Account"
4. Masukkan Nomor Virtual Account Anda contoh: 700701598855309526. Tekan "Benar" untuk melanjutkan
5. Di halaman konfirmasi, pastikan detil pembayaran sudah sesuai seperti No VA, Nama, Perus/Produk dan Total Tagihan, tekan "Benar" untuk melanjutkan
6. Tekan "Ya" jika sudah benar',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'BCA',
                'method' => 'ATM',
                'title' => 'LANGKAH 3: TRANSAKSI BERHASIL',
                'instructions' => '1. Transaksi Anda telah selesai
2. Setelah transaksi anda selesai, invoice ini akan diupdate secara otomatis. Proses ini mungkin memakan waktu hingga 5 menit',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // BCA - IBANKING
            [
                'bank_code' => 'BCA',
                'method' => 'Internet Banking',
                'title' => 'LANGKAH 1: MASUK KE AKUN ANDA',
                'instructions' => '1. Lakukan log in pada aplikasi KlikBCA Individual https://ibank.klikbca.com
2. Masukkan User ID dan PIN',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'BCA',
                'method' => 'Internet Banking',
                'title' => 'LANGKAH 2: DETAIL PEMBAYARAN',
                'instructions' => '1. Pilih "Transfer Dana", kemudian pilih "Transfer ke BCA Virtual Account"
2. Masukkan Nomor Virtual Account contoh: 700701598855309526
3. Pilih "Lanjutkan"
4. Masukkan "RESPON KEYBCA APPLI 1" yang muncul pada Token BCA anda, kemudian tekan tombol "Kirim"',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'BCA',
                'method' => 'Internet Banking',
                'title' => 'LANGKAH 3: TRANSAKSI BERHASIL',
                'instructions' => '1. Transaksi Anda telah selesai
2. Setelah transaksi anda selesai, invoice ini akan diupdate secara otomatis. Proses ini mungkin memakan waktu hingga 5 menit',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // BCA - MBANKING
            [
                'bank_code' => 'BCA',
                'method' => 'M-Banking',
                'title' => 'LANGKAH 1: MASUK KE AKUN ANDA',
                'instructions' => '1. Buka aplikasi BCA Mobile
2. Pilih menu "m-BCA", kemudian masukkan kode akses m-BCA',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'BCA',
                'method' => 'M-Banking',
                'title' => 'LANGKAH 2: DETAIL PEMBAYARAN',
                'instructions' => '1. Pilih “Transaction” lalu pilih "m-Transfer", kemudian pilih "BCA Virtual Account"
2. Masukkan Nomor Virtual Account anda contoh: 700701598855309526, kemudian tekan "OK"
3. Tekan tombol "Kirim" yang berada di sudut kanan atas aplikasi untuk melakukan transfer
4. Tekan "OK" untuk melanjutkan pembayaran
5. Masukkan PIN Anda untuk meng-otorisasi transaksi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'BCA',
                'method' => 'M-Banking',
                'title' => 'LANGKAH 3: TRANSAKSI BERHASIL',
                'instructions' => '1. Transaksi Anda telah selesai
2. Setelah transaksi anda selesai, invoice ini akan diupdate secara otomatis. Proses ini mungkin memakan waktu hingga 5 menit',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // CIMB - ATM
            [
                'bank_code' => 'CIMB',
                'method' => 'ATM',
                'title' => 'LANGKAH 1: TEMUKAN ATM TERDEKAT',
                'instructions' => '1. Masukkan kartu ATM anda
2. Pilih bahasa
3. Masukkan PIN ATM anda',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'CIMB',
                'method' => 'ATM',
                'title' => 'LANGKAH 2: DETAIL PEMBAYARAN',
                'instructions' => '1. Pilih menu "Transfer" dan lalu pilih “Other CIMB Niaga”
2. Masukkan Nomor Virtual Account Anda 9349988556620621 (contoh) pada menu "Input New"
3. Masukkan nominal yang harus dibayarkan
4. Konfirmasi transaksi dan masukkan Password Transaksi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'CIMB',
                'method' => 'ATM',
                'title' => 'LANGKAH 3: TRANSAKSI BERHASIL',
                'instructions' => '1. Transaksi Anda telah selesai
2. Setelah transaksi anda selesai, invoice ini akan diupdate secara otomatis. Proses ini mungkin memakan waktu hingga 5 menit',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // CIMB - IBANKING
            [
                'bank_code' => 'CIMB',
                'method' => 'Internet Banking',
                'title' => 'LANGKAH 1: MASUK KE AKUN ANDA',
                'instructions' => '1. Buka situs https://www.octoclicks.co.id/login/
2. Masukkan User ID dan Password',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'CIMB',
                'method' => 'Internet Banking',
                'title' => 'LANGKAH 2: DETAIL PEMBAYARAN',
                'instructions' => '1. Pilih menu "Transfer" dan lalu pilih “Other CIMB Niaga”
2. Masukkan Nomor Virtual Account Anda 9349988556620621(contoh) pada menu "Input New"
3. Masukkan nominal yang harus dibayarkan
4. Konfirmasi transaksi dan masukkan Password Transaksi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'CIMB',
                'method' => 'Internet Banking',
                'title' => 'LANGKAH 3: TRANSAKSI BERHASIL',
                'instructions' => '1. Transaksi Anda telah selesai
2. Setelah transaksi anda selesai, invoice ini akan diupdate secara otomatis. Proses ini mungkin memakan waktu hingga 5 menit',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // CIMB - MBANKING
            [
                'bank_code' => 'CIMB',
                'method' => 'M-Banking',
                'title' => 'LANGKAH 1: MASUK KE AKUN ANDA',
                'instructions' => '1. Akses Octo Mobile melalui handphone
2. Masukkan User ID dan Password',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'CIMB',
                'method' => 'M-Banking',
                'title' => 'LANGKAH 2: DETAIL PEMBAYARAN',
                'instructions' => '1. Pilih menu "Transfer" dan lalu pilih “Other CIMB Niaga”
2. Masukkan Nomor Virtual Account Anda 9349988556620621(contoh) pada menu "Input New"
3. Masukkan nominal yang harus dibayarkan
4. Konfirmasi transaksi dan masukkan Password Transaksi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'CIMB',
                'method' => 'M-Banking',
                'title' => 'LANGKAH 3: TRANSAKSI BERHASIL',
                'instructions' => '1. Transaksi Anda telah selesai
2. Setelah transaksi anda selesai, invoice ini akan diupdate secara otomatis. Proses ini mungkin memakan waktu hingga 5 menit',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // PERMATA - ATM
            [
                'bank_code' => 'PERMATA',
                'method' => 'ATM',
                'title' => 'LANGKAH 1: TEMUKAN ATM TERDEKAT',
                'instructions' => '1. Masukkan kartu ATM Permata anda
2. Masukkan PIN',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'PERMATA',
                'method' => 'ATM',
                'title' => 'LANGKAH 2: DETAIL PEMBAYARAN',
                'instructions' => '1. Pilih menu "Transaksi Lainnya"
2. Pilih menu "Pembayaran"
3. Pilih menu "Pembayaran Lainnya"
4. Pilih menu "Virtual Account"
5. Masukkan Nomor Virtual Account 7293988549175775
6. Lalu pilih rekening debet yang akan digunakan
7. Konfirmasi detail transaksi anda',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'PERMATA',
                'method' => 'ATM',
                'title' => 'LANGKAH 3: TRANSAKSI BERHASIL',
                'instructions' => '1. Transaksi Anda telah selesai
2. Setelah transaksi anda selesai, invoice ini akan diupdate secara otomatis. Proses ini mungkin memakan waktu hingga 5 menit',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // PERMATA - IBANKING
            [
                'bank_code' => 'PERMATA',
                'method' => 'Internet Banking',
                'title' => 'LANGKAH 1: MASUK KE AKUN ANDA',
                'instructions' => '1. Buka situs https://new.permatanet.com
2. Masukkan User ID dan Password',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'PERMATA',
                'method' => 'Internet Banking',
                'title' => 'LANGKAH 2: DETAIL PEMBAYARAN',
                'instructions' => '1. Pilih "Pembayaran Tagihan"
2. Pilih "Virtual Account"
3. Masukk Nomor Virtual Account 7293988549175775
4. Periksa kembali detail pembayaran anda
5. Masukkan otentikasi transaksi/token',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'PERMATA',
                'method' => 'Internet Banking',
                'title' => 'LANGKAH 3: TRANSAKSI BERHASIL',
                'instructions' => '1. Transaksi Anda telah selesai
2. Setelah transaksi anda selesai, invoice ini akan diupdate secara otomatis. Proses ini mungkin memakan waktu hingga 5 menit',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // PERMATA - MBANKING
            [
                'bank_code' => 'PERMATA',
                'method' => 'M-Banking',
                'title' => 'LANGKAH 1: MASUK KE AKUN ANDA',
                'instructions' => '1. Buka aplikasi PermataMobile Internet
2. Masukkan User ID dan Password',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'PERMATA',
                'method' => 'M-Banking',
                'title' => 'LANGKAH 2: DETAIL PEMBAYARAN',
                'instructions' => '1. Pilih "Pembayaran Tagihan"
2. Pilih "Virtual Account"
3. Masukkan Nomor Virtual Account Anda 7293988549175775
4. Masukkan otentikasi transaksi/token',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'PERMATA',
                'method' => 'M-Banking',
                'title' => 'LANGKAH 3: TRANSAKSI BERHASIL',
                'instructions' => '1. Transaksi Anda telah selesai
2. Setelah transaksi anda selesai, invoice ini akan diupdate secara otomatis. Proses ini mungkin memakan waktu hingga 5 menit',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // BSI - ATM
            [
                'bank_code' => 'BSI',
                'method' => 'ATM',
                'title' => 'LANGKAH 1: TEMUKAN ATM TERDEKAT',
                'instructions' => '1. Masukkan kartu ATM BSI anda
2. Masukkan PIN',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'BSI',
                'method' => 'ATM',
                'title' => 'LANGKAH 2: DETAIL PEMBAYARAN',
                'instructions' => '1. Pilih menu "Pembayaran/Pembelian"
2. Pilih menu "Institusi"
3. Masukkan kode BSI VA Nomor Virtual Account. Contoh: 9347xxxxxxxxxx
4. Detail yang ditampilkan: NIM, Nama, & Total Tagihan.
5. Konfirmasi detail transaksi anda',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'BSI',
                'method' => 'ATM',
                'title' => 'LANGKAH 3: TRANSAKSI BERHASIL',
                'instructions' => '1. Transaksi Anda telah selesai
2. Setelah transaksi anda selesai, invoice ini akan diupdate secara otomatis. Proses ini mungkin memakan waktu hingga 5 menit',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // BSI - IBANKING
            [
                'bank_code' => 'BSI',
                'method' => 'Internet Banking',
                'title' => 'LANGKAH 1: MASUK KE AKUN ANDA',
                'instructions' => '1. Buka situs https://bsinet.bankbsi.co.id
2. Masukkan User ID dan Password',
                'created_at' => now(),
            'updated_at' => now()
            ],
            [
                'bank_code' => 'BSI',
                'method' => 'Internet Banking',
                'title' => 'LANGKAH 2: DETAIL PEMBAYARAN',
                'instructions' => '1. Pilih Menu “Pembayaran”
2. Pilih Nomor Rekening BSI Anda
3. Pilih menu "Institusi"
4. Masukkan nama institusi Xendit (kode 9347)
5. Masukkan Nomor Virtual Account tanpa diikuti kode institusi (tanpa 4 digit pertama) Contoh: 988619428280.
6. Konfirmasi detail transaksi anda
7. Masukkan otentikasi transaksi/token',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'BSI',
                'method' => 'Internet Banking',
                'title' => 'LANGKAH 3: TRANSAKSI BERHASIL',
                'instructions' => '1. Transaksi Anda telah selesai
2. Setelah transaksi anda selesai, invoice ini akan diupdate secara otomatis. Proses ini mungkin memakan waktu hingga 5 menit',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // BSI - MBANKING
            [
                'bank_code' => 'BSI',
                'method' => 'M-Banking',
                'title' => 'LANGKAH 1: MASUK KE AKUN ANDA',
                'instructions' => '1. Buka aplikasi BSI Mobile
2. Masukkan User ID dan Password',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'BSI',
                'method' => 'M-Banking',
                'title' => 'LANGKAH 2: DETAIL PEMBAYARAN',
                'instructions' => '1. Pilih Menu “Pembayaran”
2. Pilih Nomor Rekening BSI Anda
3. Pilih menu "Institusi"
4. Masukkan nama institusi Xendit (kode 9347)
5. Masukkan Nomor Virtual Account tanpa diikuti kode institusi. Contoh: 988619428280.
6. Konfirmasi detail transaksi anda.
7. Masukkan otentikasi transaksi/token.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'bank_code' => 'BSI',
                'method' => 'M-Banking',
                'title' => 'LANGKAH 3: TRANSAKSI BERHASIL',
                'instructions' => '1. Transaksi Anda telah selesai
2. Setelah transaksi anda selesai, invoice ini akan diupdate secara otomatis. Proses ini mungkin memakan waktu hingga 5 menit',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
