# e-Bank Sampah Cipta Muri

Sistem manajemen bank sampah digital komprehensif yang dibangun dengan Laravel dan React, dirancang untuk menyederhanakan pengelolaan sampah, transaksi keuangan, dan keterlibatan masyarakat untuk praktik lingkungan yang berkelanjutan.

## 🌟 Ringkasan

e-Bank Sampah Cipta Muri adalah platform digital modern yang mengubah pengelolaan sampah menjadi pengalaman yang membuahkan hasil. Sistem ini memungkinkan anggota masyarakat untuk menyetor sampah daur ulang dan mendapatkan poin/uang, sementara memberikan administrator alat yang powerful untuk mengelola operasional, melacak transaksi, dan menghasilkan laporan.

## 🏗️ Arsitektur

### Backend (Laravel 11 + Filament 3)
- **Framework**: Laravel 11 dengan fitur PHP 8.2+ modern
- **Admin Panel**: Filament 3 untuk antarmuka administratif yang intuitif
- **Database**: SQLite (development) / MySQL (production) dengan primary key ULID
- **Authentication**: Laravel Sanctum dengan permission berbasis peran
- **Export System**: Kemampuan export Excel/CSV/PDF yang canggih

### Frontend (React 18 + TypeScript)
- **Framework**: React 18 dengan TypeScript untuk keamanan tipe
- **Build Tool**: Vite untuk pengembangan cepat dan build yang dioptimalkan
- **UI Components**: Komponen kustom dengan Tailwind CSS dan Radix UI
- **Animations**: Framer Motion untuk interaksi pengguna yang smooth
- **State Management**: React hooks dengan Inertia.js untuk integrasi Laravel yang seamless

## 🚀 Fitur Utama

### 📊 Dashboard Manajemen
- Statistik real-time dan monitoring
- Pelacakan transaksi dan analitik
- Ringkasan aktivitas pengguna
- Export data dalam format multiple (Excel, CSV, PDF)

### 👥 Manajemen Nasabah
- Pendaftaran nasabah lengkap dengan validasi NIK
- Pelacakan nomor Kartu Keluarga (KK)
- Manajemen alamat (Dusun/RW/RT untuk penduduk lokal)
- Informasi kontak dan tingkat pendidikan
- Integrasi tabungan emas dengan Pegadaian

### ♻️ Sistem Penyetoran Sampah
- Manajemen penyetoran sampah multi-jenis
- Konversi otomatis berat ke poin
- Dukungan donasi untuk pemegang rekening non-anggota
- Perhitungan saldo real-time
- Riwayat transaksi dengan audit trail lengkap

### 💰 Operasional Keuangan
- Sistem pencatatan double-entry
- Permintaan penarikan (tunai, transfer bank, tabungan emas)
- Validasi saldo dan pencegahan overdraw
- Kemampuan pembalikan transaksi
- Integrasi tabungan emas Pegadaian

### 📈 Pelaporan & Analitik
- Export data nasabah komprehensif
- Laporan transaksi dengan filter kustom
- Pernyataan keuangan dan ringkasan
- Pelacakan dampak lingkungan
- Kolom export yang dapat disesuaikan

## 🗄️ Struktur Database

### Tabel Utama
- **`rekening`** - Akun nasabah dengan informasi personal
- **`sampah`** - Definisi jenis sampah dengan harga
- **`setor_sampah`** - Transaksi penyetoran sampah
- **`withdraw_requests`** - Permintaan penarikan dana
- **`saldo_transactions`** - Riwayat transaksi keuangan
- **`poin_transactions`** - Riwayat transaksi poin

### Hubungan Kunci
- Nasabah dapat memiliki multiple transaksi penyetoran sampah
- Setiap penyetoran dapat berisi multiple jenis sampah
- Transaksi keuangan dihasilkan otomatis untuk semua operasi
- Dukungan soft delete untuk pemulihan data

## 🛠️ Instalasi

### Prasyarat
- PHP 8.2 atau lebih tinggi
- Node.js 18 atau lebih tinggi
- Composer
- MySQL (production) atau SQLite (development)

### Langkah Instalasi

1. **Clone repository**
   ```bash
   git clone https://github.com/your-repo/e-bank-sampah-cipta-muri.git
   cd e-bank-sampah-cipta-muri
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Konfigurasi Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Setup Database**
   ```bash
   # Untuk SQLite (development)
   touch database/database.sqlite
   
   # Untuk MySQL (production)
   # Buat database dan update .env dengan credentials
   
   php artisan migrate --seed
   ```

6. **Build Frontend Assets**
   ```bash
   npm run build
   ```

7. **Start Development Server**
   ```bash
   php artisan serve
   npm run dev
   ```

### Perintah Development
```bash
# Start semua service secara bersamaan
composer run dev

# Build untuk production
npm run build

# Run tests
composer run test

# Format code
npm run format

