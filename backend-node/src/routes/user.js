import express from 'express';
import UserModel from '../models/UserModel.js';

const router = express.Router();

/**
 * GET /api/user
 * Get current user information
 */
router.get('/', async (req, res) => {
  try {
    // Auto-login check
    await UserModel.autoLogin(req);
    
    const userInfo = UserModel.validateSession(req);
    
    if (userInfo.isLoggedIn) {
      res.json({
        isLoggedIn: true,
        username: userInfo.username,
        type: userInfo.type,
        avatar: UserModel.getCurrentAvatarSVG(userInfo.username)
      });
    } else {
      res.json({
        isLoggedIn: false,
        username: '',
        type: 0,
        avatar: ''
      });
    }
    
  } catch (error) {
    console.error('Error fetching user info:', error);
    res.status(500).json({
      error: 'Internal server error',
      message: process.env.APP_DEBUG === 'true' ? error.message : 'Failed to fetch user info'
    });
  }
});

/**
 * POST /api/user/login
 * User login endpoint (placeholder)
 */
router.post('/login', async (req, res) => {
  try {
    const { username, password } = req.body;
    
    if (!username || !password) {
      return res.status(400).json({ error: 'Username and password are required' });
    }
    
    // TODO: Implement actual authentication logic
    // This is a placeholder for the login functionality
    
    res.status(501).json({ error: 'Login functionality not yet implemented' });
    
  } catch (error) {
    console.error('Error during login:', error);
    res.status(500).json({
      error: 'Internal server error',
      message: process.env.APP_DEBUG === 'true' ? error.message : 'Login failed'
    });
  }
});

/**
 * POST /api/user/logout
 * User logout endpoint
 */
router.post('/logout', (req, res) => {
  try {
    req.session.destroy((err) => {
      if (err) {
        console.error('Error destroying session:', err);
        return res.status(500).json({ error: 'Failed to logout' });
      }
      
      res.clearCookie('connect.sid'); // Default session cookie name
      res.json({ message: 'Logged out successfully' });
    });
    
  } catch (error) {
    console.error('Error during logout:', error);
    res.status(500).json({
      error: 'Internal server error',
      message: process.env.APP_DEBUG === 'true' ? error.message : 'Logout failed'
    });
  }
});

export default router;