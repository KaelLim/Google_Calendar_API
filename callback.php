<?php
session_start();

$client_id = 'your_client_id.apps.googleusercontent.com';
$client_secret = 'your_client_secret';
$redirect_uri = 'http://localhost:3000/callback.php';
$token_url = 'https://oauth2.googleapis.com/token';

if (isset($_GET['code'])) {
    // 授权码可以从回调请求中获取
    $code = $_GET['code'];

    // 使用授权码获取access token
    $token = getToken($code, $client_id, $client_secret, $redirect_uri, $token_url);

    // 将token存储为JSON
    $token_data = array(
        'access_token' => $token->access_token,
        'expires_in' => $token->expires_in, // Token的有效期（秒）
        'created' => time() // 当前时间戳
    );

    if (isset($token->refresh_token)) {
        $token_data['refresh_token'] = $token->refresh_token;
    }

    // 将Token数据转换为JSON格式
    $json_token = json_encode($token_data);

    // 定义存储JSON的文件路径（确保这个路径是安全的且PHP有写入权限）
    $file_path = 'token.json';

    // 将JSON数据写入文件
    file_put_contents($file_path, $json_token);

    // 可选：重定向到应用的另一个页面或显示成功消息
    // header('Location: success_page.php');
    // exit;
} else {
    // 如果没有code参数，显示错误或重定向到初始登录页面
    echo 'Authorization code not received.';
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
