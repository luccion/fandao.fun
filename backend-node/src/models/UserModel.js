import db from '../config/database.js';

class UserModel {
  /**
   * Check if user is logged in
   */
  isLogin(session) {
    return !!(session && session.name && session.name !== '');
  }

  /**
   * Auto login check (placeholder for session validation)
   */
  async autoLogin(req) {
    // This would contain logic to validate session tokens, cookies, etc.
    // For now, just check if session exists
    if (req.session && req.session.userId) {
      try {
        const user = await this.getUserById(req.session.userId);
        if (user) {
          req.session.name = user.name;
          req.session.type = user.type || 0;
          return true;
        }
      } catch (error) {
        console.error('Auto login error:', error);
      }
    }
    return false;
  }

  /**
   * Get user by ID
   */
  async getUserById(userId) {
    const sql = 'SELECT * FROM users WHERE id = ?';
    return await db.getRow(sql, [userId]);
  }

  /**
   * Get user by name
   */
  async getUserByName(username) {
    const sql = 'SELECT * FROM users WHERE name = ?';
    return await db.getRow(sql, [username]);
  }

  /**
   * Generate current avatar SVG (simplified version)
   */
  getCurrentAvatarSVG(username) {
    if (!username) return '';
    
    // Simple avatar generation based on username
    const colors = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7', '#DDA0DD', '#98D8C8'];
    const color = colors[username.length % colors.length];
    const initial = username.charAt(0).toUpperCase();
    
    return `<svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
      <circle cx="20" cy="20" r="20" fill="${color}"/>
      <text x="20" y="26" text-anchor="middle" fill="white" font-size="16" font-family="Arial">${initial}</text>
    </svg>`;
  }

  /**
   * Auto unban user (placeholder)
   */
  async autoUnbanUser() {
    // Logic to automatically unban users whose ban period has expired
    const sql = 'UPDATE users SET banned = 0 WHERE ban_until < NOW() AND banned = 1';
    return await db.query(sql);
  }

  /**
   * Log user activity to database (simplified)
   */
  async log2db(req) {
    try {
      const logData = {
        ip: req.ip || req.connection.remoteAddress,
        user_agent: req.get('User-Agent') || '',
        timestamp: new Date(),
        path: req.path
      };
      
      // Only log if we have meaningful data
      if (logData.ip) {
        await db.autoExecute('user_logs', logData, 'insert');
      }
    } catch (error) {
      console.error('Error logging user activity:', error);
    }
  }

  /**
   * Validate user session
   */
  validateSession(req) {
    return {
      isLoggedIn: this.isLogin(req.session),
      username: req.session?.name || '',
      type: req.session?.type || 0,
      userId: req.session?.userId || null
    };
  }
}

export default new UserModel();