# 🌐 API Endpoints

## Endpoint-uri disponibile

### 1. Process Data
**Endpoint:** `/api/example/process`  
**Metode:** `GET`, `POST`  
**Controller:** `ExampleController::process()`

#### GET Request
```bash
curl "http://localhost:8000/api/example/process?data=test"
```

**Response:**
```json
{
  "input": "test",
  "output": "TEST (processed)",
  "processedAt": "2024-01-04 18:00:00"
}
```

#### POST Request
```bash
curl -X POST http://localhost:8000/api/example/process \
  -H "Content-Type: application/json" \
  -d '{"data": "hello world"}'
```

**Request Body:**
```json
{
  "data": "hello world"
}
```

**Response:**
```json
{
  "input": "hello world",
  "output": "HELLO WORLD (processed)",
  "processedAt": "2024-01-04 18:00:00"
}
```

**Status Codes:**
- `200 OK` - Succes

---

## 📋 Rute Symfony (interne)

Următoarele rute sunt pentru debugging și development:

- `/_profiler/*` - Symfony Profiler (doar în dev)
- `/_wdt/*` - Web Debug Toolbar (doar în dev)
- `/_error/*` - Error pages

---

## 🎮 Commands disponibile

### Example Command
```bash
php bin/console example:process "test data"
```

**Output:**
```
Example Command
================

Processing data...

 [OK] Processed: test data -> TEST DATA (processed)
```

---

## 📝 Note

- Toate endpoint-urile returnează JSON
- Pentru development, profiler-ul este disponibil la `/_profiler`
- Endpoint-urile sunt definite în `ExampleController`

