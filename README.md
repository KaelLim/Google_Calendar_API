# Google Calendar API

### 建立一個Google Cloud Platform專案：
- 訪問 Google Cloud Console。
- 如果您還沒有Google Cloud賬戶，您需要註冊並創建一個。
- 在GCP控制台中，創建一個新的專案。

### 啟用Google Calendar API：
- 在您的GCP專案中，進入“API與服務”部分。
- 點擊“啟用API和服務”按鈕，搜索“Google Calendar API”，然後啟用它。

### 創建憑證：
- 在API與服務的“憑證”頁面，選擇“創建憑證”。
- 創建一個OAuth 2.0用戶端ID。
- **已授權的 JavaScript 來源**：指定哪些網頁來源（即域名或IP地址）被允許從客戶端請求Google的OAuth 2.0服務。這主要用於防止CSRF攻擊。
```http://localhost:3000```
- **已授權的重新導向 URI**：指定在OAuth認證流程中，Google應該將用戶導向哪個您的應用URI來交換授權碼為訪問令牌。
```http://localhost:3000/callback.php```
- 完成後可下載用戶端密鑰的檔案，格式如下
```json
{"web":{
    "client_id":"your_own_client_id.apps.googleusercontent.com",
    "project_id":"long-advice-999999",
    "auth_uri":"https://accounts.google.com/o/oauth2/auth",
    "token_uri":"https://oauth2.googleapis.com/token",
    "auth_provider_x509_cert_url":"https://www.googleapis.com/oauth2/v1/certs",
    "client_secret":"Gour_own_client_secret",
    "redirect_uris":["http://localhost:3000/callback"],
    "javascript_origins":["http://localhost:3000"]
    }
}
```

### 配置OAuth同意屏幕：
- **應用程式名稱**：這是用戶在同意屏幕上看到的您的應用名稱。
- **使用者支援電子郵件**：當用戶需要幫助時，可以聯繫的電子郵件地址。這通常是一個客服或支援團隊的郵箱。
- **開發人員聯絡資訊**：這是Google在需要時可以聯繫您的郵件地址。這應該是一個您經常檢查的郵箱。
- 其餘可無需設定

### 測試API：
* 使用創建的憑證，您可以開始在您的應用中調用Google Calendar API了。
* 確保在開發和測試階段處理任何API調用限制或配額問題。

## OAuth 2.0 授權回調處理

`callback.php`是處理Google OAuth 2.0授權回調的PHP腳本。當用戶完成Google登入授權後，Google會將用戶重定向回這個腳本，並附帶一個授權碼。本腳本的主要功能是使用這個授權碼來獲取訪問令牌（Access Token）和刷新令牌（Refresh Token），並將它們保存起來供後續使用。

### 主要步驟

1. **啟動會話**：透過`session_start()`開始一個會話，以便於管理和保持用戶狀態。
2. **配置OAuth客戶端信息**：設定客戶端ID、客戶端密鑰、重定向URI和令牌URL。
3. **處理授權碼**：檢查是否從URL參數中接收到授權碼。如果有，則調用`getToken`函數使用這個授權碼來獲取訪問令牌。
4. **保存令牌**：將獲取的訪問令牌和其他相關信息（如有效期、創建時間等）保存為JSON格式，並寫入到伺服器上的一個文件中。
5. **錯誤處理**：如果沒有接收到授權碼，則顯示錯誤信息。這有助於在開發和調試過程中識別問題。

### 安全提示

- 確保您的客戶端密鑰和令牌保存在安全的地方，避免泄露給第三方。
- 考慮使用更安全的存儲方案（如資料庫）來替代將令牌寫入文件系統。

### 使用示例

請根據您的應用需求調整客戶端ID、客戶端密鑰和重定向URI等配置信息。當部署到生產環境時，請更新這些設定以反映實際的運行環境。

