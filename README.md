# Leave Management App

## First setup

```bash
git clone https://github.com/cikipawyeye/leave-management-be.git
cd leave-management-be
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

## User

| Role          | Email                   | Password |
| ------------- | ----------------------- | -------- |
| Admin         | admin@example.com       | password |
| Verificator   | verificator@example.com | password |
| Ordinary User | user@example.com        | password |