# Type checking
npm run types
```

## 🔧 Konfigurasi

### Variabel Environment
```env
APP_NAME="e-Bank Sampah Cipta Muri"
APP_ENV=local
APP_KEY=base64:your-generated-key

# Database
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# Untuk MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=bank_sampah
# DB_USERNAME=root
# DB_PASSWORD=

# File Storage
FILESYSTEM_DISK=local
```

### Database Seeding
Sistem ini menyertakan seeder komprehensif untuk setup awal:
```bash
php artisan db:seed --class=DatabaseSeeder
```

## 📱 Antarmuka Pengguna

### Admin Panel (Filament)
- **Dashboard**: Ringkasan metrik kunci dan aktivitas terkini
- **Manajemen Nasabah**: Operasi CRUD untuk akun nasabah
- **Penyetoran Sampah**: Rekam dan kelola transaksi pengumpulan sampah
- **Operasional Keuangan**: Kelola penarikan dan saldo
- **Laporan**: Hasilkan dan export berbagai laporan

### Website Publik (React)
- **Hero Section**: Pengenalan animasi dengan pesan lingkungan
- **Tentang Kami**: Cerita organisasi dan misi
- **Program**: Ringkasan program pengelolaan sampah
- **Cara Kerja**: Panduan pengguna langkah demi langkah
- **Testimoni**: Umpan balik komunitas dan cerita sukses
- **Berita & Update**: Pengumuman dan artikel terkini
- **Informasi Kontak**: Lokasi dan detail kontak

## 🔐 Fitur Keamanan

- **Authentication**: API tokens Laravel Sanctum
- **Authorization**: Control access berbasis peran (Super Admin, Admin, User)
- **Data Validation**: Validasi input komprehensif dengan aturan kustom
- **SQL Injection Protection**: Eloquent ORM dengan query yang terparameterisasi
- **XSS Prevention**: Escaping otomatis konten pengguna
- **CSRF Protection**: Proteksi CSRF bawaan Laravel
- **Soft Deletes**: Kemampuan pemulihan data
- **Audit Trail**: Pelacakan riwayat transaksi lengkap

## 📊 Logika Bisnis

### Perhitungan Penyetoran Sampah
```php
// Perhitungan otomatis berdasarkan berat dan jenis
$balance = $weight * $wasteType->saldo_per_kg;
$points = $weight * $wasteType->poin_per_kg;
```

### Operasional Keuangan
- **Deposit**: Kredit saldo nasabah dan buat record transaksi
- **Withdrawal**: Debit saldo setelah validasi dan buat permintaan penarikan
- **Donasi**: Rekam berat sampah tanpa dampak keuangan
- **Reversal**: Pembalikan transaksi otomatis saat delete/restore

### Integrasi Pegadaian
- Pelacatan status akun tabungan emas
- Dukungan untuk pendaftaran baru dan akun existing
- Kemampuan transfer langsung ke akun Pegadaian

## 🚀 Deployment

### Setup Production
1. Konfigurasi variabel environment production
2. Setup database MySQL
3. Run migrations dengan seeding
4. Build frontend assets yang dioptimalkan
5. Konfigurasi web server (Apache/Nginx)
6. Setup SSL certificate
7. Konfigurasi cron jobs untuk tasks terjadwal

### Persyaratan Server
- PHP 8.2+
- Node.js 18+
- MySQL 8.0+ atau MariaDB 10.5+
- Web Server (Apache/Nginx)
- SSL Certificate (HTTPS)

## 🤝 Kontribusi

1. Fork repository
2. Buat feature branch (`git checkout -b feature/amazing-feature`)
3. Commit perubahan (`git commit -m 'Add amazing feature'`)
4. Push ke branch (`git push origin feature/amazing-feature`)
5. Buka Pull Request

### Panduan Development
- Ikuti standar coding Laravel
- Gunakan TypeScript untuk development frontend
- Tulis commit messages yang meaningful
- Sertakan tests untuk fitur baru
- Update documentation untuk perubahan API

## 📄 Lisensi

Proyek ini dilisensikan di bawah MIT License - lihat file [LICENSE](LICENSE) untuk detail.

## 🆘 Dukungan

Untuk dukungan dan pertanyaan:
- 📧 Email: info@ciptamuri.co.id
- 📱 Phone: +62 812-3456-7890
- 📍 Location: Jl. Raya Cilacap, Jawa Tengah, Indonesia
- 🌐 Website: https://ciptamuri.co.id

## 🏆 Acknowledgments

- **Laravel Framework** - PHP framework yang menggerakkan aplikasi ini
- **Filament** - Antarmuka admin panel yang indah
- **React** - JavaScript library untuk membangun user interfaces
- **Tailwind CSS** - CSS framework utility-first
- **The Community** - Semua contributor dan user yang membuat proyek ini lebih baik

---

**e-Bank Sampah Cipta Muri** - Mengubah sampah menjadi kekayaan, satu setoran pada satu waktu. 🌱💰