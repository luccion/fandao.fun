#!/bin/bash

# Development setup script for fandao.fun
# This script sets up the development environment

set -e

echo "🛠️  Setting up fandao.fun development environment..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check requirements
print_status "Checking requirements..."

# Check PHP
if command -v php &> /dev/null; then
    PHP_VERSION=$(php -v | head -n1 | cut -d" " -f2 | cut -d"." -f1,2)
    print_success "PHP $PHP_VERSION found"
else
    print_error "PHP is required but not installed"
    exit 1
fi

# Check Composer
if command -v composer &> /dev/null; then
    print_success "Composer found"
else
    print_error "Composer is required but not installed"
    exit 1
fi

# Check Node.js
if command -v node &> /dev/null; then
    NODE_VERSION=$(node -v)
    print_success "Node.js $NODE_VERSION found"
else
    print_error "Node.js is required but not installed"
    exit 1
fi

# Check npm
if command -v npm &> /dev/null; then
    NPM_VERSION=$(npm -v)
    print_success "npm $NPM_VERSION found"
else
    print_error "npm is required but not installed"
    exit 1
fi

# Setup backend
print_status "Setting up backend..."

# Install PHP dependencies
composer install
print_success "PHP dependencies installed"

# Setup environment file if it doesn't exist
if [ ! -f ".env" ]; then
    if [ -f ".env.example" ]; then
        cp .env.example .env
        print_warning "Created .env from .env.example - please configure your settings"
    else
        print_warning "No .env.example found"
    fi
fi

# Setup frontend
print_status "Setting up frontend..."

cd frontend

# Install Node.js dependencies
npm install
print_success "Node.js dependencies installed"

# Setup frontend environment file
if [ ! -f ".env" ]; then
    if [ -f ".env.example" ]; then
        cp .env.example .env
        print_warning "Created frontend/.env from .env.example - please configure your settings"
    fi
fi

cd ..

# Create storage directories if they don't exist
print_status "Setting up storage directories..."
mkdir -p backend/storage/{logs,cache,uploads}
chmod 755 backend/storage/{logs,cache,uploads}
print_success "Storage directories created"

# Summary
print_success "Development environment setup completed! 🎉"

echo ""
echo "📋 Next steps:"
echo "1. Configure your .env file with database settings"
echo "2. Configure frontend/.env with API URL"
echo "3. Run 'npm run dev' in frontend/ directory for development"
echo "4. Set up your web server to point to the project root"
echo ""
echo "🔧 Development commands:"
echo "- Frontend dev server: cd frontend && npm run dev"
echo "- Frontend build: cd frontend && npm run build"
echo "- Lint frontend: cd frontend && npm run lint"
echo "- Production build: ./scripts/build.sh"
echo ""
echo "📚 Documentation:"
echo "- Main README: ./README.md"
echo "- Deployment guide: ./docs/DEPLOYMENT.md"
echo "- Migration guide: ./docs/MIGRATION_GUIDE.md"
echo "- API examples: ./docs/API_EXAMPLES.md"