# 🚀 Quick Start - Hero Battle Arena

## Pași pentru a porni totul (în ordine):

### 1. Pornește MySQL cu Docker
```bash
cd /Users/alexandru.besleaga/playground/symfony/sf8-playground
docker compose up -d
```

**Verifică că rulează:**
```bash
docker compose ps
```
Ar trebui să vezi `database` cu status `Up (healthy)`

**Dacă nu rulează, așteaptă 10-15 secunde:**
```bash
sleep 15
docker compose ps
```

---

### 2. Pornește serverul Symfony
```bash
# Opțiunea 1: Symfony CLI (recomandat)
symfony server:start

# Opțiunea 2: PHP built-in server
php -S localhost:8000 -t public
```

---

### 3. Verifică că totul funcționează

**Testează API-ul:**
```bash
curl "http://localhost:8000/api/hero/create?name=TestHero"
```

**Sau deschide în browser:**
- Home: http://localhost:8000/
- Create Hero: http://localhost:8000/create-hero
- Battle: http://localhost:8000/battle
- API: http://localhost:8000/api/hero/create?name=Warrior

---

## ✅ Checklist rapid:

- [ ] `docker compose up -d` - MySQL pornit
- [ ] `docker compose ps` - verifică că e "healthy"
- [ ] `symfony server:start` sau `php -S localhost:8000 -t public` - server pornit
- [ ] Deschide http://localhost:8000 în browser

---

## 🔧 Dacă ceva nu merge:

### MySQL nu pornește:
```bash
docker compose logs database
docker compose restart database
```

### Port 3307 ocupat:
```bash
# Verifică ce folosește portul
lsof -i :3307

# Sau schimbă portul în compose.yaml la 3308
```

### Serverul nu pornește:
```bash
# Verifică dacă portul 8000 e ocupat
lsof -i :8000

# Sau folosește alt port
php -S localhost:8001 -t public
```

### Eroare "Access denied" la MySQL:
```bash
# Verifică că containerul rulează
docker compose ps

# Așteaptă MySQL să pornească complet (10-15 secunde)
sleep 15
```

---

## 📝 Comenzi utile:

```bash
# Oprește tot
docker compose stop
# (oprește serverul cu Ctrl+C)

# Restart MySQL
docker compose restart database

# Vezi log-urile MySQL
docker compose logs -f database

# Verifică rutele
php bin/console debug:router

# Rulează testele
php bin/console phpunit
```

---

## 🎯 Pentru interviu - Quick Commands:

```bash
# 1. Pornește tot (copy-paste asta):
cd /Users/alexandru.besleaga/playground/symfony/sf8-playground && docker compose up -d && sleep 10 && symfony server:start -d

# 2. Verifică:
docker compose ps && curl http://localhost:8000/api/hero/create?name=Test

# 3. Oprește tot:
docker compose stop && symfony server:stop
```

---

## 📚 Structura proiectului:

```
sf8-playground/
├── src/
│   ├── Controller/
│   │   ├── HeroController.php      # API endpoint
│   │   └── GameController.php      # Web interface
│   ├── Entity/
│   │   ├── Hero.php                # Hero entity
│   │   └── Spell.php               # Spell entity
│   ├── Service/
│   │   ├── HeroFactory.php         # Creează eroi random
│   │   ├── BattleService.php       # Logica de bătălie
│   │   └── DamageCalculatorService.php
│   └── Repository/
│       └── HeroRepository.php      # Queries pentru eroi
├── tests/                          # Toate testele TDD
├── templates/game/                 # Interfața web
└── compose.yaml                    # Docker MySQL
```

---

## 🎮 Endpoints disponibile:

### API:
- `POST /api/hero/create` - Creează erou (JSON: `{"name": "Hero"}`)
- `GET /api/hero/create?name=Hero` - Creează erou (GET)

### Web:
- `GET /` - Home page
- `GET /create-hero` - Formular creare erou
- `GET /battle` - Interfață bătălie
- `GET /hero/{id}` - Detalii erou

---

**Succes la interviu! 🚀**

