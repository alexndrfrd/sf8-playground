# 📚 Exemple de Componente Symfony

Acest proiect conține exemple de fiecare tip de componentă Symfony pentru referință.

## 📁 Structura

### Controller (`src/Controller/ExampleController.php`)
- Exemplu de controller REST API
- Dependency injection
- Request handling (GET/POST)
- JSON response

**Test:** `tests/Controller/ExampleControllerTest.php`
- Testare cu WebTestCase
- Mock-uri pentru servicii

### Command (`src/Command/ExampleCommand.php`)
- Exemplu de Symfony Console Command
- Input arguments
- Output formatting cu SymfonyStyle

**Test:** `tests/Command/ExampleCommandTest.php`
- Testare cu CommandTester
- Mock-uri pentru servicii

### Service (`src/Service/ExampleService.php`)
- Exemplu de service class
- Business logic
- Folosește Value Objects

**Test:** `tests/Service/ExampleServiceTest.php`
- Unit testing
- Mock-uri pentru dependențe

### DTO (`src/DTO/ExampleDTO.php`)
- Data Transfer Object
- Serializare cu `toArray()`
- Separare între entități și API

**Test:** `tests/DTO/ExampleDTOTest.php`
- Testare structură DTO
- Testare serializare

### Value Object (`src/ValueObject/Damage.php`)
- Value Object imutabil (`readonly`)
- Validare în constructor
- Metode pentru operații

**Test:** `tests/ValueObject/DamageTest.php`
- Testare imutabilitate
- Testare validare
- Testare operații

### Entity (`src/Entity/ExampleEntity.php`)
- Doctrine Entity
- Atribute și getters/setters
- Mapping cu Doctrine ORM

**Test:** `tests/Entity/ExampleEntityTest.php`
- Testare getters/setters
- Testare entitate

### Repository (`src/Repository/ExampleRepository.php`)
- Doctrine Repository
- Query-uri custom
- Extinde ServiceEntityRepository

## 🧪 Testare

Toate componentele au teste asociate care demonstrează:
- ✅ TDD (Test-Driven Development)
- ✅ Mock-uri pentru dependențe
- ✅ Testare unitară și integrare

## 🚀 Utilizare

### Controller
```bash
# GET
curl "http://localhost:8000/api/example/process?data=test"

# POST
curl -X POST http://localhost:8000/api/example/process \
  -H "Content-Type: application/json" \
  -d '{"data": "test"}'
```

### Command
```bash
php bin/console example:process "test data"
```

### Service
```php
$service = new ExampleService();
$result = $service->processData('test');
```

### DTO
```php
$dto = new ExampleDTO();
$dto->input = 'test';
$dto->output = 'TEST (processed)';
$array = $dto->toArray();
```

### Value Object
```php
$damage = new Damage(100);
$total = $damage->add(new Damage(50)); // 150
$reduced = $damage->multiply(0.5); // 50
```

## 📝 Note

- Toate exemplele sunt funcționale și testate
- Fiecare componentă demonstrează best practices
- Testele arată cum să testezi fiecare tip de componentă

