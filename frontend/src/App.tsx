import { useState, useEffect } from 'react'
import DiscussionList from './components/DiscussionList'
import { discussionService } from './services/api'
import type { Category } from './services/api'
import './App.css'

function App() {
  const [categories, setCategories] = useState<Category[]>([])
  const [selectedCategory, setSelectedCategory] = useState(1)
  const [appInfo, setAppInfo] = useState({ name: '饭岛', version: '0.8.0' })

  useEffect(() => {
    fetchCategories()
    checkApiHealth()
  }, [])

  const fetchCategories = async () => {
    try {
      const response = await discussionService.getCategories()
      setCategories(response.categories)
    } catch (error) {
      console.error('Failed to fetch categories:', error)
    }
  }

  const checkApiHealth = async () => {
    try {
      const health = await discussionService.checkHealth()
      setAppInfo({ 
        name: import.meta.env.VITE_APP_NAME || '饭岛', 
        version: health.version 
      })
    } catch (error) {
      console.error('API health check failed:', error)
    }
  }

  return (
    <div className="app">
      <header className="app-header mb-4">
        <nav className="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
          <div className="container">
            <a className="navbar-brand" href="#">
              {appInfo.name} <small className="text-muted">v{appInfo.version}</small>
            </a>
            <div className="navbar-nav">
              <span className="nav-link text-success">
                ✅ React Frontend Active
              </span>
            </div>
          </div>
        </nav>
      </header>

      <div className="container">
        <div className="row">
          <div className="col-md-3">
            <div className="sticky-top">
              <div className="card">
                <div className="card-header">
                  <h5>分类</h5>
                </div>
                <div className="list-group list-group-flush">
                  {categories.map((category) => (
                    <button
                      key={category.id}
                      className={`list-group-item list-group-item-action ${
                        selectedCategory === category.id ? 'active' : ''
                      }`}
                      onClick={() => setSelectedCategory(category.id)}
                    >
                      {category.name}
                      {!category.postable && (
                        <small className="text-muted ml-1">(只读)</small>
                      )}
                    </button>
                  ))}
                </div>
              </div>
            </div>
          </div>
          
          <div className="col-md-9">
            <DiscussionList 
              categoryId={selectedCategory} 
              key={selectedCategory} 
            />
          </div>
        </div>
      </div>

      <footer className="mt-5 py-4 bg-light text-center">
        <div className="container">
          <p className="text-muted mb-0">
            fandao.fun - React Frontend Migration Demo
          </p>
          <small className="text-muted">
            Backend API: {import.meta.env.VITE_API_BASE_URL}
          </small>
        </div>
      </footer>
    </div>
  )
}

export default App
