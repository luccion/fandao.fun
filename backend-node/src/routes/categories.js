import express from 'express';
import CategoryModel from '../models/CategoryModel.js';

const router = express.Router();

/**
 * GET /api/categories
 * Get all categories
 */
router.get('/', async (req, res) => {
  try {
    const categories = await CategoryModel.getCatTree();
    
    res.json({
      categories: categories
    });
    
  } catch (error) {
    console.error('Error fetching categories:', error);
    res.status(500).json({
      error: 'Internal server error',
      message: process.env.APP_DEBUG === 'true' ? error.message : 'Failed to fetch categories'
    });
  }
});

/**
 * GET /api/categories/:id
 * Get specific category info
 */
router.get('/:id', async (req, res) => {
  try {
    const categoryId = parseInt(req.params.id);
    
    if (!categoryId || categoryId < 1) {
      return res.status(400).json({ error: 'Invalid category ID' });
    }
    
    const category = await CategoryModel.getInfo(categoryId);
    
    if (!category) {
      return res.status(404).json({ error: 'Category not found' });
    }
    
    res.json({
      category: {
        id: category.cat_id,
        name: category.cat_name,
        description: category.intro || '',
        postable: category.postable === 1
      }
    });
    
  } catch (error) {
    console.error('Error fetching category:', error);
    res.status(500).json({
      error: 'Internal server error',
      message: process.env.APP_DEBUG === 'true' ? error.message : 'Failed to fetch category'
    });
  }
});

export default router;