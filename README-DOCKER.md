# 🐳 Docker Setup - Hero Battle Arena

## Quick Start

### 1. Pornește MySQL cu Docker

```bash
docker compose up -d
```

### 2. Așteaptă câteva secunde pentru ca MySQL să pornească

```bash
# Verifică statusul
docker compose ps

# Verifică log-urile
docker compose logs database
```

### 3. Creează migrațiile (dacă nu există)

```bash
php bin/console doctrine:migrations:diff
```

### 4. Rulează migrațiile

```bash
php bin/console doctrine:migrations:migrate
```

### 5. Gata! 🎉

Acum poți accesa aplicația:
- Web: http://localhost:8000
- API: http://localhost:8000/api/hero/create

## Configurație

### Variabile de mediu (.env)

```env
# MySQL Configuration
DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=8.0.32&charset=utf8mb4"

# Docker MySQL
MYSQL_VERSION=8.0
MYSQL_DATABASE=app
MYSQL_USER=app
MYSQL_PASSWORD=!ChangeMe!
MYSQL_PORT=3306
```

### Porturi

- **MySQL**: `3306` (host) → `3306` (container)
- **Web Server**: `8000` (dacă folosești `symfony server:start`)

## Comenzi utile

### Verifică statusul containerelor
```bash
docker compose ps
```

### Oprește containerul
```bash
docker compose stop
```

### Oprește și șterge containerul (păstrează datele)
```bash
docker compose down
```

### Oprește și șterge tot (inclusiv datele!)
```bash
docker compose down -v
```

### Restart
```bash
docker compose restart database
```

### Accesează MySQL CLI
```bash
docker compose exec database mysql -u app -p
# Parola: !ChangeMe!
```

### Vezi log-urile
```bash
docker compose logs -f database
```

## Troubleshooting

### ❌ "Access denied for user 'app'"
**Cauză**: Containerul nu este pornit sau MySQL nu este gata.

**Soluție**:
```bash
docker compose up -d
# Așteaptă 10-15 secunde
docker compose ps  # Verifică că este "healthy"
```

### ❌ "Port 3306 is already allocated"
**Cauză**: Ai deja MySQL rulând pe portul 3306.

**Soluție**: Schimbă portul în `compose.yaml`:
```yaml
ports:
  - "3307:3306"  # Folosește 3307 în loc de 3306
```

Apoi actualizează `.env`:
```env
DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3307/app?serverVersion=8.0.32&charset=utf8mb4"
```

### ❌ "Connection refused"
**Cauză**: Containerul nu rulează.

**Soluție**:
```bash
docker compose up -d
docker compose ps  # Verifică statusul
```

### 🔄 Resetare completă

Dacă vrei să ștergi tot și să începi de la zero:

```bash
# Oprește și șterge tot
docker compose down -v

# Pornește din nou
docker compose up -d

# Așteaptă MySQL să pornească (10-15 secunde)
sleep 15

# Rulează migrațiile
php bin/console doctrine:migrations:migrate
```

## Structura Docker

```
compose.yaml
├── database (MySQL 8.0)
│   ├── Port: 3306
│   ├── User: app
│   ├── Password: !ChangeMe!
│   └── Database: app
└── volumes
    └── database_data (persistent storage)
```

## Next Steps

După ce ai pornit MySQL:

1. ✅ Rulează migrațiile: `php bin/console doctrine:migrations:migrate`
2. ✅ Pornește serverul: `symfony server:start` sau `php -S localhost:8000 -t public`
3. ✅ Accesează aplicația: http://localhost:8000
4. ✅ Creează eroi și bătălii! ⚔️

