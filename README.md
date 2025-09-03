# fandao.fun

现代化的饭岛社区平台 - 使用 PHP + React 构建的现代讨论平台

## 📁 项目结构

```
fandao.fun/
├── backend/                 # 🔧 PHP 后端 API
│   ├── public/             # Web 根目录
│   │   ├── index.php       # 主路由入口
│   │   └── assets/         # 静态资源
│   ├── src/                # 应用源码
│   │   ├── Controllers/    # 控制器
│   │   ├── Models/         # 数据模型
│   │   ├── Api/           # API 端点
│   │   ├── Services/      # 业务逻辑
│   │   └── Config/        # 配置文件
│   └── storage/           # 存储目录
│       ├── logs/          # 日志文件
│       ├── cache/         # 缓存文件
│       └── uploads/       # 上传文件
├── frontend/               # ⚛️ React 前端
│   ├── src/               # 前端源码
│   ├── public/            # 静态资源
│   └── dist/              # 构建输出
├── docs/                  # 📚 项目文档
├── scripts/               # 🔧 构建和维护脚本
├── .github/               # 🤖 CI/CD 工作流
└── view/                  # 📦 遗留 PHP 模板 (逐步迁移)
```

## 🚀 快速开始

### 环境要求
- PHP 7.4+
- Node.js 18+
- Composer
- MySQL

### 安装步骤

1. **克隆仓库**
```bash
git clone https://github.com/luccion/fandao.fun.git
cd fandao.fun
```

2. **后端设置**
```bash
composer install
cp .env.example .env
# 编辑 .env 填入数据库配置
```

3. **前端设置**
```bash
cd frontend
npm install
cp .env.example .env
# 编辑前端环境变量
npm run dev
```

## 🏗️ 现代化特性

### 后端改进
- ✅ **MVC 架构**: 清晰的控制器-模型分离
- ✅ **路由系统**: 现代化的 URL 路由
- ✅ **API 层**: RESTful API 端点
- ✅ **自动加载**: PSR-4 自动加载支持
- ✅ **配置管理**: 环境变量和配置分离

### 前端特性
- ✅ **React + TypeScript**: 现代前端技术栈
- ✅ **Vite 构建**: 快速开发和构建
- ✅ **组件化**: 可复用的 UI 组件
- ✅ **状态管理**: 现代状态管理模式

### 开发体验
- ✅ **热重载**: 开发时自动刷新
- ✅ **代码检查**: ESLint 代码质量检查
- ✅ **自动部署**: GitHub Actions CI/CD
- ✅ **文档完善**: 详细的开发文档

## 📖 详细文档

- [部署指南](docs/DEPLOYMENT.md)
- [API 文档](docs/API_EXAMPLES.md)
- [安全说明](docs/SECURITY.md)

## 🔄 迁移状态

### ✅ 已完成
- [x] 后端目录结构现代化
- [x] 前端 React + TypeScript 设置
- [x] API 端点重组
- [x] 路由系统实现
- [x] 构建和部署配置
- [x] 文档整理

### 🔄 进行中
- [ ] 遗留 PHP 文件迁移到控制器
- [ ] 数据库访问层优化
- [ ] 用户认证系统现代化

### 📋 计划中
- [ ] 实时功能 (WebSocket)
- [ ] 移动端优化
- [ ] 性能监控
- [ ] 单元测试覆盖

## 🔧 开发指南

### 添加新功能
1. 在 `backend/src/Controllers/` 创建控制器
2. 在 `backend/src/Models/` 创建模型
3. 在 `backend/src/Config/Router.php` 添加路由
4. 在 `frontend/src/components/` 创建前端组件

### API 开发
```php
// backend/src/Api/endpoints/example.php
<?php
header('Content-Type: application/json');
echo json_encode(['message' => 'Hello API']);
```

### 前端组件
```tsx
// frontend/src/components/Example.tsx
export const Example = () => {
  return <div>Hello React</div>;
};
```

## 📞 支持

遇到问题？
1. 查看 [GitHub Issues](https://github.com/luccion/fandao.fun/issues)
2. 检查开发者控制台错误
3. 查看服务器日志 `backend/storage/logs/`

---

**现代化完成！** 🎉 项目现在具有清晰的结构和现代化的开发体验。
