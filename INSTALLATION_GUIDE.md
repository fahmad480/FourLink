# Panduan Instalasi FourLink

Panduan lengkap instalasi project FourLink dari awal.

## Persyaratan Sistem

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL/PostgreSQL
- Git

## Langkah-Langkah Instalasi

### 1. Install Dependencies Composer

```bash
composer install
```

Output yang diharapkan:
```
Installing dependencies from lock file
...
Package manifest generated successfully.
```

### 2. Install Dependencies NPM

```bash
npm install
```

### 3. Setup Environment File

```bash
# Windows
copy .env.example .env

# Linux/Mac
cp .env.example .env
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Konfigurasi Database

Edit file `.env` dan sesuaikan dengan database Anda:

```env
APP_NAME=FourLink
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fourlink
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Buat Database

Buat database baru dengan nama `fourlink` (atau sesuai `DB_DATABASE` di `.env`):

**Via MySQL Command:**
```sql
CREATE DATABASE fourlink CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**Via phpMyAdmin:**
- Klik "New" di sidebar
- Masukkan nama database: `fourlink`
- Pilih Collation: `utf8mb4_unicode_ci`
- Klik "Create"

### 7. Jalankan Migration

```bash
php artisan migrate
```

Ini akan membuat tables:
- users
- link_groups
- link_components
- roles (Spatie Permission)
- permissions (Spatie Permission)
- model_has_roles (Spatie Permission)
- model_has_permissions (Spatie Permission)
- role_has_permissions (Spatie Permission)

### 8. Jalankan Seeder

```bash
php artisan db:seed
```

Seeder akan membuat:
- 2 Roles: `admin` dan `user`
- 4 Permissions
- 2 Users default:
  - Admin: `admin@fourlink.com` / `password`
  - User: `user@fourlink.com` / `password`

### 9. Create Storage Link

```bash
php artisan storage:link
```

Ini membuat symbolic link dari `public/storage` ke `storage/app/public` untuk mengakses uploaded files.

### 10. Build Assets

**Development Mode (dengan hot reload):**
```bash
npm run dev
```

**Production Mode:**
```bash
npm run build
```

### 11. Jalankan Server Development

```bash
php artisan serve
```

Server akan berjalan di: `http://localhost:8000`

## Verifikasi Instalasi

### 1. Test Login

Buka browser dan akses: `http://localhost:8000/login`

Login dengan:
- Email: `admin@fourlink.com`
- Password: `password`

### 2. Test Fitur

Setelah login, coba:
1. Buat Link Group baru
2. Tambah Components
3. View Public Page
4. Access Admin Dashboard (jika login sebagai admin)

## Troubleshooting

### Error: "Class 'Spatie\Permission\Traits\HasRoles' not found"

**Solusi:**
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

### Error: "Storage link not working"

**Solusi:**
```bash
# Hapus link lama jika ada
rm public/storage

# Buat link baru
php artisan storage:link
```

### Error: "SQLSTATE[HY000] [1045] Access denied"

**Solusi:**
- Periksa kredensial database di `.env`
- Pastikan MySQL service berjalan
- Test koneksi dengan MySQL client

### Error: "npm run dev not working"

**Solusi:**
```bash
# Clear npm cache
npm cache clean --force

# Remove node_modules
rm -rf node_modules

# Reinstall
npm install

# Try again
npm run dev
```

### Assets tidak ter-load

**Solusi:**
```bash
# Build ulang assets
npm run build

# Clear cache Laravel
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Permission Denied pada Storage

**Windows:**
Tidak ada aksi khusus diperlukan.

**Linux/Mac:**
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

## Konfigurasi Production

Untuk deployment production, lakukan langkah tambahan:

### 1. Set Environment ke Production

Edit `.env`:
```env
APP_ENV=production
APP_DEBUG=false
```

### 2. Optimize Laravel

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Build Production Assets

```bash
npm run build
```

### 4. Set Permissions (Linux/Mac)

```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

## Update Aplikasi

Jika ada update di repository:

```bash
# Pull update
git pull origin main

# Update dependencies
composer install
npm install

# Run migrations (jika ada)
php artisan migrate

# Clear cache
php artisan config:clear
php artisan cache:clear

# Build assets
npm run build
```

## Uninstall

Untuk menghapus aplikasi:

```bash
# Drop database
mysql -u root -p -e "DROP DATABASE fourlink"

# Hapus folder project
cd ..
rm -rf linktreev2
```

## Support

Jika mengalami masalah, pastikan:
1. PHP version >= 8.1
2. Semua PHP extensions terinstall (mbstring, pdo_mysql, etc.)
3. MySQL service berjalan
4. Port 8000 tidak digunakan aplikasi lain

---

**Happy Coding! 🚀**
