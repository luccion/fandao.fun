#!/bin/bash

# Build script for fandao.fun
# This script handles both backend and frontend builds

set -e

echo "🚀 Building fandao.fun..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
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

# Check if we're in the right directory
if [ ! -f "composer.json" ]; then
    print_error "Please run this script from the project root directory"
    exit 1
fi

# Backend build
print_status "Building backend..."

# Install PHP dependencies
if [ -f "composer.json" ]; then
    print_status "Installing PHP dependencies..."
    composer install --no-dev --optimize-autoloader
    print_success "PHP dependencies installed"
else
    print_warning "No composer.json found, skipping PHP dependencies"
fi

# Frontend build
print_status "Building frontend..."

cd frontend

# Install Node.js dependencies
if [ -f "package.json" ]; then
    print_status "Installing Node.js dependencies..."
    npm ci
    print_success "Node.js dependencies installed"
    
    # Lint the code
    print_status "Running ESLint..."
    npm run lint || print_warning "ESLint found some issues"
    
    # Build the frontend
    print_status "Building React application..."
    npm run build
    print_success "Frontend built successfully"
else
    print_error "No package.json found in frontend directory"
    exit 1
fi

cd ..

# Create production-ready directory structure info
print_status "Creating deployment info..."
cat > deployment-info.json << EOF
{
    "buildTime": "$(date -u +"%Y-%m-%dT%H:%M:%SZ")",
    "version": "$(git describe --tags --always --dirty 2>/dev/null || echo 'unknown')",
    "commit": "$(git rev-parse HEAD 2>/dev/null || echo 'unknown')",
    "structure": {
        "backend": "backend/",
        "frontend": "frontend/dist/",
        "api": "backend/src/Api/",
        "docs": "docs/"
    }
}
EOF

print_success "Build completed successfully! 🎉"
print_status "Deployment info saved to deployment-info.json"

# Show build summary
echo ""
echo "📊 Build Summary:"
echo "- Backend: ✅ PHP dependencies installed"
echo "- Frontend: ✅ React app built to frontend/dist/"
echo "- Structure: ✅ Modern directory layout"
echo "- Ready for deployment! 🚀"