# 🗄️ Conectare la Baza de Date

## Configurație actuală

- **Host:** `127.0.0.1`
- **Port:** `3307` (3306 este ocupat de MySQL local)
- **Database:** `app`
- **User:** `app`
- **Password:** `!ChangeMe!`

## Metode de conectare

### 1. MySQL CLI prin Docker (Recomandat)

```bash
# Conectare directă prin Docker
docker compose exec database mysql -u app -p

# Sau fără parolă în command (mai rapid)
docker compose exec database mysql -u app -p'!ChangeMe!'
```

**După conectare, poți rula:**
```sql
-- Vezi toate bazele de date
SHOW DATABASES;

-- Folosește baza de date app
USE app;

-- Vezi toate tabelele
SHOW TABLES;

-- Vezi structura unui tabel
DESCRIBE example_entities;

-- Query-uri
SELECT * FROM example_entities;
```

### 2. MySQL CLI direct (dacă ai MySQL client instalat)

```bash
mysql -h 127.0.0.1 -P 3307 -u app -p
# Parola: !ChangeMe!
```

### 3. Symfony Console (pentru verificare)

```bash
# Verifică conexiunea
php bin/console doctrine:schema:validate

# Vezi toate entitățile
php bin/console doctrine:mapping:info

# Rulează query-uri SQL direct
php bin/console dbal:run-sql "SELECT * FROM example_entities"
```

### 4. Client GUI (MySQL Workbench, DBeaver, TablePlus, etc.)

**Configurare:**
- **Connection Type:** MySQL
- **Host:** `127.0.0.1`
- **Port:** `3307`
- **Database:** `app`
- **Username:** `app`
- **Password:** `!ChangeMe!`

## Comenzi utile

### Verifică că containerul rulează
```bash
docker compose ps
```

### Vezi log-urile MySQL
```bash
docker compose logs database
```

### Restart MySQL
```bash
docker compose restart database
```

### Accesează MySQL și rulează query
```bash
docker compose exec database mysql -u app -p'!ChangeMe!' app -e "SHOW TABLES;"
```

### Export database
```bash
docker compose exec database mysqldump -u app -p'!ChangeMe!' app > backup.sql
```

### Import database
```bash
docker compose exec -T database mysql -u app -p'!ChangeMe!' app < backup.sql
```

## Exemple de query-uri

### Vezi toate tabelele
```sql
SHOW TABLES;
```

### Vezi structura unui tabel
```sql
DESCRIBE example_entities;
```

### Select toate înregistrările
```sql
SELECT * FROM example_entities;
```

### Count înregistrări
```sql
SELECT COUNT(*) FROM example_entities;
```

### Vezi migrațiile aplicate
```sql
SELECT * FROM doctrine_migration_versions;
```

## Troubleshooting

### ❌ "Access denied"
**Cauză:** Parola greșită sau user-ul nu există.

**Soluție:**
```bash
# Verifică că containerul rulează
docker compose ps

# Verifică log-urile
docker compose logs database
```

### ❌ "Connection refused"
**Cauză:** Containerul nu rulează sau portul este greșit.

**Soluție:**
```bash
# Pornește containerul
docker compose up -d

# Verifică portul
docker compose ps
```

### ❌ "Unknown database 'app'"
**Cauză:** Baza de date nu există.

**Soluție:**
```bash
# Rulează migrațiile
php bin/console doctrine:migrations:migrate
```

## Quick Test

Testează conexiunea rapid:
```bash
docker compose exec database mysql -u app -p'!ChangeMe!' app -e "SELECT 1 as test;"
```

Dacă vezi `test: 1`, conexiunea funcționează! ✅

