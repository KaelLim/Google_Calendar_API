<?php
session_start();

// 從 credentials.json 讀取 client_id 和 client_secret
$credentials = json_decode(file_get_contents(__DIR__ . '/credentials.json'), true)['web'];
$client_id = $credentials['client_id'];
$client_secret = $credentials['client_secret'];

$redirect_uri = 'http://localhost:3000/callback.php';
$token_url = 'https://oauth2.googleapis.com/token';

if (isset($_GET['code'])) {
    // 從回調請求中獲取授權碼
    $code = $_GET['code'];

    // 使用授權碼獲取access token
    $token = getToken($code, $client_id, $client_secret, $redirect_uri, $token_url);

    // 將token存儲為JSON格式
    $token_data = array(
        'access_token' => $token->access_token,
        'expires_in' => $token->expires_in, // Token的有效期（秒）
        'created' => time() // 當前時間戳
    );

    if (isset($token->refresh_token)) {
        $token_data['refresh_token'] = $token->refresh_token;
    }

    // 將Token數據轉換為JSON格式
    $json_token = json_encode($token_data);

    // 定義存儲JSON的文件路徑（確保這個路徑是安全的且PHP有寫入權限）
    $file_path = 'token.json';

    // 將JSON數據寫入文件
    file_put_contents($file_path, $json_token);
} else {
    // 如果沒有code參數，顯示錯誤或重定向到初始登錄頁面
    echo '未接收到授權碼。';
}

function getToken($code, $client_id, $client_secret, $redirect_uri, $token_url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $token_url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
        'code' => $code,
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'redirect_uri' => $redirect_uri,
        'grant_type' => 'authorization_code'
    )));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);

    $response_data = json_decode($response);
    if (isset($response_data->access_token)) {
        return $response_data;
    } else {
        die('獲取Token時發生錯誤：' . $response);
    }
}
?>
