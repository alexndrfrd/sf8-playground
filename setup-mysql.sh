#!/bin/bash

# Script pentru configurarea MySQL în .env

echo "🔧 Configurând MySQL în .env..."

# Comentează PostgreSQL
sed -i.bak 's|^DATABASE_URL="postgresql://|# DATABASE_URL="postgresql://|' .env

# Activează MySQL (decomentează linia MySQL)
sed -i.bak 's|^# DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=8.0.32&charset=utf8mb4"|DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=8.0.32&charset=utf8mb4"|' .env

echo "✅ Configurație MySQL activată!"
echo ""
echo "Următorii pași:"
echo "1. docker compose up -d"
echo "2. php bin/console doctrine:migrations:migrate"

