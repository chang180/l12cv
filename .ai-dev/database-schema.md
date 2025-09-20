# L12CV 資料庫架構文件

## 資料庫概覽

L12CV 使用 SQLite 作為開發環境資料庫，採用關聯式資料庫設計，支援用戶管理、履歷管理和作品集管理三大核心功能。

## 資料表結構

### 1. Users 表
**用途**: 儲存用戶基本資訊和認證資料

| 欄位名 | 類型 | 約束 | 說明 |
|--------|------|------|------|
| id | bigint | PRIMARY KEY | 用戶唯一識別碼 |
| name | varchar(255) | NOT NULL | 用戶姓名 |
| slug | varchar(255) | UNIQUE | 用戶友善 URL 標識符 |
| email | varchar(255) | UNIQUE, NOT NULL | 電子郵件地址 |
| avatar | varchar(255) | NULLABLE | 頭像檔案路徑 |
| email_verified_at | timestamp | NULLABLE | 郵件驗證時間 |
| password | varchar(255) | NOT NULL | 加密密碼 |
| remember_token | varchar(100) | NULLABLE | 記住登入令牌 |
| created_at | timestamp | NOT NULL | 建立時間 |
| updated_at | timestamp | NOT NULL | 更新時間 |

**索引**:
- `users_email_unique` (email)
- `users_slug_unique` (slug)

**特殊功能**:
- 自動生成 slug（建立時）
- 密碼自動加密
- 頭像 URL 生成方法

### 2. Resumes 表
**用途**: 儲存用戶履歷資料

| 欄位名 | 類型 | 約束 | 說明 |
|--------|------|------|------|
| id | bigint | PRIMARY KEY | 履歷唯一識別碼 |
| user_id | bigint | FOREIGN KEY, UNIQUE | 關聯用戶 ID |
| slug | varchar(255) | UNIQUE | 履歷友善 URL 標識符 |
| title | varchar(255) | NOT NULL | 履歷標題 |
| summary | text | NULLABLE | 自我介紹摘要 |
| avatar | varchar(255) | NULLABLE | 履歷大頭照 |
| education | json | NULLABLE | 學歷資料陣列 |
| experience | json | NULLABLE | 工作經驗陣列 |
| is_public | boolean | DEFAULT false | 是否公開 |
| created_at | timestamp | NOT NULL | 建立時間 |
| updated_at | timestamp | NOT NULL | 更新時間 |

**外鍵約束**:
- `resumes_user_id_foreign` → `users(id)` ON DELETE CASCADE

**JSON 欄位結構**:

**education 欄位**:
```json
[
  {
    "school": "學校名稱",
    "degree": "學位",
    "field": "科系",
    "start_date": "開始日期",
    "end_date": "結束日期",
    "description": "描述"
  }
]
```

**experience 欄位**:
```json
[
  {
    "company": "公司名稱",
    "position": "職位",
    "start_date": "開始日期",
    "end_date": "結束日期",
    "current": false,
    "description": "工作描述"
  }
]
```

### 3. Projects 表
**用途**: 儲存用戶作品集專案資料

| 欄位名 | 類型 | 約束 | 說明 |
|--------|------|------|------|
| id | bigint | PRIMARY KEY | 專案唯一識別碼 |
| user_id | bigint | FOREIGN KEY | 關聯用戶 ID |
| title | varchar(255) | NOT NULL | 專案標題 |
| description | text | NULLABLE | 專案描述 |
| thumbnail | varchar(255) | NULLABLE | 縮圖檔案路徑 |
| url | varchar(255) | NULLABLE | 專案演示連結 |
| github_url | varchar(255) | NULLABLE | GitHub 連結 |
| technologies | json | NULLABLE | 使用技術標籤 |
| completion_date | date | NULLABLE | 完成日期 |
| is_featured | boolean | DEFAULT false | 是否為特色專案 |
| order | integer | DEFAULT 0 | 排序順序 |
| created_at | timestamp | NOT NULL | 建立時間 |
| updated_at | timestamp | NOT NULL | 更新時間 |

**外鍵約束**:
- `projects_user_id_foreign` → `users(id)` ON DELETE CASCADE

**JSON 欄位結構**:

**technologies 欄位**:
```json
["Laravel", "PHP", "MySQL", "Tailwind CSS"]
```

## 資料關聯關係

### 一對一關係
```
User (1) ←→ (1) Resume
```
- 每個用戶只能有一份履歷
- 履歷刪除時會自動刪除關聯的用戶（CASCADE）

### 一對多關係
```
User (1) ←→ (N) Project
```
- 每個用戶可以有多個專案
- 用戶刪除時會自動刪除所有關聯專案（CASCADE）

## 資料模型關聯

### User Model 關聯
```php
// 取得用戶履歷
public function resume()
{
    return $this->hasOne(Resume::class);
}

// 取得用戶所有專案
public function projects()
{
    return $this->hasMany(Project::class);
}
```

### Resume Model 關聯
```php
// 取得履歷擁有者
public function user()
{
    return $this->belongsTo(User::class);
}
```

### Project Model 關聯
```php
// 取得專案擁有者
public function user()
{
    return $this->belongsTo(User::class);
}
```

## 資料庫遷移檔案

### 主要遷移檔案
1. `0001_01_01_000000_create_users_table.php` - 建立用戶表
2. `2025_07_26_021911_add_avatar_to_users_table.php` - 新增用戶頭像欄位
3. `2025_07_26_065325_add_slug_to_users_table.php` - 新增用戶 slug 欄位
4. `2025_02_28_113246_create_resumes_table.php` - 建立履歷表
5. `2025_07_26_025633_create_projects_table.php` - 建立專案表

## 資料庫索引策略

### 主要索引
- **Primary Keys**: 所有表的 id 欄位
- **Unique Indexes**: 
  - users.email
  - users.slug
  - resumes.slug
- **Foreign Key Indexes**: 
  - resumes.user_id
  - projects.user_id

### 查詢優化索引
- **Composite Indexes**: 可考慮為常用查詢組合建立複合索引
- **Performance Indexes**: 根據實際查詢模式調整

## 資料完整性約束

### 外鍵約束
- `resumes.user_id` → `users.id` (CASCADE DELETE)
- `projects.user_id` → `users.id` (CASCADE DELETE)

### 唯一性約束
- 用戶 email 唯一
- 用戶 slug 唯一
- 履歷 slug 唯一
- 用戶只能有一份履歷

### 檢查約束
- email 格式驗證
- slug 格式驗證（字母數字連字號）
- JSON 欄位格式驗證

## 資料庫效能考量

### 查詢優化
- 使用 Eager Loading 避免 N+1 問題
- 適當的索引設計
- 分頁查詢處理

### 儲存優化
- JSON 欄位壓縮
- 檔案路徑標準化
- 軟刪除機制（可選）

## 備份與恢復

### 備份策略
- 定期資料庫備份
- 檔案系統備份（上傳檔案）
- 配置檔案備份

### 恢復程序
- 資料庫恢復
- 檔案恢復
- 系統重新部署

## 擴展性考量

### 水平擴展
- 讀寫分離
- 分片策略
- 快取層設計

### 垂直擴展
- 硬體升級
- 資料庫優化
- 查詢優化

## 安全性考量

### 資料保護
- 敏感資料加密
- 密碼雜湊
- 檔案存取控制

### 存取控制
- 資料庫權限管理
- 應用程式層權限
- API 存取控制
