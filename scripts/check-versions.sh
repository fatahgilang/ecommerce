#!/bin/bash

# Script untuk mengecek versi semua dependency
# Usage: bash scripts/check-versions.sh

echo "=========================================="
echo "  E-Commerce Version Check"
echo "=========================================="
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to check version
check_version() {
    local name=$1
    local current=$2
    local required=$3
    local recommended=$4
    
    echo -e "${GREEN}$name${NC}"
    echo "  Current: $current"
    echo "  Required: $required"
    echo "  Recommended: $recommended"
    echo ""
}

# Check PHP
echo "Checking PHP..."
PHP_VERSION=$(php -v | head -n 1 | cut -d " " -f 2 | cut -d "-" -f 1)
check_version "PHP" "$PHP_VERSION" "8.2.0" "8.2.x or 8.3.x"

if [[ "$PHP_VERSION" == 8.5* ]]; then
    echo -e "${YELLOW}⚠️  Warning: PHP 8.5 detected. Use PHP 8.2 or 8.3 for production.${NC}"
    echo ""
fi

# Check Node.js
echo "Checking Node.js..."
NODE_VERSION=$(node -v | cut -d "v" -f 2)
check_version "Node.js" "$NODE_VERSION" "20.0.0" "20.x LTS or 22.x LTS"

# Check NPM
echo "Checking NPM..."
NPM_VERSION=$(npm -v)
check_version "NPM" "$NPM_VERSION" "10.0.0" "10.x"

# Check Composer
echo "Checking Composer..."
COMPOSER_VERSION=$(composer -V | cut -d " " -f 3)
check_version "Composer" "$COMPOSER_VERSION" "2.5.0" "2.9.x"

# Check MySQL
echo "Checking MySQL..."
if command -v mysql &> /dev/null; then
    MYSQL_VERSION=$(mysql --version | awk '{print $5}' | cut -d "," -f 1)
    check_version "MySQL" "$MYSQL_VERSION" "8.0.0" "8.0.27+"
else
    echo -e "${RED}MySQL not found in PATH${NC}"
    echo ""
fi

# Check Laravel
echo "Checking Laravel..."
if [ -f "artisan" ]; then
    LARAVEL_VERSION=$(php artisan --version | cut -d " " -f 3)
    check_version "Laravel" "$LARAVEL_VERSION" "12.0.0" "12.26.x"
else
    echo -e "${RED}Laravel not installed (artisan not found)${NC}"
    echo ""
fi

# Check PHP Extensions
echo "Checking PHP Extensions..."
REQUIRED_EXTENSIONS=("pdo" "pdo_mysql" "mbstring" "xml" "curl" "zip" "gd")
MISSING_EXTENSIONS=()

for ext in "${REQUIRED_EXTENSIONS[@]}"; do
    if php -m | grep -q "^$ext$"; then
        echo -e "  ${GREEN}✓${NC} $ext"
    else
        echo -e "  ${RED}✗${NC} $ext"
        MISSING_EXTENSIONS+=("$ext")
    fi
done
echo ""

# Check if .env exists
echo "Checking Configuration..."
if [ -f ".env" ]; then
    echo -e "  ${GREEN}✓${NC} .env file exists"
else
    echo -e "  ${RED}✗${NC} .env file missing"
    echo "  Run: cp .env.example .env"
fi

# Check if vendor exists
if [ -d "vendor" ]; then
    echo -e "  ${GREEN}✓${NC} Composer dependencies installed"
else
    echo -e "  ${RED}✗${NC} Composer dependencies not installed"
    echo "  Run: composer install"
fi

# Check if node_modules exists
if [ -d "node_modules" ]; then
    echo -e "  ${GREEN}✓${NC} NPM dependencies installed"
else
    echo -e "  ${RED}✗${NC} NPM dependencies not installed"
    echo "  Run: npm install"
fi

# Check if storage is linked
if [ -L "public/storage" ]; then
    echo -e "  ${GREEN}✓${NC} Storage linked"
else
    echo -e "  ${RED}✗${NC} Storage not linked"
    echo "  Run: php artisan storage:link"
fi

echo ""

# Summary
echo "=========================================="
echo "  Summary"
echo "=========================================="

if [ ${#MISSING_EXTENSIONS[@]} -eq 0 ]; then
    echo -e "${GREEN}✓ All required PHP extensions are installed${NC}"
else
    echo -e "${RED}✗ Missing PHP extensions: ${MISSING_EXTENSIONS[*]}${NC}"
fi

if [[ "$PHP_VERSION" == 8.5* ]]; then
    echo -e "${YELLOW}⚠️  PHP 8.5 is for development only${NC}"
fi

echo ""
echo "For detailed version information, see VERSION_LOCK.md"
echo "For deployment guide, see docs/DEPLOYMENT.md"
echo ""
