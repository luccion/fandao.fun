# 迁移指南 - 从遗留PHP到现代化结构

这个文档描述了如何将剩余的PHP文件迁移到新的现代化结构中。

## 🎯 迁移原则

1. **渐进式迁移**: 保持向后兼容，逐步迁移
2. **最小修改**: 重用现有逻辑，仅重新组织结构
3. **保持功能**: 确保用户体验不受影响

## 📋 待迁移文件状态

### ✅ 已迁移
- [x] `index.php` → `HomeController::index()`
- [x] `article.php` → `ArticleController::show()`
- [x] `me.php` → `UserController::profile()`
- [x] `view.php` → `ThreadController::view()`

### 🔄 部分迁移 (使用回退机制)
- [x] `edit.php` → `ThreadController::edit()` (使用回退)
- [x] `delete.php` → `ThreadController::delete()` (使用回退)

### 📋 计划迁移
- [ ] `hall.php` → `HallController::index()`
- [ ] `market.php` → `MarketController::index()`
- [ ] `lottery.php` → `LotteryController::index()`
- [ ] `exchange.php` → `ExchangeController::index()`
- [ ] `privacy.php` → `PageController::privacy()`
- [ ] `terms.php` → `PageController::terms()`
- [ ] `oauth.php` → `AuthController::oauth()`
- [ ] `logout.php` → `AuthController::logout()`

### 🔧 特殊文件 (保持原位)
- [ ] `init.php` - 核心初始化文件
- [ ] `aifadianwebhook.php` - Webhook处理
- [ ] `mail.php` - 邮件发送
- [ ] `test.php` - 测试文件

## 🚀 迁移步骤

### 1. 创建控制器

```php
<?php
/**
 * ExampleController - 处理示例功能
 */
class ExampleController
{
    public function action()
    {
        // 1. 复制原文件的逻辑
        // 2. 移除 define('ACC', true) 和 require('init.php')
        // 3. 整理变量赋值
        // 4. 使用 extract() 传递给视图
        
        $data = [
            'variable1' => $value1,
            'variable2' => $value2
        ];
        
        extract($data);
        require(ROOT . "view/example.php");
    }
}
```

### 2. 更新路由

在 `backend/src/Config/Router.php` 中添加：

```php
'/example' => ['controller' => 'ExampleController', 'action' => 'action'],
```

### 3. 测试迁移

1. 访问新路由确保功能正常
2. 检查原路由仍然工作（回退机制）
3. 验证所有参数和功能

## 🔄 回退机制

当前的路由系统提供了回退机制：

```php
// 在 backend/public/index.php 中
if (!$router->dispatch($path)) {
    // 回退到原始文件结构
    $originalFile = ROOT . ltrim($path, '/') . '.php';
    if (file_exists($originalFile)) {
        require_once $originalFile;
    }
}
```

这确保了在迁移过程中不会破坏现有功能。

## 📈 迁移优先级

### 高优先级 (用户核心功能)
1. `hall.php` - 大厅页面
2. `market.php` - 市场页面
3. `lottery.php` - 抽奖功能
4. `oauth.php` - 用户认证

### 中优先级 (辅助功能)
1. `exchange.php` - 兑换功能
2. `privacy.php` - 隐私政策
3. `terms.php` - 服务条款

### 低优先级 (管理功能)
1. `mail.php` - 邮件功能
2. `test.php` - 测试页面
3. Webhook处理文件

## 🧪 测试清单

每个迁移的控制器都应该通过以下测试：

- [ ] 页面能正常加载
- [ ] 所有GET/POST参数正确处理
- [ ] 数据库查询正常执行
- [ ] 视图正确渲染
- [ ] 错误处理工作正常
- [ ] 用户权限检查有效

## 📝 迁移模板

使用这个模板快速创建新控制器：

```php
<?php
/**
 * [ControllerName] - [Description]
 */
class [ControllerName]
{
    public function [action]()
    {
        // === 原始逻辑开始 ===
        // 从原PHP文件复制核心逻辑
        
        // === 原始逻辑结束 ===
        
        // 整理数据
        $data = [
            // 所有模板需要的变量
        ];
        
        // 传递给视图
        extract($data);
        require(ROOT . "view/[template].php");
    }
}
```

## 🎯 完成标准

迁移完成后，项目将达到：

- ✅ **清晰架构**: MVC模式分离关注点
- ✅ **现代路由**: 统一的URL处理
- ✅ **PSR-4自动加载**: 现代PHP标准
- ✅ **向后兼容**: 现有功能不受影响
- ✅ **易于维护**: 代码组织清晰
- ✅ **可扩展性**: 易于添加新功能