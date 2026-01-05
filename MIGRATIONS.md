# 📦 Doctrine Migrations - Ghid Complet

## Cum să creezi o nouă migrație

### Pasul 1: Modifică entitatea

Adaugă/modifică proprietăți în entitatea ta (ex: `ExampleEntity.php`):

```php
#[ORM\Column(length: 255, nullable: true)]
private ?string $description = null;

#[ORM\Column(type: 'datetime_immutable', nullable: true)]
private ?\DateTimeImmutable $createdAt = null;
```

### Pasul 2: Generează migrația

```bash
php bin/console doctrine:migrations:diff
```

Această comandă:
- Compară entitățile cu schema bazei de date
- Generează automat o migrație cu diferențele
- Creează un fișier în `migrations/VersionYYYYMMDDHHMMSS.php`

### Pasul 3: Verifică migrația generată

```bash
# Vezi ultima migrație creată
ls -lt migrations/ | head -2

# Deschide fișierul și verifică SQL-ul generat
cat migrations/Version*.php | tail -20
```

### Pasul 4: Rulează migrația

```bash
# Rulează migrația
php bin/console doctrine:migrations:migrate

# Sau pentru o migrație specifică
php bin/console doctrine:migrations:migrate VersionYYYYMMDDHHMMSS
```

---

## Comenzi utile

### Verifică statusul migrațiilor
```bash
php bin/console doctrine:migrations:status
```

### Vezi toate migrațiile disponibile
```bash
php bin/console doctrine:migrations:list
```

### Vezi SQL-ul care va fi executat (fără să ruleze)
```bash
php bin/console doctrine:migrations:migrate --dry-run
```

### Rulează migrația și vezi SQL-ul
```bash
php bin/console doctrine:migrations:migrate --show-sql
```

### Revert ultima migrație
```bash
php bin/console doctrine:migrations:migrate prev
```

### Revert la o versiune specifică
```bash
php bin/console doctrine:migrations:migrate VersionYYYYMMDDHHMMSS
```

### Execută o migrație specifică (up)
```bash
php bin/console doctrine:migrations:execute --up VersionYYYYMMDDHHMMSS
```

### Execută o migrație specifică (down - revert)
```bash
php bin/console doctrine:migrations:execute --down VersionYYYYMMDDHHMMSS
```

---

## Exemplu complet

### 1. Modifică ExampleEntity

```php
// src/Entity/ExampleEntity.php
#[ORM\Column(length: 500, nullable: true)]
private ?string $description = null;
```

### 2. Generează migrația

```bash
php bin/console doctrine:migrations:diff
```

**Output:**
```
Generated new migration class to "/path/migrations/Version20240104180000.php"
```

### 3. Verifică migrația

```bash
cat migrations/Version20240104180000.php
```

Vei vedea ceva de genul:
```php
public function up(Schema $schema): void
{
    $this->addSql('ALTER TABLE example_entities ADD description VARCHAR(500) DEFAULT NULL');
}
```

### 4. Rulează migrația

```bash
php bin/console doctrine:migrations:migrate
```

**Output:**
```
[notice] Migrating up to DoctrineMigrations\Version20240104180000
[notice] finished in 50ms, used 20M memory, 1 migrations executed, 1 sql queries
[OK] Successfully migrated to version: Version20240104180000
```

---

## Creează migrație manuală (avansat)

Dacă vrei să creezi manual o migrație:

```bash
php bin/console doctrine:migrations:generate
```

Apoi editează fișierul generat manual.

---

## Best Practices

1. ✅ **Verifică întotdeauna migrația** înainte să o rulezi
2. ✅ **Folosește `--dry-run`** pentru a vedea ce se va întâmpla
3. ✅ **Backup baza de date** înainte de migrații importante
4. ✅ **Testează migrațiile** pe un environment de development mai întâi
5. ✅ **Nu modifica migrații** care au fost deja rulate în producție

---

## Troubleshooting

### ❌ "No changes detected"
**Cauză:** Entitățile sunt deja sincronizate cu baza de date.

**Soluție:** Modifică entitatea sau șterge manual tabelul și rulează din nou.

### ❌ "Migration already executed"
**Cauză:** Migrația a fost deja rulată.

**Soluție:** 
```bash
# Vezi statusul
php bin/console doctrine:migrations:status

# Dacă vrei să o rulezi din nou, fă revert mai întâi
php bin/console doctrine:migrations:migrate prev
```

### ❌ "Table already exists"
**Cauză:** Tabelul există deja în baza de date.

**Soluție:** 
```bash
# Șterge manual tabelul sau
# Modifică migrația să verifice existența
```

---

## Structura unui fișier de migrație

```php
<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240104180000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add description field to example_entities';
    }

    public function up(Schema $schema): void
    {
        // SQL pentru a aplica migrația
        $this->addSql('ALTER TABLE example_entities ADD description VARCHAR(500) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // SQL pentru a reverta migrația
        $this->addSql('ALTER TABLE example_entities DROP description');
    }
}
```

---

## Quick Reference

```bash
# Workflow complet
1. Modifică entitatea
2. php bin/console doctrine:migrations:diff
3. Verifică migrația generată
4. php bin/console doctrine:migrations:migrate
```

