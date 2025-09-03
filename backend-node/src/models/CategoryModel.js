import db from '../config/database.js';

class CategoryModel {
  /**
   * Get category information by ID
   */
  async getInfo(categoryId) {
    const sql = 'SELECT * FROM categories WHERE cat_id = ?';
    return await db.getRow(sql, [categoryId]);
  }

  /**
   * Get all categories information
   */
  async getAllInfo() {
    const sql = 'SELECT * FROM categories ORDER BY cat_id ASC';
    return await db.query(sql);
  }

  /**
   * Get category tree (hierarchical structure)
   */
  async getCatTree() {
    const sql = 'SELECT * FROM categories ORDER BY cat_id ASC';
    const categories = await db.query(sql);
    
    // Build tree structure (simple version - can be enhanced for nested categories)
    return categories.map(cat => ({
      id: cat.cat_id,
      name: cat.cat_name,
      description: cat.intro || '',
      postable: cat.postable === 1,
      parent_id: cat.parent_id || null
    }));
  }

  /**
   * Check if posting is allowed in category
   */
  async isPostable(categoryId) {
    const sql = 'SELECT postable FROM categories WHERE cat_id = ?';
    const result = await db.getOne(sql, [categoryId]);
    return result === 1;
  }

  /**
   * Get category by name
   */
  async getByName(categoryName) {
    const sql = 'SELECT * FROM categories WHERE cat_name = ?';
    return await db.getRow(sql, [categoryName]);
  }
}

export default new CategoryModel();