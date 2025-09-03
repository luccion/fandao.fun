# API Demo Examples

This file contains example API calls that demonstrate the new REST API endpoints.

## Health Check
```bash
curl -X GET "http://localhost/fandao.fun/api/health"
```

Expected Response:
```json
{
  "status": "ok",
  "timestamp": 1703123456,
  "version": "0.8.0"
}
```

## Get Categories
```bash
curl -X GET "http://localhost/fandao.fun/api/categories"
```

Expected Response:
```json
{
  "categories": [
    {
      "id": 1,
      "name": "General Discussion",
      "description": "General topics and discussions",
      "postable": true,
      "order": 0
    }
  ]
}
```

## Get Discussions (with pagination)
```bash
curl -X GET "http://localhost/fandao.fun/api/discussions?cat=1&page=1&limit=5"
```

Expected Response:
```json
{
  "discussions": [
    {
      "id": 123,
      "title": "Welcome to fandao.fun",
      "author": "admin",
      "categoryId": 1,
      "lastReplyTime": 1703123456,
      "replyCount": 5,
      "content": "Welcome to our discussion platform...",
      "created": 1703120000,
      "replies": [...]
    }
  ],
  "pagination": {
    "currentPage": 1,
    "totalPages": 3,
    "totalCount": 12,
    "limit": 5
  },
  "category": {
    "id": 1,
    "name": "General Discussion",
    "description": "General topics and discussions",
    "postable": true
  }
}
```

## Get Current User
```bash
curl -X GET "http://localhost/fandao.fun/api/user" \
     -H "Cookie: session_cookie_if_authenticated"
```

Expected Response (authenticated):
```json
{
  "isLoggedIn": true,
  "username": "john_doe",
  "type": 1,
  "avatar": "<svg>...</svg>"
}
```

Expected Response (not authenticated):
```json
{
  "isLoggedIn": false,
  "username": "",
  "type": 0,
  "avatar": ""
}
```

## CORS Headers

All API endpoints include CORS headers for cross-origin requests:
- `Access-Control-Allow-Origin: *`
- `Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS`
- `Access-Control-Allow-Headers: Content-Type, Authorization`

## Error Handling

API endpoints return appropriate HTTP status codes:
- `200` - Success
- `404` - Endpoint not found
- `405` - Method not allowed
- `500` - Internal server error

Error Response Format:
```json
{
  "error": "Error description",
  "message": "Detailed error message (in debug mode)"
}
```