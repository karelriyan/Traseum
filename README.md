# 🌱 CIPTA MURI - Bank Sampah Digital Desa Muntang

<div align="center">
  <img src="public/logo.png" alt="Cipta Muri Logo" width="120" height="120">
  
  <h3>Sistem Bank Sampah Digital untuk Desa Muntang</h3>
  
  ![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
  ![React](https://img.shields.io/badge/React-18-61DAFB?style=for-the-badge&logo=react&logoColor=black)
  ![TypeScript](https://img.shields.io/badge/TypeScript-5-3178C6?style=for-the-badge&logo=typescript&logoColor=white)
  ![Tailwind](https://img.shields.io/badge/Tailwind-3-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
</div>

---

## 📖 Tentang Proyek

**Cipta Muri** adalah aplikasi Bank Sampah Digital yang dikembangkan khusus untuk Desa Muntang. Aplikasi ini memungkinkan nasabah untuk:

- 💰 **Mengelola Saldo** - Lihat saldo dari penjualan sampah
- ♻️ **Setor Sampah** - Input data setor sampah dengan berbagai jenis
- ⭐ **Sistem Poin** - Kumpulkan dan tukar poin Muri
- 🏪 **UMKM Integration** - Belanja di Warung Bu Yeni dan UMKM lokal
- 📊 **Laporan** - Track kontribusi lingkungan dan earnings

## 🎯 Fitur Utama

### 👥 **Untuk Nasabah**
- ✅ Dashboard dengan design modern sesuai Figma
- ✅ Saldo rekening real-time
- ✅ Sistem poin reward
- ✅ Setor sampah dengan berbagai jenis
- ✅ Mutasi dan riwayat transaksi
- ✅ Tukar saldo ke cash
- ✅ Integrasi UMKM lokal
- ✅ Bottom navigation mobile-first

### 🔧 **Untuk Admin**
- ✅ Dashboard analytics dengan statistik lengkap
- ✅ Kelola nasabah (CRUD operations)
- ✅ Validasi dan approve transaksi
- ✅ Kelola jenis sampah dan harga
- ✅ Generate laporan bulanan
- ✅ UMKM management
- ✅ System settings

## 🚀 Quick Start

### Prerequisites
- PHP 8.2+
- Node.js 18+
- MySQL 8.0+
- Composer
- NPM/Yarn

### Instalasi

```bash
# 1. Clone repository
git clone https://github.com/Rifkyrahmat2006/cipta-muri.git
cd cipta-muri

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Configure database di .env file
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cipta_muri
DB_USERNAME=your_username
DB_PASSWORD=your_password

# 5. Run migrations dan seeder
php artisan migrate
php artisan db:seed --class=TestUserSeeder

# 6. Build frontend assets
npm run build

# 7. Start development server
php artisan serve
```

### 🧪 Test Users

Setelah menjalankan seeder, Anda dapat login dengan:

| Role | NIK | Password | Dashboard |
|------|-----|----------|-----------|
| **Admin** | `1234567890123456` | `password123` | Dashboard Admin dengan analytics |
| **Nasabah** | `3201234567890123` | `budi123` | Dashboard Nasabah sesuai Figma |
| **Nasabah** | `3301234567890456` | `siti123` | Dashboard Nasabah |
| **Nasabah** | `3501234567890789` | `ahmad123` | Dashboard Nasabah |

## 📱 Screenshots

### 🎨 **Loading & Login Page**
<img src="docs/screenshots/loading.png" width="300"> <img src="docs/screenshots/login.png" width="300">

### 👤 **Dashboard Nasabah (Figma Design)**
<img src="docs/screenshots/nasabah-dashboard.png" width="300">

*Dashboard dengan greeting personal, saldo rekening, poin Muri, dan action grid sesuai design Figma*

### 🔧 **Dashboard Admin**
<img src="docs/screenshots/admin-dashboard.png" width="600">

*Dashboard admin dengan analytics cards, quick actions, dan recent activities*

## 🏗️ Arsitektur Teknologi

```
┌─────────────────┐    ┌──────────────────┐    ┌─────────────────┐
│   Frontend      │    │    Backend       │    │   Database      │
│                 │    │                  │    │                 │
│ React 18        │◄──►│ Laravel 11       │◄──►│ MySQL 8.0       │
│ TypeScript      │    │ Inertia.js       │    │                 │
│ Tailwind CSS    │    │ Custom NIK Auth  │    │ Users           │
│ shadcn/ui       │    │ Sanctum          │    │ Transactions    │
│ Vite            │    │ Controllers      │    │ Waste_Deposits  │
└─────────────────┘    └──────────────────┘    └─────────────────┘
```

## 📂 Struktur Folder

```
📦 cipta-muri/
├── 📁 app/
│   ├── 📁 Auth/
│   │   └── 📄 NikUserProvider.php        # Custom auth provider
│   ├── 📁 Http/Controllers/Auth/
│   └── 📁 Models/
├── 📁 resources/js/
│   ├── 📁 components/
│   │   ├── 📁 dashboard/
│   │   │   ├── 📁 admin/                 # Admin components
│   │   │   ├── 📁 nasabah/               # Nasabah components  
│   │   │   └── 📄 bottom-navigation.tsx
│   │   └── 📁 ui/                        # shadcn/ui components
│   ├── 📁 layouts/
│   │   └── 📁 dashboard/
│   ├── 📁 pages/
│   │   ├── 📁 auth/                      # Auth pages
│   │   ├── 📄 admin-dashboard.tsx
│   │   ├── 📄 nasabah-dashboard.tsx
│   │   └── 📄 loading.tsx
│   └── 📁 types/
├── 📁 routes/
│   ├── 📄 web.php                        # Main routes
│   └── 📄 auth.php                       # Auth routes
└── 📁 database/
    ├── 📁 migrations/
    └── 📁 seeders/
```

## 🔐 Authentication System

Aplikasi menggunakan **custom NIK-based authentication** dengan fitur:

- ✅ Login menggunakan NIK 16 digit (bukan email)
- ✅ Custom `NikUserProvider` untuk Laravel Auth
- ✅ Validasi NIK di frontend dan backend
- ✅ Rate limiting untuk security
- ✅ Role-based dashboard routing

## 🎨 Design System

### Color Palette
```css
/* Primary Gradient */
background: linear-gradient(to bottom, #84D61F, #297694);

/* Card Glassmorphism */
background: rgba(255, 255, 255, 0.15);
backdrop-filter: blur(10px);
border: 1px solid rgba(255, 255, 255, 0.3);
```

### Typography
- **Font**: Poppins (300, 400, 500, 600, 700, 800)
- **Responsive**: Mobile-first dengan breakpoints MD, LG

## 📊 Development Progress

| Feature | Status | Progress |
|---------|--------|----------|
| 🔐 Authentication System | ✅ Complete | 100% |
| 🎨 UI/UX Design | ✅ Complete | 100% |
| 📱 Dashboard Nasabah | ✅ Complete | 100% |
| 🔧 Dashboard Admin | ✅ Complete | 100% |
| 👥 User Management | 🚧 Planned | 0% |
| 💰 Transaction System | 🚧 Planned | 0% |
| ♻️ Waste Management | 🚧 Planned | 0% |
| 🏪 UMKM Integration | 🚧 Planned | 0% |
| 📊 Reports & Analytics | 🚧 Planned | 0% |
| 📱 Mobile PWA | 🚧 Planned | 0% |

## 🗺️ Roadmap

### 🎯 **Q3 2025 - Core Features**
- [ ] User management system
- [ ] Transaction processing
- [ ] Waste deposit system
- [ ] Points & rewards

### 🎯 **Q4 2025 - Advanced Features**
- [ ] UMKM integration (Warung Bu Yeni)
- [ ] Mobile PWA optimization
- [ ] QR Code system
- [ ] Reports & analytics

### 🎯 **Q1 2026 - Enhancements**
- [ ] Mobile app (React Native)
- [ ] Advanced analytics
- [ ] Gamification features
- [ ] API integration

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

### Development Guidelines
- Follow PSR-12 for PHP code
- Use ESLint + Prettier for TypeScript/React
- Write meaningful commit messages
- Add tests for new features

## 📋 Available Scripts

```bash
# Development
npm run dev          # Start Vite dev server
php artisan serve    # Start Laravel server

# Building
npm run build        # Build for production
npm run preview      # Preview production build

# Database
php artisan migrate  # Run migrations
php artisan db:seed  # Run seeders
php artisan migrate:fresh --seed  # Fresh database with seeds

# Testing
php artisan test     # Run PHP tests
npm run test         # Run JS tests

# Code Quality
npm run lint         # ESLint check
npm run format       # Prettier format
composer phpstan     # Static analysis
```

## 📄 Documentation

- 📖 [Development Guide](DEVELOPMENT.md) - Detailed development documentation
- 🧩 [Components API](COMPONENTS.md) - Component usage and API reference
- 🎨 [Design System](docs/design-system.md) - UI guidelines and patterns
- 🔌 [API Documentation](docs/api.md) - Backend API reference

## 🆘 Support

Jika Anda mengalami masalah atau memiliki pertanyaan:

1. Check [Issues](https://github.com/Rifkyrahmat2006/cipta-muri/issues) untuk masalah yang sudah diketahui
2. Create [New Issue](https://github.com/Rifkyrahmat2006/cipta-muri/issues/new) jika masalah belum ada
3. Join [Discussions](https://github.com/Rifkyrahmat2006/cipta-muri/discussions) untuk diskusi umum

## 👨‍💻 Developer

**Rifky Rahmat**
- GitHub: [@Rifkyrahmat2006](https://github.com/Rifkyrahmat2006)
- Project: [cipta-muri](https://github.com/Rifkyrahmat2006/cipta-muri)

## 📜 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

<div align="center">
  <p>Made with ❤️ for Desa Muntang</p>
  <p>Bangun ekonomi hijau melalui teknologi digital</p>
</div>
