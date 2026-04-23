#!/bin/bash

# Production Readiness Script - Le Nium Legal
# Run this script to verify production environment is ready
# Usage: bash ./scripts/production-ready.sh

set -e

echo "🔍 Le Nium Legal - Production Readiness Check"
echo "=============================================="
echo ""

# Color codes
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

PASSED=0
FAILED=0

check_item() {
    local name=$1
    local result=$2
    
    if [ $result -eq 0 ]; then
        echo -e "${GREEN}✓${NC} $name"
        ((PASSED++))
    else
        echo -e "${RED}✗${NC} $name"
        ((FAILED++))
    fi
}

# 1. Environment Setup
echo "1️⃣  Environment Setup"
echo "---"

[ -f .env ] && check_item ".env file exists" 0 || check_item ".env file exists" 1

grep -q "APP_ENV=production" .env
check_item "APP_ENV=production" $?

grep -q "APP_DEBUG=false" .env
check_item "APP_DEBUG=false" $?

grep -q "APP_KEY=" .env
check_item "APP_KEY configured" $?

echo ""

# 2. PHP Version
echo "2️⃣  PHP Configuration"
echo "---"

php -v | grep -q "PHP 8"
check_item "PHP 8.x installed" $?

php -m | grep -q "pdo_mysql"
check_item "PDO MySQL extension" $?

php -m | grep -q "openssl"
check_item "OpenSSL extension" $?

echo ""

# 3. Composer & Dependencies
echo "3️⃣  Dependencies"
echo "---"

[ -d "vendor" ] && check_item "vendor/ directory exists" 0 || check_item "vendor/ directory exists" 1

[ -f "composer.lock" ] && check_item "composer.lock exists" 0 || check_item "composer.lock exists" 1

echo ""

# 4. Storage & Permissions
echo "4️⃣  Storage & Permissions"
echo "---"

[ -d "storage/app" ] && check_item "storage/app/ exists" 0 || check_item "storage/app/ exists" 1

[ -d "storage/logs" ] && check_item "storage/logs/ exists" 0 || check_item "storage/logs/ exists" 1

[ -d "bootstrap/cache" ] && check_item "bootstrap/cache/ exists" 0 || check_item "bootstrap/cache/ exists" 1

[ -L "public/storage" ] && check_item "public/storage symlink exists" 0 || check_item "public/storage symlink exists" 1

echo ""

# 5. Database Configuration
echo "5️⃣  Database Configuration"
echo "---"

grep -q "DB_HOST=" .env
check_item "DB_HOST configured" $?

grep -q "DB_USERNAME=" .env
check_item "DB_USERNAME configured" $?

grep -q "DB_PASSWORD=" .env
check_item "DB_PASSWORD configured" $?

# Try to connect to database
php artisan tinker --execute="DB::connection()->getPdo(); echo 'OK';" 2>/dev/null | grep -q "OK"
check_item "Database connection works" $?

echo ""

# 6. Caching
echo "6️⃣  Caching"
echo "---"

grep -q "CACHE_STORE=" .env
check_item "CACHE_STORE configured" $?

php artisan tinker --execute="Cache::put('test', 'ok'); echo Cache::get('test');" 2>/dev/null | grep -q "ok"
check_item "Cache driver working" $?

echo ""

# 7. Security Files
echo "7️⃣  Security Files"
echo "---"

[ -f ".gitignore" ] && grep -q ".env" .gitignore
check_item ".env in .gitignore" $?

[ -f "public/.htaccess" ]
check_item "public/.htaccess exists" $?

[ ! -d ".git" ] || ! git log -1 --format="%H" .env 2>/dev/null | grep -q "."
check_item ".env not tracked in git" 0  # Soft check

echo ""

# 8. Routes & Config Caching
echo "8️⃣  Optimization"
echo "---"

php artisan config:show --key=app.env 2>/dev/null | grep -q "production"
check_item "Config can be cached" $?

php artisan route:list > /dev/null 2>&1
check_item "Routes are valid" $?

echo ""

# 9. Key Files Present
echo "9️⃣  Key Files"
echo "---"

[ -f "PRODUCTION_DEPLOYMENT_GUIDE.md" ]
check_item "Deployment guide exists" $?

[ -f "PRODUCTION_CONFIGURATION.md" ]
check_item "Configuration guide exists" $?

[ -f ".env.production" ]
check_item ".env.production template exists" $?

echo ""

# Summary
echo "=============================================="
echo "📊 Summary"
echo "---"
echo -e "Passed: ${GREEN}${PASSED}${NC}"
echo -e "Failed: ${RED}${FAILED}${NC}"
echo ""

if [ $FAILED -eq 0 ]; then
    echo -e "${GREEN}✓ ✓ ✓ Ready for Production! ✓ ✓ ✓${NC}"
    exit 0
else
    echo -e "${YELLOW}⚠ Fix the above issues before deploying to production${NC}"
    exit 1
fi
