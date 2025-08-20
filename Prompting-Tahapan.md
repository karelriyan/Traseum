# 🚀 Panduan Pengembangan "Bank Sampah Cipta Muri"

## 📋 Gambaran Umum Proyek

**Tujuan**: Membangun sistem Bank Sampah Cipta Muri dengan dua komponen utama:

- **Landing Page Publik**: Modern, aksesible, dan responsif untuk umum
- **Panel Admin**: Dashboard lengkap untuk mengelola operasional bank sampah

### 🏗️ Arsitektur Sistem

```
📁 Public Site (/)
├── Landing page (Inertia React)
├── Auth publik (/login, /register)
└── Redirect ke admin untuk pengelola

📁 Admin Panel (/admin)
├── Login admin (/admin/login)
├── Dashboard dengan widgets
├── Manajemen data (anggota, transaksi, sampah)
└── Role-based access control
```

### 🛠️ Tech Stack

- **Backend**: Laravel 12
- **Frontend Publik**: Inertia.js + React + Laravel Breeze
- **Styling**: Tailwind CSS
- **Icons**: Lucide React
- **Admin Panel**: Filament

---

## 🎯 Tahapan Pengembangan

### 📋 Tahap 1: Persiapan & Setup Dasar

#### 1.1 Instalasi Dependencies

