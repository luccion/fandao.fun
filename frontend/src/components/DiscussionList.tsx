import React, { useState, useEffect, useCallback } from 'react';
import { discussionService } from '../services/api';
import type { Discussion, Category } from '../services/api';

interface DiscussionListProps {
  categoryId?: number;
  pageSize?: number;
}

const DiscussionList: React.FC<DiscussionListProps> = ({ 
  categoryId = 1, 
  pageSize = 20 
}) => {
  const [discussions, setDiscussions] = useState<Discussion[]>([]);
  const [category, setCategory] = useState<Category | null>(null);
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  const fetchDiscussions = useCallback(async () => {
    try {
      setLoading(true);
      setError(null);
      const response = await discussionService.getDiscussions(categoryId, currentPage, pageSize);
      setDiscussions(response.discussions);
      setCategory(response.category);
      setTotalPages(response.pagination.totalPages);
    } catch (err) {
      setError('Failed to load discussions');
      console.error('Error fetching discussions:', err);
    } finally {
      setLoading(false);
    }
  }, [categoryId, currentPage, pageSize]);

  useEffect(() => {
    fetchDiscussions();
  }, [fetchDiscussions]);

  const formatDate = (timestamp: number) => {
    return new Date(timestamp * 1000).toLocaleString('zh-CN');
  };

  const handlePageChange = (page: number) => {
    setCurrentPage(page);
  };

  if (loading) {
    return (
      <div className="d-flex justify-content-center p-4">
        <div className="spinner-border" role="status">
          <span className="sr-only">Loading...</span>
        </div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="alert alert-danger" role="alert">
        {error}
        <button 
          className="btn btn-link p-0 ml-2" 
          onClick={() => fetchDiscussions()}
        >
          重试
        </button>
      </div>
    );
  }

  return (
    <div className="discussion-list">
      {category && (
        <div className="shadow-sm p-3 color-background rounded mb-3">
          <h2 className="title color-font-default">{category.name}</h2>
          <p className="intro color-font-default">{category.description}</p>
          <hr className="discussionHr" />
        </div>
      )}

      {discussions.length === 0 ? (
        <div className="notice color-font-default p-3 text-center">
          {category && !category.postable ? 
            '虽然还没有人发起讨论，但是你也不能在这里说话~' : 
            '还没有人发起讨论，由你发起第一个讨论吧！'
          }
        </div>
      ) : (
        <ul className="list-group list-group-flush">
          {discussions.map((discussion) => (
            <li 
              key={discussion.id} 
              className="discussion list-group-item list-group-item-action color-font-grey"
              data-tid={discussion.id}
            >
              <div className="stretched-link d-flex justify-content-between">
                <div className="contentsContainer">
                  <div className="titleContainer d-flex flex-row align-items-end">
                    <span className="avatarSVG" dangerouslySetInnerHTML={{ __html: '🗿' }} />
                    <span className="d-flex flex-column inline-block ml-1 mr-1">
                      <span className="d-flex flex-row">
                        <span className="text-nickname color-font-secondary">
                          {discussion.author}
                        </span>
                        <span className="text-date color-font-secondary small ml-1">
                          发表在{category?.name} 
                        </span>
                        <span className="text-date color-font-secondary small">
                          <i className="fa-regular fa-comment-dots"></i>&nbsp;
                          {formatDate(discussion.lastReplyTime)}
                        </span>
                      </span>
                      <span className="text-title color-font-default">
                        {discussion.title}
                      </span>
                    </span>
                  </div>
                  <div className="text-content color-font-grey mt-1">
                    {discussion.content.substring(0, 100)}
                    {discussion.content.length > 100 && '...'}
                  </div>
                  {discussion.replies.length > 0 && (
                    <div className="replies mt-2">
                      <small className="color-font-secondary">
                        {discussion.replyCount} 条回复
                      </small>
                    </div>
                  )}
                </div>
              </div>
            </li>
          ))}
        </ul>
      )}

      {/* Pagination */}
      {totalPages > 1 && (
        <nav className="mt-3">
          <ul className="pagination justify-content-center">
            <li className={`page-item ${currentPage === 1 ? 'disabled' : ''}`}>
              <button 
                className="page-link" 
                onClick={() => handlePageChange(currentPage - 1)}
                disabled={currentPage === 1}
              >
                上一页
              </button>
            </li>
            
            {Array.from({ length: Math.min(totalPages, 10) }, (_, i) => {
              const page = i + 1;
              return (
                <li key={page} className={`page-item ${currentPage === page ? 'active' : ''}`}>
                  <button 
                    className="page-link" 
                    onClick={() => handlePageChange(page)}
                  >
                    {page}
                  </button>
                </li>
              );
            })}
            
            <li className={`page-item ${currentPage === totalPages ? 'disabled' : ''}`}>
              <button 
                className="page-link" 
                onClick={() => handlePageChange(currentPage + 1)}
                disabled={currentPage === totalPages}
              >
                下一页
              </button>
            </li>
          </ul>
        </nav>
      )}
    </div>
  );
};

export default DiscussionList;