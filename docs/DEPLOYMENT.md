# Fandao.fun CI/CD Pipeline & React Migration

This document describes the complete setup for migrating fandao.fun from jQuery to React with a modern CI/CD pipeline.

## 📋 Overview

The project has been restructured to support:
- **React frontend** (TypeScript + Vite) for modern UI
- **PHP backend** serving JSON API endpoints
- **GitHub Actions** CI/CD pipeline
- **Vercel deployment** for static hosting

## 🏗️ Architecture

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   React App     │    │   PHP Backend   │    │   Database      │
│  (Frontend)     │◄──►│   (API Server)  │◄──►│   (MySQL)       │
│                 │    │                 │    │                 │
│ • TypeScript    │    │ • REST API      │    │ • Existing      │
│ • Vite          │    │ • CORS enabled  │    │   Schema        │
│ • Bootstrap     │    │ • JSON responses│    │                 │
└─────────────────┘    └─────────────────┘    └─────────────────┘
         │                        │
         │                        │
    ┌─────────────────┐    ┌─────────────────┐
    │     Vercel      │    │  Production     │
    │  (Frontend)     │    │   Server        │
    │                 │    │  (Backend)      │
    └─────────────────┘    └─────────────────┘
```

## 🚀 Getting Started

### Prerequisites
- Node.js 18+ (for frontend development)
- PHP 7.4+ (existing backend)
- Composer (for PHP dependencies)

### Development Setup

1. **Clone and setup:**
```bash
git clone https://github.com/luccion/fandao.fun.git
cd fandao.fun
```

2. **Backend setup:**
```bash
composer install
cp .env.example .env
# Edit .env with your database credentials
```

3. **Frontend setup:**
```bash
cd frontend
npm install
cp .env.example .env
# Edit .env with your API base URL
npm run dev
```

The React app will be available at `http://localhost:5173`

## 📁 Project Structure

```
fandao.fun/
├── api/                    # 🆕 New API endpoints
│   ├── index.php           # API router
│   └── endpoints/          # Individual API handlers
│       ├── discussions.php
│       ├── categories.php
│       └── user.php
├── frontend/               # 🆕 React application
│   ├── src/
│   │   ├── components/     # React components
│   │   ├── services/       # API service layer
│   │   └── ...
│   ├── package.json
│   └── vite.config.ts
├── .github/workflows/      # 🆕 CI/CD pipeline
│   └── ci-cd.yml
├── view/                   # 📦 Existing PHP templates
├── *.php                   # 📦 Existing PHP backend
└── vercel.json             # 🆕 Deployment config
```

## 🔄 CI/CD Pipeline

The GitHub Actions workflow (`.github/workflows/ci-cd.yml`) provides:

### Build & Test Stage
- ✅ Node.js setup and dependency caching
- ✅ ESLint code quality checks
- ✅ TypeScript compilation
- ✅ Production build generation
- ✅ Build artifact storage

### Deployment Stage
- ✅ Automatic deployment to Vercel on main branch
- ✅ Environment variable injection
- ✅ Error handling and logging
- ✅ Deployment status reporting

### Triggering the Pipeline
```bash
git push origin main        # Triggers full build + deploy
git push origin develop     # Triggers build + test only
```

## 🌐 API Endpoints

The new API layer provides:

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/health` | GET | System health check |
| `/api/categories` | GET | Discussion categories |
| `/api/discussions` | GET | Discussion threads (paginated) |
| `/api/user` | GET | Current user info |

### Example API Usage
```javascript
// Get discussions for category 1, page 1
GET /api/discussions?cat=1&page=1&limit=20

// Response format
{
  "discussions": [...],
  "pagination": {
    "currentPage": 1,
    "totalPages": 5,
    "totalCount": 100,
    "limit": 20
  },
  "category": {...}
}
```

## 🔧 Environment Variables

### Frontend Environment (.env)
```bash
VITE_API_BASE_URL=http://localhost/fandao.fun
VITE_APP_NAME=饭岛
VITE_APP_VERSION=0.8.0
```

### GitHub Secrets (for deployment)
```bash
VERCEL_TOKEN=your_vercel_token
VERCEL_ORG_ID=your_org_id
VERCEL_PROJECT_ID=your_project_id
VITE_API_BASE_URL=https://your-production-api.com
```

## 📦 Deployment

### Automatic Deployment
- Push to `main` branch triggers automatic deployment
- Frontend deploys to Vercel
- Backend remains on existing hosting

### Manual Deployment

**Frontend (Vercel):**
```bash
cd frontend
npm run build
vercel --prod
```

**Backend:**
Upload PHP files to your existing hosting provider.

## ⚡ Performance & Features

### React Frontend Benefits
- ⚡ **Fast**: Vite dev server with HMR
- 🎯 **Type-safe**: Full TypeScript support
- 📱 **Responsive**: Bootstrap 4 integration
- 🔄 **Real-time**: Ready for WebSocket integration
- 🎨 **Consistent**: Matches existing design system

### CI/CD Benefits
- 🚀 **Automated**: Zero-manual deployment process
- 🛡️ **Quality**: Automated linting and type checking
- 📊 **Monitoring**: Build status and deployment logs
- 🔒 **Secure**: Environment variable management
- ⚡ **Fast**: Optimized build and deployment pipeline

## 🧪 Testing

### Running Tests
```bash
cd frontend
npm run lint           # ESLint checks
npm run build          # Production build test
npm run preview        # Preview production build
```

### API Testing
```bash
# Test API endpoints (requires running backend)
curl http://localhost/fandao.fun/api/health
curl http://localhost/fandao.fun/api/categories
```

## 📈 Migration Progress

### ✅ Completed
- [x] React + TypeScript setup with Vite
- [x] API endpoint architecture
- [x] Discussion list component migration
- [x] Category navigation
- [x] GitHub Actions CI/CD pipeline
- [x] Vercel deployment configuration
- [x] Environment variable management
- [x] Error handling and logging

### 🔄 In Progress
- [ ] User authentication integration
- [ ] Thread detail views
- [ ] Post creation/editing forms

### 📋 Future Enhancements
- [ ] Real-time updates (WebSocket)
- [ ] Progressive Web App features
- [ ] Mobile app (React Native)
- [ ] Advanced search functionality
- [ ] User management dashboard

## 🆘 Troubleshooting

### Common Issues

**Frontend build fails:**
```bash
cd frontend
rm -rf node_modules package-lock.json
npm install
npm run build
```

**API CORS errors:**
- Check that `Access-Control-Allow-Origin` headers are set in API endpoints
- Verify `VITE_API_BASE_URL` in frontend `.env`

**Deployment fails:**
- Verify GitHub secrets are configured
- Check Vercel project settings
- Review GitHub Actions logs

## 📞 Support

For issues and questions:
1. Check the GitHub Actions logs
2. Review API endpoint responses
3. Verify environment variable configuration
4. Check browser developer console for errors

---

**Ready to deploy!** 🚀 Push to the main branch to trigger the complete CI/CD pipeline.