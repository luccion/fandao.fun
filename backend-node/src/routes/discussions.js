import express from 'express';
import MessageModel from '../models/MessageModel.js';
import CategoryModel from '../models/CategoryModel.js';

const router = express.Router();

/**
 * GET /api/discussions
 * Get discussions/threads with pagination
 */
router.get('/', async (req, res) => {
  try {
    // Get query parameters
    let cat = parseInt(req.query.cat) || 1;
    let page = parseInt(req.query.page) || 1;
    let limit = Math.min(parseInt(req.query.limit) || 20, 50);
    
    // Validate parameters
    if (cat < 1) cat = 1;
    if (page < 1) page = 1;
    
    // Get total count and calculate pagination
    const totalCount = await MessageModel.countThreads(cat);
    const totalPages = Math.ceil(totalCount / limit);
    const offset = (page - 1) * limit;
    
    // Get threads
    const threads = await MessageModel.getTopThreads(offset, cat, limit);
    
    // Get replies for each thread
    const threadsWithReplies = [];
    for (const thread of threads) {
      const replies = await MessageModel.getTopReplies(thread.tid, 0, 5);
      
      threadsWithReplies.push({
        id: thread.tid,
        title: MessageModel.decodeContent(thread.title),
        author: thread.name,
        categoryId: thread.cat,
        lastReplyTime: thread.lastreptime,
        replyCount: replies.length,
        content: MessageModel.decodeContent(thread.content),
        created: thread.dateline,
        replies: replies.map(reply => ({
          id: reply.rid,
          content: MessageModel.decodeContent(reply.content),
          author: reply.name,
          created: reply.dateline
        }))
      });
    }
    
    // Get category info
    const category = await CategoryModel.getInfo(cat);
    
    res.json({
      discussions: threadsWithReplies,
      pagination: {
        currentPage: page,
        totalPages: totalPages,
        totalCount: totalCount,
        limit: limit
      },
      category: {
        id: category?.cat_id || cat,
        name: category?.cat_name || '',
        description: category?.intro || '',
        postable: category?.postable === 1
      }
    });
    
  } catch (error) {
    console.error('Error fetching discussions:', error);
    res.status(500).json({
      error: 'Internal server error',
      message: process.env.APP_DEBUG === 'true' ? error.message : 'Failed to fetch discussions'
    });
  }
});

export default router;