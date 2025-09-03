import db from '../config/database.js';

class MessageModel {
  /**
   * Count threads in a category
   */
  async countThreads(categoryId = 1) {
    const sql = 'SELECT COUNT(*) as count FROM threads WHERE cat = ?';
    const result = await db.getOne(sql, [categoryId]);
    return parseInt(result) || 0;
  }

  /**
   * Get top threads with pagination
   */
  async getTopThreads(offset = 0, categoryId = 1, limit = 20) {
    const sql = `
      SELECT tid, title, content, name, cat, dateline, lastreptime 
      FROM threads 
      WHERE cat = ? 
      ORDER BY lastreptime DESC 
      LIMIT ? OFFSET ?
    `;
    return await db.query(sql, [categoryId, limit, offset]);
  }

  /**
   * Get top replies for a thread
   */
  async getTopReplies(threadId, offset = 0, limit = 5) {
    const sql = `
      SELECT rid, content, name, dateline 
      FROM replies 
      WHERE tid = ? 
      ORDER BY dateline ASC 
      LIMIT ? OFFSET ?
    `;
    return await db.query(sql, [threadId, limit, offset]);
  }

  /**
   * Filter and format content (migrated from PHP)
   */
  filterContent(content) {
    if (!content) return '';

    // HTML escape
    let filtered = content
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#x27;');

    // Line breaks
    filtered = filtered.replace(/(\r\n|\r|\n)/g, '<br/>');

    // Spaces
    filtered = filtered.replace(/\s/g, '&nbsp;');

    // Quotes
    filtered = filtered.replace(/&gt;&gt;\d+(&gt;\d+)?/g, '<span class="quote">$&</span>');

    // Dice (simple version)
    filtered = filtered.replace(/\[\[(d|dice|r|roll)([-]?\d+)\]\]/gi, (match, type, num) => {
      const diceNum = parseInt(num);
      const result = diceNum > 0 ? Math.floor(Math.random() * diceNum) + 1 : 
                     diceNum < 0 ? Math.floor(Math.random() * Math.abs(diceNum)) + diceNum : 0;
      return `<span class="dice" title="D${diceNum}骰"><small><i class="fa-solid fa-dice-d6"></i>&nbsp;</small><b>${result}</b><small> (D${diceNum})</small></span>`;
    });

    // Doodles
    filtered = filtered.replace(/\[\[doodle:([a-fA-F0-9]+\.[a-z]+)\]\]/g, 
      '<div class="fandaoDoodle"><img class="fandaoImg" src="upload/$1" alt="fandaoDoodle" title="$1"></div>');

    // Images
    filtered = filtered.replace(/\[\[img:([a-fA-F0-9]+\.[a-z]+)\]\]/g, 
      '<img class="fandaoImg" src="upload/$1" alt="fandaoIMG" title="$1">');

    // Links
    filtered = filtered.replace(/\[\[(((http|https):\/\/)?[a-zA-Z0-9./?:@\-_=#]+\.([a-zA-Z0-9&%./?:@\-_=#]*))(\|)?(.+)?\]\]/g, 
      (match, url, _, protocol, ext, pipe, text) => {
        const sup = '<sup><i class="fa-solid fa-arrow-up-right-from-square"></i></sup>';
        const fullUrl = protocol ? url : `http://${url}`;
        const linkText = text || url;
        return `<a class="fandaoLink" href="${fullUrl}" target="_blank">${linkText}${sup}</a>`;
      });

    return filtered;
  }

  /**
   * Decode base64 content if needed
   */
  decodeContent(content) {
    if (!content) return '';
    
    try {
      // Try to decode as base64
      const decoded = Buffer.from(content, 'base64').toString('utf8');
      // Check if it's valid UTF-8 and not the same as original
      if (decoded !== content && decoded.length > 0) {
        return decoded;
      }
    } catch (e) {
      // Not base64 or invalid, return original
    }
    
    return content;
  }
}

export default new MessageModel();