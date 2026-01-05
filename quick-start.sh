#!/bin/bash

# Quick Start Script pentru Hero Battle Arena
# Rulează: ./quick-start.sh

echo "🚀 Starting Hero Battle Arena..."
echo ""

# Navighează la directorul proiectului
cd "$(dirname "$0")"

# 1. Pornește MySQL
echo "📦 Starting MySQL..."
docker compose up -d

# Așteaptă MySQL să pornească
echo "⏳ Waiting for MySQL to be ready..."
sleep 15

# Verifică statusul
if docker compose ps | grep -q "healthy"; then
    echo "✅ MySQL is ready!"
else
    echo "⚠️  MySQL might still be starting. Check with: docker compose ps"
fi

echo ""
echo "🌐 Starting Symfony server..."
echo ""

# 2. Pornește serverul Symfony
if command -v symfony &> /dev/null; then
    echo "Using Symfony CLI..."
    symfony server:start -d
    echo "✅ Server started at http://localhost:8000"
else
    echo "Using PHP built-in server..."
    php -S localhost:8000 -t public > /dev/null 2>&1 &
    echo "✅ Server started at http://localhost:8000"
    echo "⚠️  To stop: kill the PHP process or Ctrl+C"
fi

echo ""
echo "🎉 Everything is ready!"
echo ""
echo "📋 Quick links:"
echo "   - Home: http://localhost:8000/"
echo "   - API: http://localhost:8000/api/hero/create?name=TestHero"
echo "   - Create Hero: http://localhost:8000/create-hero"
echo "   - Battle: http://localhost:8000/battle"
echo ""
echo "🛑 To stop everything:"
echo "   docker compose stop"
echo "   symfony server:stop  (or kill PHP process)"
echo ""

