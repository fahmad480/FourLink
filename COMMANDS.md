# FourLink - Quick Commands Reference

Daftar perintah yang sering digunakan untuk FourLink project.

## 🚀 Installation Commands

```bash
# 1. Install dependencies
composer install
npm install

# 2. Setup environment
cp .env.example .env
php artisan key:generate

# 3. Configure database di .env, lalu:
php artisan migrate
php artisan db:seed

# 4. Storage link
php artisan storage:link

# 5. Build & run
npm run dev
php artisan serve
```

## 🔧 Development Commands

### Laravel Artisan

```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Refresh database (WARNING: deletes all data!)
php artisan migrate:fresh --seed

# Create new migration
php artisan make:migration create_table_name

# Create new controller
php artisan make:controller ControllerName

# Create new model
php artisan make:model ModelName

# Run seeder
php artisan db:seed
php artisan db:seed --class=SpecificSeeder

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### NPM/Build Commands

```bash
# Development (with hot reload)
npm run dev

# Production build
npm run build

# Watch for changes
npm run watch

# Clear npm cache
npm cache clean --force
rm -rf node_modules
npm install
```

## 🗄️ Database Commands

### MySQL

```bash
# Login to MySQL
mysql -u root -p

# Create database
CREATE DATABASE fourlink;

# Use database
USE fourlink;

# Show tables
SHOW TABLES;

# Drop database (WARNING!)
DROP DATABASE fourlink;
```

### Laravel Database

```bash
# Run specific migration
php artisan migrate --path=/database/migrations/filename.php

# Rollback last migration
php artisan migrate:rollback

# Rollback all migrations
php artisan migrate:reset

# Fresh migration with seed
php artisan migrate:fresh --seed
```

## 👥 User Management (via Tinker)

```bash
# Open Laravel Tinker
php artisan tinker

# Create new user
$user = User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => Hash::make('password')
]);

# Assign role
$user->assignRole('admin');
# or
$user->assignRole('user');

# Check user role
$user->hasRole('admin');

# List all users
User::all();

# Find user by email
User::where('email', 'admin@fourlink.com')->first();

# Delete user
User::find(1)->delete();

# Exit tinker
exit
```

## 🔐 Permission & Role Commands (via Tinker)

```bash
php artisan tinker

# Create new role
Role::create(['name' => 'moderator']);

# Create new permission
Permission::create(['name' => 'edit-posts']);

# Assign permission to role
$role = Role::findByName('moderator');
$role->givePermissionTo('edit-posts');

# List all roles
Role::all();

# List all permissions
Permission::all();
```

## 📦 Storage Commands

```bash
# Create storage link
php artisan storage:link

# Remove storage link (if exists)
# Windows
rmdir public\storage

# Linux/Mac
rm public/storage

# Then recreate
php artisan storage:link
```

## 🧪 Testing Commands

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter TestName

# Run with coverage
php artisan test --coverage
```

## 🐛 Debugging Commands

```bash
# Check Laravel version
php artisan --version

# List all routes
php artisan route:list

# Check environment
php artisan env

# Run queue worker (if using jobs)
php artisan queue:work

# Show failed jobs
php artisan queue:failed
```

## 🔍 Useful Git Commands

```bash
# Check status
git status

# Add all changes
git add .

# Commit changes
git commit -m "Your message"

# Push to repository
git push origin main

# Pull latest changes
git pull origin main

# Create new branch
git checkout -b feature-name

# Switch branch
git checkout branch-name

# Show commit history
git log --oneline
```

## 📊 Performance Commands

```bash
# Optimize autoloader
composer dump-autoload -o

# Cache everything (production)
php artisan optimize

# Clear all optimizations
php artisan optimize:clear
```

## 🔒 Security Commands

```bash
# Generate new APP_KEY
php artisan key:generate

# Clear and regenerate all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## 📝 File Permissions (Linux/Mac)

```bash
# Set storage permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Set ownership (replace www-data with your user)
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

## 🚨 Emergency Commands

```bash
# If stuck with cache issues
php artisan optimize:clear
composer dump-autoload
npm run build

# If database issues
php artisan migrate:fresh --seed

# If permission issues (Linux/Mac)
sudo chmod -R 777 storage
sudo chmod -R 777 bootstrap/cache
# Then set back to 775 for security
```

## 🌐 Server Commands

```bash
# Start Laravel server
php artisan serve

# Start on specific port
php artisan serve --port=8080

# Start on specific host
php artisan serve --host=192.168.1.1

# Start with public access
php artisan serve --host=0.0.0.0
```

## 📤 Deployment Commands (Production)

```bash
# 1. Pull latest code
git pull origin main

# 2. Update dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# 3. Run migrations
php artisan migrate --force

# 4. Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Set permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

## 🔄 Update Commands

```bash
# Update Composer packages
composer update

# Update specific package
composer update spatie/laravel-permission

# Update NPM packages
npm update

# Check outdated packages
composer outdated
npm outdated
```

## 💾 Backup Commands

```bash
# Export database
mysqldump -u root -p fourlink > backup.sql

# Import database
mysql -u root -p fourlink < backup.sql

# Backup files (Linux/Mac)
tar -czf backup.tar.gz storage public
```

---

**Pro Tip:** Bookmark this file for quick access to commonly used commands!
