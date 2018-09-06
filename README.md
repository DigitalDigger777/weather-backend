Deploy backend

1. install dependencies 
```bash
composer install
```

2. prepare database connection
```bash
cp .env.dist .env
```

```bash
vim .env
```
and change database configuration row for example:
```bash
DATABASE_URL=mysql://someuser:pass@127.0.0.1:3306/weather
```

3. create database and fill test data
```bash
./bin/console doctrine:database:create

./bin/console doctrine:schema:update --force

./bin/console doctrine:fixtures:load
```

