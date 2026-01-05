# Docker Setup pentru Hero Battle Arena

## Pornirea bazei de date MySQL

### 1. Pornește containerul MySQL

```bash
docker compose up -d
```

Această comandă va:
- Descărca imaginea MySQL 8.0 (dacă nu există)
- Creează și pornește containerul cu baza de date
- Expune portul 3306 pentru conexiune

### 2. Verifică că containerul rulează

```bash
docker compose ps
```

Ar trebui să vezi containerul `database` cu status `Up`.

### 3. Verifică log-urile (opțional)

```bash
docker compose logs database
```

### 4. Configurează .env

Asigură-te că în fișierul `.env` ai:

```env
DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=8.0.32&charset=utf8mb4"
```

### 5. Rulează migrațiile

```bash
php bin/console doctrine:migrations:migrate
```

## Comenzi utile

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

### Restart container
```bash
docker compose restart database
```

### Accesează MySQL CLI
```bash
docker compose exec database mysql -u app -p
# Parola: !ChangeMe!
```

## Variabile de mediu

Poți personaliza configurația prin variabile de mediu în `.env`:

```env
MYSQL_VERSION=8.0
MYSQL_DATABASE=app
MYSQL_USER=app
MYSQL_PASSWORD=!ChangeMe!
MYSQL_PORT=3306
MYSQL_ROOT_PASSWORD=rootpassword
```

## Troubleshooting

### Eroare: "port is already allocated"
Schimbă portul în `compose.yaml` sau `.env`:
```yaml
ports:
  - "3307:3306"  # în loc de 3306
```

### Eroare: "connection refused"
Verifică că containerul rulează:
```bash
docker compose ps
```

### Resetare completă a bazei de date
```bash
docker compose down -v
docker compose up -d
php bin/console doctrine:migrations:migrate
```

