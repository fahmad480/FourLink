# FourLink - Link Group Manager

**FourLink** adalah aplikasi web untuk membuat dan mengelola link groups dengan berbagai komponen seperti link, gambar, video, embed, teks, dan file. Aplikasi ini dibangun menggunakan Laravel 10 dengan fitur autentikasi, RBAC menggunakan Spatie Permission, dan UI modern menggunakan Bootstrap 5.

## Fitur Utama

✅ **Autentikasi** - Login, Register, Forgot Password dengan AJAX & SweetAlert2  
✅ **CRUD Link Groups** - Buat, edit, hapus link groups dengan thumbnail & background color  
✅ **Multi-komponen** - Mendukung 6 tipe: Link, Image, Embed, Text, Video, File  
✅ **Public View** - Halaman publik untuk berbagi link groups tanpa login  
✅ **Role-Based Access Control (RBAC)** - Admin & User roles menggunakan Spatie Permission  
✅ **Admin Dashboard** - User management dan monitoring semua link groups  
✅ **Responsive Design** - Bootstrap 5 dengan FontAwesome icons

## Tech Stack

- **Backend**: Laravel 10 (PHP 8.1+)
- **Database**: MySQL/PostgreSQL
- **Frontend**: Bootstrap 5, jQuery, SweetAlert2, FontAwesome
- **Packages**: 
  - `spatie/laravel-permission` - RBAC
  - `intervention/image` - Image processing

## Instalasi

### 1. Clone Repository

```bash
git clone <repository-url>
cd linktreev2
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 3. Environment Configuration

```bash
# Copy .env.example ke .env
cp .env.example .env

# Generate application key
php artisan key:generate
```

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fourlink
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Database Migration & Seeding

```bash
# Jalankan migration
php artisan migrate

# Jalankan seeder untuk roles dan sample users
php artisan db:seed
```

**Default Users:**
- **Admin**: admin@fourlink.com / password
- **User**: user@fourlink.com / password

### 5. Storage Link

```bash
# Buat symbolic link untuk storage
php artisan storage:link
```

### 6. Build Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 7. Jalankan Server

```bash
# Jalankan development server
php artisan serve
```

Aplikasi dapat diakses di: `http://localhost:8000`

## Struktur Routes

### Public Routes
- `/` - Redirect ke login
- `/l/{slug}` - Public view link group (tanpa autentikasi)

### Authentication Routes
- `/login` - Login page
- `/register` - Register page
- `/forgot-password` - Forgot password page
- `/logout` - Logout (POST)

### User Routes (Authenticated)
- `/dashboard` - User dashboard
- `/my-links` - Daftar link groups user
- `/link-groups` - CRUD link groups
- `/link-groups/{linkGroup}/components` - CRUD components

### Admin Routes (Role: Admin)
- `/admin/dashboard` - Admin dashboard dengan statistik
- `/admin/users` - User management (CRUD users)
- `/admin/link-groups` - View semua link groups dari semua users

## Fitur Detail

### 1. Authentication
- Form login, register, forgot password menggunakan jQuery AJAX
- Tidak ada reload halaman saat submit form
- Notifikasi menggunakan SweetAlert2
- Register langsung bisa login (no email verification)

### 2. Link Groups
- Title, description, slug (auto-generated)
- Background color picker
- Thumbnail upload (persegi panjang seperti Google Form)
- View counter
- Active/inactive status

### 3. Link Components
Mendukung 6 tipe komponen:
- **Link** - URL eksternal
- **Image** - Upload gambar
- **Video** - Upload video
- **File** - Upload file/dokumen untuk download
- **Text** - Teks biasa
- **Embed** - Embed code (iframe, widget, dll)

### 4. Role-Based Access Control (RBAC)
**Admin Role:**
- Akses semua fitur user
- View & manage semua link groups dari semua users
- User management (CRUD users)
- Assign roles ke users
- View admin dashboard dengan statistik

**User Role:**
- CRUD link groups milik sendiri
- CRUD components dalam link groups milik sendiri
- Tidak bisa akses link groups user lain

### 5. Public View
- URL: `/l/{slug}`
- Tanpa autentikasi
- Design khusus dengan background color dari link group
- Display semua active components
- Increment view counter otomatis

## License

MIT License

---

**Developed with ❤️ using Laravel 10 & Bootstrap 5**

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
