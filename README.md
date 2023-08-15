STACK yang Digunakan :

- Laravel 9
- Bootstrap
- Guzzle (For Seeder image from url)

## Installation
1. Clone Repo
   ```console
   git clone https://github.com/yohanes59/tugas-uts
   ```
2. Buat file .env dengan perintah
   ```console
   cp .env.example .env
   ```
3. sesuaikan nama database pada file .env pada bagian DB_DATABASE sesuai dengan nama pada database mysql anda
4. Run
   ```console
   composer install
   ```
5. buat kunci aplikasi 
   ```console
   php artisan key:generate
   ```
6. migrate tabel dan isi dengan data seeder
   ```console
   php artisan migrate:fresh --seed
   ```
7. koneksikan storage ke folder public
   ```console
   php artisan storage:link
   ```
8. Run server
   ```console
   php artisan serve
   ```
9.  Login with this credential

    - If you want to use admin role (can dashboard access):

        Email: 
        ```
        admin@gmail.com
        ```
        Password: 
        ```
        admin123
        ```
    - If you want to use cashier role (can't dashboard access):

        Email: 
        ```
        kasir@gmail.com
        ```
        Password: 
        ```
        kasir123
        ```
