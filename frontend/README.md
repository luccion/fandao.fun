# Fandao.fun Frontend

React + TypeScript frontend for the fandao.fun discussion platform.

## Development Setup

1. Install dependencies:
```bash
cd frontend
npm install
```

2. Configure environment variables:
```bash
cp .env.example .env
# Edit .env with your API base URL
```

3. Start development server:
```bash
npm run dev
```

## Build for Production

```bash
npm run build
```

## API Integration

The frontend communicates with the PHP backend through REST API endpoints:

- `GET /api/discussions` - Get discussion threads
- `GET /api/categories` - Get discussion categories  
- `GET /api/user` - Get current user info
- `GET /api/health` - Health check

## Deployment

### Vercel

The project is configured for automatic deployment to Vercel via GitHub Actions.

Required secrets:
- `VERCEL_TOKEN` - Vercel deployment token
- `VERCEL_ORG_ID` - Vercel organization ID
- `VERCEL_PROJECT_ID` - Vercel project ID

### Environment Variables

Production environment variables:
- `VITE_API_BASE_URL` - Base URL for the PHP API backend
- `VITE_APP_NAME` - Application name
- `VITE_APP_VERSION` - Application version

## Architecture

- **Frontend**: React 18 + TypeScript + Vite
- **Styling**: Bootstrap 4 + Font Awesome
- **HTTP Client**: Axios
- **Backend API**: PHP (existing codebase)

## Migration Status

✅ Basic React setup with TypeScript
✅ API service layer
✅ Discussion list component
✅ Category navigation
✅ Bootstrap styling integration
✅ GitHub Actions CI/CD pipeline
✅ Vercel deployment configuration

## Next Steps

- [ ] User authentication integration
- [ ] Thread/reply detail views
- [ ] Post creation/editing forms
- [ ] Real-time updates
- [ ] Mobile responsiveness improvements
- [ ] Progressive Web App features
