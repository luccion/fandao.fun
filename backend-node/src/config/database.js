import mysql from 'mysql2/promise';

class Database {
  constructor() {
    this.pool = null;
    this.config = {
      host: process.env.DB_HOST || 'localhost',
      user: process.env.DB_USER || 'fandao',
      password: process.env.DB_PASS || '',
      database: process.env.DB_NAME || 'fandao',
      charset: process.env.DB_CHARSET || 'utf8mb4',
      connectionLimit: 10,
      acquireTimeout: 60000,
      timeout: 60000,
      multipleStatements: false
    };
  }

  async connect() {
    try {
      this.pool = mysql.createPool(this.config);
      // Test the connection
      const connection = await this.pool.getConnection();
      await connection.ping();
      connection.release();
      console.log('Database connected successfully');
      return true;
    } catch (error) {
      console.error('Database connection failed:', error.message);
      return false;
    }
  }

  async query(sql, params = []) {
    if (!this.pool) {
      throw new Error('Database not connected');
    }
    
    try {
      const [rows] = await this.pool.execute(sql, params);
      return rows;
    } catch (error) {
      console.error('Database query error:', error.message);
      throw error;
    }
  }

  async getRow(sql, params = []) {
    const rows = await this.query(sql, params);
    return rows.length > 0 ? rows[0] : null;
  }

  async getOne(sql, params = []) {
    const row = await this.getRow(sql, params);
    if (row) {
      const values = Object.values(row);
      return values.length > 0 ? values[0] : null;
    }
    return null;
  }

  async autoExecute(table, data, action = 'insert', where = '') {
    if (action === 'insert') {
      const columns = Object.keys(data).join(', ');
      const placeholders = Object.keys(data).map(() => '?').join(', ');
      const values = Object.values(data);
      
      const sql = `INSERT INTO ${table} (${columns}) VALUES (${placeholders})`;
      const result = await this.query(sql, values);
      return result.insertId || result.affectedRows;
    } else if (action === 'update') {
      const setClause = Object.keys(data).map(key => `${key} = ?`).join(', ');
      const values = Object.values(data);
      
      const sql = `UPDATE ${table} SET ${setClause} ${where}`;
      const result = await this.query(sql, values);
      return result.affectedRows;
    }
    
    throw new Error(`Unsupported action: ${action}`);
  }

  async close() {
    if (this.pool) {
      await this.pool.end();
      this.pool = null;
      console.log('Database connection closed');
    }
  }
}

// Singleton instance
const db = new Database();

export default db;