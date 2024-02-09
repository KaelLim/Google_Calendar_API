<?php
session_start();

$client_id = 'your_own_client_id.apps.googleusercontent.com';
$client_secret = 'your_own_client_secret';
$redirect_uri = 'http://localhost:3000/callback.php';
$auth_url = 'https://accounts.google.com/o/oauth2/auth';
$token_url = 'https://oauth2.googleapis.com/token';

if (isset($_GET['code'])) {
    // 授权码可以从回调请求中获取
    $code = $_GET['code'];
    
    // 使用授权码获取access token
    $token = getToken($code, $client_id, $client_secret, $redirect_uri, $token_url);

} else {
// 设置scope
    $scopes = 'https://www.googleapis.com/auth/calendar';

    // 构建授权URL
    $auth_url = $auth_url . '?response_type=code&access_type=offline&client_id=' . $client_id . '&redirect_uri=' . urlencode($redirect_uri) . '&scope=' . urlencode($scopes);

    // 重定向到Google的OAuth服务器
    header('Location: ' . $auth_url);
    exit;
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
        die('Error retrieving token: ' . $response);
    }
}
?>
