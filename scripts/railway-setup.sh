#!/bin/bash

# Script untuk setup Railway deployment
# Usage: bash scripts/railway-setup.sh

echo "=========================================="
echo "  Railway.app Setup Script"
echo "=========================================="
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Check if Railway CLI is installed
if ! command -v railway &> /dev/null; then
    echo -e "${YELLOW}Railway CLI not found. Installing...${NC}"
    npm install -g @railway/cli
    echo ""
fi

# Check Railway CLI version
RAILWAY_VERSION=$(railway --version 2>/dev/null || echo "not installed")
echo -e "${GREEN}Railway CLI:${NC} $RAILWAY_VERSION"
echo ""

# Login to Railway
echo -e "${BLUE}Step 1: Login to Railway${NC}"
echo "This will open your browser for authentication..."
railway login
echo ""

# Create or link project
echo -e "${BLUE}Step 2: Create/Link Project${NC}"
echo "Choose an option:"
echo "1) Create new project"
echo "2) Link existing project"
read -p "Enter choice (1 or 2): " choice

if [ "$choice" == "1" ]; then
    echo "Creating new project..."
    railway init
elif [ "$choice" == "2" ]; then
    echo "Linking existing project..."
    railway link
else
    echo -e "${RED}Invalid choice${NC}"
    exit 1
fi
echo ""

# Add MySQL database
echo -e "${BLUE}Step 3: Add MySQL Database${NC}"
read -p "Do you want to add MySQL database? (y/n): " add_db

if [ "$add_db" == "y" ]; then
    echo "Adding MySQL..."
    railway add mysql
    echo -e "${GREEN}✓ MySQL added${NC}"
else
    echo "Skipping MySQL setup"
fi
echo ""

# Generate APP_KEY
echo -e "${BLUE}Step 4: Generate APP_KEY${NC}"
if [ -f "artisan" ]; then
    APP_KEY=$(php artisan key:generate --show)
    echo "Generated APP_KEY: $APP_KEY"
    railway variables set APP_KEY="$APP_KEY"
    echo -e "${GREEN}✓ APP_KEY set${NC}"
else
    echo -e "${RED}artisan not found. Please generate APP_KEY manually.${NC}"
fi
echo ""

# Set environment variables
echo -e "${BLUE}Step 5: Set Environment Variables${NC}"
echo "Setting production variables..."

railway variables set APP_ENV=production
railway variables set APP_DEBUG=false
railway variables set APP_LOCALE=id
railway variables set APP_FALLBACK_LOCALE=id
railway variables set LOG_LEVEL=error
railway variables set SESSION_DRIVER=database
railway variables set CACHE_STORE=database
railway variables set QUEUE_CONNECTION=database

echo -e "${GREEN}✓ Environment variables set${NC}"
echo ""

# Deploy
echo -e "${BLUE}Step 6: Deploy Application${NC}"
read -p "Do you want to deploy now? (y/n): " deploy_now

if [ "$deploy_now" == "y" ]; then
    echo "Deploying..."
    railway up
    echo -e "${GREEN}✓ Deployment started${NC}"
    echo ""
    echo "Monitor deployment:"
    echo "  railway logs --follow"
else
    echo "Skipping deployment"
    echo ""
    echo "To deploy later, run:"
    echo "  railway up"
fi
echo ""

# Get domain
echo -e "${BLUE}Step 7: Get Domain${NC}"
echo "Getting Railway domain..."
DOMAIN=$(railway domain 2>/dev/null || echo "not set")

if [ "$DOMAIN" != "not set" ]; then
    echo -e "${GREEN}Your app will be available at:${NC}"
    echo "  https://$DOMAIN"
    echo ""
    echo "Update APP_URL:"
    railway variables set APP_URL="https://$DOMAIN"
else
    echo -e "${YELLOW}Domain not generated yet.${NC}"
    echo "Generate domain in Railway dashboard:"
    echo "  Settings > Domains > Generate Domain"
fi
echo ""

# Summary
echo "=========================================="
echo "  Setup Complete!"
echo "=========================================="
echo ""
echo "Next steps:"
echo "1. Wait for deployment to complete"
echo "2. Generate domain in Railway dashboard"
echo "3. Update APP_URL with your domain"
echo "4. Access your app:"
echo "   - Frontend: https://your-domain.railway.app"
echo "   - Admin: https://your-domain.railway.app/admin"
echo ""
echo "Default credentials:"
echo "   Email: admin@example.com"
echo "   Password: password"
echo ""
echo "⚠️  Remember to change default password!"
echo ""
echo "Useful commands:"
echo "  railway logs          - View logs"
echo "  railway logs --follow - Follow logs"
echo "  railway open          - Open dashboard"
echo "  railway status        - Check status"
echo "  railway variables     - List variables"
echo ""
echo "Documentation: docs/RAILWAY_DEPLOYMENT.md"
echo ""
