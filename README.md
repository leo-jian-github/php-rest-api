# php-laravel-api
此為 PHP 使用 laravel 框架實作 API 專案

## 概述
1. User: 註冊、登入、修改
2. Issue: 建立、查詢(分頁)
3. Issue comment: 建立、查詢(分頁)
4. User 完成註冊或登入後，其餘 API 接口皆需傳入 token

## API 接口

### User
- **POST**      api/user/register : 註冊
- **POST**      api/user/login : 登入
- **PUT**       api/user/name : 修改用戶資訊

### Issue
- **POST**      api/issue/create : 建立
- **POST**      api/issue/list : 查詢(分頁)

### Issue comment
- **POST**      api/issue/comment/create : 建立
- **POST**      api/issue/comment/list : 查詢(分頁)

## API Swagger
- http://127.0.0.1:8000/swagger

## 相關指令
- php artisan serve : 啟動服務
- php artisan migrate : 更新 migrations 至 DB
- php artisan route:list : 列出 API 接口列表
