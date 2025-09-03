import express from 'express';
import discussionsRouter from './discussions.js';
import categoriesRouter from './categories.js';
import userRouter from './user.js';

const router = express.Router();

// Mount route handlers
router.use('/discussions', discussionsRouter);
router.use('/categories', categoriesRouter);
router.use('/user', userRouter);

// Health check endpoint
router.get('/health', (req, res) => {
  res.json({
    status: 'ok',
    timestamp: Math.floor(Date.now() / 1000),
    version: process.env.SITE_VERSION || '0.8.0'
  });
});

export default router;