# Install dependencies frontend (sudah ada)
npm install framer-motion lucide-react
```

#### 1.2 Setup Database & Migration

```bash
# Jalankan migrasi dasar
php artisan migrate
```

#### 1.3 Konfigurasi Environment

- Pastikan `.env` sudah dikonfigurasi dengan benar
- Database connection
- App URL dan name

---

### 📋 Tahap 2: Backend Foundation

#### 2.1 Setup Models & Database Structure

**Models yang Dibutuhkan:**

- `Member` (Anggota bank sampah)
- `WasteType` (Jenis sampah)
- `Deposit` (Setoran sampah)
- `Withdrawal` (Penarikan dana)
- `Partner` (Kemitraan UMKM)

#### 2.2 Setup Authentication & Permissions

**Roles yang Dibutuhkan:**

- `admin` - Full access ke semua fitur
- `petugas` - Akses terbatas untuk operasional harian
- `viewer` - Read-only access

#### 2.3 Setup Controllers untuk Public

**File yang Dibuat:**

- `app/Http/Controllers/HomeController.php`
- Update `routes/web.php`

---

### 📋 Tahap 3: Setup Filament Admin Panel

#### 3.1 Konfigurasi Panel Provider

**File**: `app/Providers/Filament/AdminPanelProvider.php`

- Setup path `/admin`
- Konfigurasi branding dan warna
- Setup authentication

#### 3.2 Buat Filament Resources

**Resources yang Dibutuhkan:**

1. `MemberResource` - Manajemen anggota
2. `WasteTypeResource` - Manajemen jenis sampah
3. `DepositResource` - Manajemen setoran
4. `WithdrawalResource` - Manajemen penarikan
5. `PartnerResource` - Manajemen kemitraan

#### 3.3 Setup Policies & Permissions

- Buat Policy untuk setiap Resource
- Implement role-based access control
- Setup permission middleware

---

### 📋 Tahap 4: Dashboard & Widgets

#### 4.1 Dashboard Widgets

**File**: `app/Filament/Widgets/*`

1. `StatsOverviewWidget` - Statistik umum
2. `MonthlyDepositChart` - Chart setoran bulanan
3. `PendingWithdrawalTable` - Tabel penarikan pending

#### 4.2 Global Search & Navigation

- Setup global search untuk Member, Deposit, Withdrawal
- Konfigurasi navigation groups dan icons

---

### 📋 Tahap 5: Frontend Landing Page

#### 5.1 Layout & Structure

**Files:**

- `resources/views/app.blade.php` - Shell untuk Inertia
- `resources/js/Layouts/MainLayout.jsx` - Layout utama
- `resources/css/app.css` - Styling Tailwind

#### 5.2 React Components

**Reusable Components:**

- `resources/js/Components/Button.jsx`
- `resources/js/Components/SectionHeader.jsx`
- `resources/js/Components/StatCard.jsx`
- `resources/js/Components/ProgramCard.jsx`
- `resources/js/Components/StepItem.jsx`
- `resources/js/Components/TestimonialItem.jsx`

#### 5.3 Pages

**File**: `resources/js/Pages/Home.jsx`

**Sections yang Dibutuhkan:**

1. **Hero Section** - Headline dan CTA utama
2. **About Section** - Penjelasan tentang Cipta Muri
3. **How It Works** - 4 langkah cara kerja
4. **Programs** - 4 program utama
5. **Stats** - Statistik pencapaian
6. **Testimonials** - Testimoni pengguna
7. **CTA Section** - Call to action akhir

---

### 📋 Tahap 6: Integration & Data Flow

#### 6.1 Backend Integration

- Setup data flow antara landing page dan admin
- Implement caching untuk performa
- Setup API endpoints jika diperlukan

#### 6.2 Business Logic Implementation

- Hook untuk update saldo member saat setoran
- Logic approval/rejection untuk penarikan
- Calculation untuk statistik

---

### 📋 Tahap 7: UI/UX & Design System

#### 7.1 Design System

**Branding Guidelines:**

- **Primary Color**: Hijau `#16a34a`
- **Accent Color**: Kuning `#facc15`
- **Neutral**: Putih/Abu-abu
- **Typography**: Inter/Poppins (400/500/700)

#### 7.2 Accessibility (A11Y)

- Kontras warna AA compliance
- Focus states yang jelas
- ARIA labels untuk elements
- Keyboard navigation
- Screen reader support

#### 7.3 Responsive Design

- Mobile-first approach
- Breakpoints yang konsisten
- Touch-friendly interface

---

### 📋 Tahap 8: Security & Performance

#### 8.1 Security Implementation

- CSRF protection aktif
- Rate limiting untuk login
- Input validation & sanitization
- Role-based access control
- Audit logging (optional)

#### 8.2 Performance Optimization

- Database query optimization
- Asset optimization (Vite build)
- Caching strategies
- Image optimization
- Code splitting

---

### 📋 Tahap 9: Testing & Quality Assurance

#### 9.1 Functional Testing

- Test semua CRUD operations
- Test authentication flows
- Test permission system
- Test business logic

#### 9.2 Performance Testing

- Lighthouse scores ≥ 90
- Load testing untuk concurrent users
- Database performance testing

#### 9.3 Accessibility Testing

- Screen reader testing
- Keyboard navigation testing
- Color contrast validation

---

### 📋 Tahap 10: Deployment & Production

#### 10.1 Production Setup

- Environment configuration
- Database optimization
- Asset compilation
- Error logging setup

#### 10.2 Seeding & Initial Data

```bash
php artisan db:seed
```

- Default admin user
- Sample data untuk testing
- Roles dan permissions

---

## 📄 Detail Spesifikasi Teknis

### 🔧 File Structure & Components

#### Backend Files

```
app/
├── Http/Controllers/
│   └── HomeController.php
├── Models/
│   ├── Member.php
│   ├── WasteType.php
│   ├── Deposit.php
│   ├── Withdrawal.php
│   └── Partner.php
├── Filament/
│   ├── Resources/
│   │   ├── MemberResource.php
│   │   ├── WasteTypeResource.php
│   │   ├── DepositResource.php
│   │   ├── WithdrawalResource.php
│   │   └── PartnerResource.php
│   └── Widgets/
│       ├── StatsOverviewWidget.php
│       ├── MonthlyDepositChart.php
│       └── PendingWithdrawalTable.php
└── Providers/Filament/
    └── AdminPanelProvider.php
```

#### Frontend Files

```
resources/
├── views/
│   └── app.blade.php
├── js/
│   ├── app.jsx
│   ├── Layouts/
│   │   └── MainLayout.jsx
│   ├── Pages/
│   │   └── Home.jsx
│   └── Components/
│       ├── Button.jsx
│       ├── SectionHeader.jsx
│       ├── StatCard.jsx
│       ├── ProgramCard.jsx
│       ├── StepItem.jsx
│       └── TestimonialItem.jsx
└── css/
    └── app.css
```

### 🎨 Landing Page Content Specification

#### Hero Section

- **Headline**: "Ubah Sampah Jadi Tabungan, Wujudkan Lingkungan Bersih Bersama Cipta Muri"
- **CTA Button**: "Gabung Sekarang" → `route('register')`
- **Login Button**: "Login" → `/admin/login`

#### About Section (3 Pilar)

1. **Transparan** - Sistem tracking yang jelas
2. **Ramah Lingkungan** - Mendukung daur ulang
3. **Berbasis Digital** - Platform modern dan mudah

#### How It Works (4 Langkah)

1. **Kumpulkan** - Pisahkan sampah di rumah
2. **Setor** - Bawa ke bank sampah
3. **Dapat Poin** - Sistem konversi otomatis
4. **Tukar** - Tukar poin dengan uang/barang

#### Programs (4 Kartu)

1. **Edukasi** - Workshop lingkungan
2. **Tabungan Digital** - Sistem simpan pinjam
3. **Aplikasi Mobile** - Akses mudah via HP
4. **Kemitraan UMKM** - Mendukung usaha lokal

#### Statistics Display

- **Members**: Total anggota terdaftar
- **Recycled Tons**: Total sampah yang didaur ulang (format: X.X ton)
- **Savings Total**: Total tabungan (format: Rp X.XXX.XXX)

### 🛡️ Security & Permission Matrix

| Role    | Members | WasteTypes | Deposits      | Withdrawals | Partners | Dashboard |
| ------- | ------- | ---------- | ------------- | ----------- | -------- | --------- |
| Admin   | Full    | Full       | Full          | Full        | Full     | Full      |
| Petugas | Read    | Read       | Create/Update | Create/Read | Read     | Limited   |
| Viewer  | Read    | Read       | Read          | Read        | Read     | Read      |

### 📊 Dashboard Widgets Specification

#### Stats Overview Widget

- Total Members (dengan growth rate)
- Total Recycled Weight (kg/ton)
- Total Savings (Rp)
- Pending Withdrawals count

#### Monthly Deposit Chart

- Bar/Line chart untuk 12 bulan terakhir
- Data: Total weight dan total value per bulan
- Filterable by waste type

#### Pending Withdrawals Table

- 5 withdrawal terbaru dengan status "requested"
- Quick actions: Approve/Reject
- Show: Member name, amount, date, method

---

## ✅ Acceptance Criteria

### Functional Requirements

- [ ] Landing page dapat diakses di `/` tanpa error
- [ ] Authentication flow berjalan dengan benar (Breeze + Filament)
- [ ] Admin panel dapat diakses di `/admin` dengan branding hijau
- [ ] Semua CRUD operations berfungsi untuk semua resources
- [ ] Role-based access control bekerja sesuai matrix
- [ ] Dashboard widgets menampilkan data yang benar
- [ ] Global search berfungsi untuk Member, Deposit, Withdrawal
- [ ] Business logic (saldo update, approval flow) berjalan benar

### Performance Requirements

- [ ] Lighthouse score ≥ 90 untuk semua metric (Performance, Accessibility, Best Practices, SEO)
- [ ] Page load time < 3 detik untuk landing page
- [ ] Admin panel responsive pada desktop, tablet, mobile

### Security Requirements

- [ ] CSRF protection aktif pada semua form
- [ ] Input validation pada server side untuk semua input
- [ ] Authorization checks pada setiap action (Policy)
- [ ] Secure session management
- [ ] Rate limiting untuk login attempts

### Data Requirements

- [ ] Seeder menghasilkan admin user dengan role admin
- [ ] Sample data tersedia untuk testing (members, waste types, dll)
- [ ] Database relationships berfungsi dengan benar
- [ ] Soft deletes implemented untuk data penting

---

## 📝 Implementation Notes

### Navigation Structure

**Landing Page:**

- Home (anchor: #home)
- Tentang (#tentang)
- Program (#program)
- Cara Kerja (#cara-kerja)
- Kontak (#kontak)

**Admin Panel:**

- Dashboard
- Transaksi (Deposits, Withdrawals)
- Master Data (Members, Waste Types)
- Kemitraan (Partners)
- Laporan (Reports)

### Form Validation Rules

```php
// Member
'name' => 'required|string|max:255'
'email' => 'required|email|unique:members'
'phone' => 'required|string|max:20'
'address' => 'required|string'

// Deposit
'member_id' => 'required|exists:members,id'
'waste_type_id' => 'required|exists:waste_types,id'
'weight_kg' => 'required|numeric|min:0.1'

// Withdrawal
'member_id' => 'required|exists:members,id'
'amount' => 'required|numeric|min:1000'
'method' => 'required|in:cash,transfer'
```

### Responsive Breakpoints

```css
/* Mobile first */
sm: 640px   /* Small devices */
md: 768px   /* Tablets */
lg: 1024px  /* Laptops */
xl: 1280px  /* Desktops */
```

---

## 🚀 Quick Start Commands

```bash
# 1. Setup dependencies
composer require filament/filament spatie/laravel-permission
npm install framer-motion lucide-react

# 2. Setup database
php artisan migrate
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate

# 3. Install Filament
php artisan filament:install --panels

# 4. Generate resources
php artisan make:filament-resource Member
php artisan make:filament-resource WasteType
php artisan make:filament-resource Deposit
php artisan make:filament-resource Withdrawal
php artisan make:filament-resource Partner

# 5. Generate widgets
php artisan make:filament-widget StatsOverviewWidget --stats-overview
php artisan make:filament-widget MonthlyDepositChart --chart
php artisan make:filament-widget PendingWithdrawalTable --table

# 6. Seed data
php artisan db:seed

# 7. Build assets
npm run build

# 8. Start development
php artisan serve
npm run dev (in separate terminal)
```

---

_Dokumen ini akan diupdate seiring dengan progress pengembangan untuk memastikan semua requirements tercapai dengan baik._
