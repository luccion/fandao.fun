import express from 'express';
import cors from 'cors';
import session from 'express-session';
import { join, dirname } from 'path';
import { fileURLToPath } from 'url';

// Import models and routes from backend-node
import db from '../backend-node/src/config/database.js';
import discussionsRouter from '../backend-node/src/routes/discussions.js';
import categoriesRouter from '../backend-node/src/routes/categories.js';
import userRouter from '../backend-node/src/routes/user.js';

const __dirname = dirname(fileURLToPath(import.meta.url));

const app = express();

// Initialize database connection
let dbConnected = false;
try {
  await db.connect();
  dbConnected = true;
} catch (error) {
  console.error('Database connection failed:', error);
}

// CORS configuration
app.use(cors({
  origin: '*',
  credentials: true,
  methods: ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
  allowedHeaders: ['Content-Type', 'Authorization', 'X-Requested-With']
}));

// Body parsing middleware
app.use(express.json({ limit: '10mb' }));
app.use(express.urlencoded({ extended: true, limit: '10mb' }));

// Session configuration (simplified for serverless)
app.use(session({
  secret: process.env.SESSION_SECRET || 'fandao-secret-key-change-me',
  resave: false,
  saveUninitialized: false,
  cookie: {
    secure: false, // Set to true for HTTPS in production
    httpOnly: true,
    maxAge: 24 * 60 * 60 * 1000 // 24 hours
  }
}));

// API routes
app.use('/discussions', discussionsRouter);
app.use('/categories', categoriesRouter);
app.use('/user', userRouter);

// Health check endpoint
app.get('/health', (req, res) => {
  res.json({
    status: dbConnected ? 'ok' : 'database_error',
    timestamp: Math.floor(Date.now() / 1000),
    version: process.env.SITE_VERSION || '0.8.0'
  });
});

// Error handling middleware
app.use((err, req, res, next) => {
  console.error(err.stack);
  res.status(500).json({
    error: 'Internal server error',
    message: process.env.APP_DEBUG === 'true' ? err.message : 'Something went wrong'
  });
});

// 404 handler
app.use('*', (req, res) => {
  res.status(404).json({ error: 'Endpoint not found' });
});

export default app;