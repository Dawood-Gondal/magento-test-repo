<p align="center">
<a href="https://www.codetriage.com/magento/magento2"><img src="https://www.codetriage.com/magento/magento2/badges/users.svg" alt="Open Source Helpers" /></a>
<a href="https://gitter.im/magento/magento2?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge"><img src="https://badges.gitter.im/Join%20Chat.svg" alt="Gitter" /></a>
<a href="https://crowdin.com/project/magento-2"><img src="https://d322cqt584bo4o.cloudfront.net/magento-2/localized.svg" alt="Crowdin" /></a><br/>
<a href="https://magento.com/products/magento-open-source">
<img alt="Adobe logo" height="50px" src="https://www.adobe.com/content/dam/cc/icons/Adobe_Corporate_Horizontal_Red_HEX.svg"/>
</a>
</p>

<h1 align="center">Magento Open Source</h1>

Welcome to the Magento Open Source project! Magento Open Source provides everything you need to build a powerful and flexible eCommerce store.

---

# Magento 2 Docker Setup

This project uses **Docker Compose** to provide a complete local Magento 2 development environment.

## Requirements

Before getting started, ensure the following software is installed:

- Docker Engine 24+
- Docker Compose v2+
- Git
- Composer
- Linux/macOS (Windows with WSL2 is also supported)

---

# Clone the Repository

```bash
git clone <repository-url>
cd <project-directory>
```

---

# Configure Local Domain

This project uses the following local domain:

```
magento.local
```

## Linux / macOS

Open the hosts file:

```bash
sudo nano /etc/hosts
```

## Windows

Open the following file as **Administrator**:

```
C:\Windows\System32\drivers\etc\hosts
```

Add the following entry:

```text
127.0.0.1    magento.local
```

Save the file.

Verify it:

```bash
cat /etc/hosts | grep magento.local
```

Expected output:

```text
127.0.0.1    magento.local
```

---

# Start Docker

Start all containers.

```bash
docker compose up -d
```

Verify the containers:

```bash
docker ps
```

Expected services:

- magento-app
- magento-nginx
- magento-db
- magento-redis
- magento-opensearch
- magento-rabbitmq
- magento-mailcatcher

---

# Install Composer Dependencies

Enter the PHP container:

```bash
docker exec -it magento-app bash
```

Install dependencies:

```bash
composer install
```

---

# Create Database (Optional)

> **Note**
>
> The provided `docker-compose.yml` automatically creates the database and user during the first startup.
>
> Only use the following commands if you need to create the database manually.

Connect to MySQL:

```bash
docker exec -it magento-db mysql -uroot -proot
```

Create the database:

```sql
CREATE DATABASE magento
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

Create the Magento user:

```sql
CREATE USER 'magento'@'%' IDENTIFIED BY 'magento';

GRANT ALL PRIVILEGES ON magento.* TO 'magento'@'%';

FLUSH PRIVILEGES;
```

Verify:

```sql
SHOW DATABASES;
```

Exit MySQL:

```sql
exit
```

---

# Install Magento

Inside the PHP container execute:

```bash
bin/magento setup:install \
--base-url=https://magento.local \
--db-host=db \
--db-name=magento \
--db-user=magento \
--db-password=magento \
--admin-firstname=Admin \
--admin-lastname=User \
--admin-email=admin@example.com \
--admin-user=admin \
--admin-password='admin123' \
--language=en_US \
--currency=USD \
--timezone=Asia/Karachi \
--use-rewrites=1 \
--search-engine=opensearch \
--opensearch-host=opensearch \
--opensearch-port=9200
```

---

# Post Installation

Run the following commands:

```bash
bin/magento deploy:mode:set developer

bin/magento cache:flush

bin/magento indexer:reindex
```

---

# Magento Access

## Storefront

```
https://magento.local
```

## Admin Panel

Magento will generate a unique admin URI.

Example:

```
https://magento.local/admin
```

If you need another administrator account:

```bash
bin/magento admin:user:create \
--admin-user=admin2 \
--admin-password='admin123' \
--admin-email=admin2@example.com \
--admin-firstname=Admin \
--admin-lastname=User
```

---

# Database Access

Magento User

```bash
docker exec -it magento-db mysql -umagento -pmagento
```

Root User

```bash
docker exec -it magento-db mysql -uroot -proot
```

---

# Common Magento Commands

Flush Cache

```bash
bin/magento cache:flush
```

Clean Cache

```bash
bin/magento cache:clean
```

Setup Upgrade

```bash
bin/magento setup:upgrade
```

Compile

```bash
bin/magento setup:di:compile
```

Deploy Static Content

```bash
bin/magento setup:static-content:deploy -f
```

Reindex

```bash
bin/magento indexer:reindex
```

Developer Mode

```bash
bin/magento deploy:mode:set developer
```

Show Current Mode

```bash
bin/magento deploy:mode:show
```

---

# Common Docker Commands

Start Containers

```bash
docker compose up -d
```

Stop Containers

```bash
docker compose down
```

Restart Containers

```bash
docker compose restart
```

Rebuild Containers

```bash
docker compose down
docker compose up -d --build
```

View Running Containers

```bash
docker ps
```

View All Containers

```bash
docker ps -a
```

Access PHP Container

```bash
docker exec -it magento-app bash
```

Access Database

```bash
docker exec -it magento-db mysql -umagento -pmagento
```

---

# Logs

Nginx

```bash
docker logs -f magento-nginx
```

PHP

```bash
docker logs -f magento-app
```

MySQL

```bash
docker logs -f magento-db
```

OpenSearch

```bash
docker logs -f magento-opensearch
```

RabbitMQ

```bash
docker logs -f magento-rabbitmq
```

---

# Mailcatcher

Open Mailcatcher in your browser:

```
http://localhost:1080
```

---

# Services

| Service | Host          | Port |
|----------|---------------|------|
| Magento | magento.local | 80 |
| MySQL | localhost     | 3306 |
| Redis | localhost     | 6379 |
| OpenSearch | localhost     | 9200 |
| RabbitMQ | localhost     | 5672 |
| RabbitMQ Management | localhost     | 15672 |
| Mailcatcher | localhost     | 1080 |

---

# Troubleshooting

### Flush Cache

```bash
bin/magento cache:flush
```

### Reindex

```bash
bin/magento indexer:reindex
```

### Restart Docker

```bash
docker compose restart
```

### Rebuild Containers

```bash
docker compose down
docker compose up -d --build
```

### Check Running Containers

```bash
docker ps -a
```

### View MySQL Logs

```bash
docker logs magento-db
```

### View Nginx Logs

```bash
docker logs magento-nginx
```

---

# Project Structure

```
docker/
├── mysql/
├── nginx/
│   ├── certs/
│   └── conf/
docker-compose.yml
app/
pub/
vendor/
var/
```

---

# License

This project is intended for local development and testing purposes.
