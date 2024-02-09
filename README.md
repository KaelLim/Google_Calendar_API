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
