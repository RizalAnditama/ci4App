  # Simple CodeIgniter 4 Program 

  ## Cara pakai

  ### Via command line
  - Install Composer 
  ```
    apt install Composer
  ```
  - Arahkan ke cli direktori program 
  - Jalankan migrasi 
  ```
  php spark migrate
  ```
  - Kalau perlu, jalankan juga ```php spark db:seed mahasiswa``` serta ```composer install -vvv```
  - Jalankan program dengan 
  ```
  php spark serve
  ```

  ### Via file zip
  - Download file zip repository ini dan taruh di htdocs
  - Buka phpmyadmin dan import ```mahasiswadb.sql```
  - Buka browser dan masukan url berikut 
  ```http
  localhost/ci4App
  ```
