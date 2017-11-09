<?php

// GitHub Webhook Secret.
// Keep it the same with the 'Secret' field on your Webhooks / Manage webhook page of your respostory.
$secret = "";
// 项目根目录, 如: "/var/www/fizzday"
$path = "/data/wwwroot/dms";
// Headers deliveried from GitHub
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE'];
if ($signature) {
    $hash = "sha1=" . hash_hmac('sha1', $HTTP_RAW_POST_DATA, $secret);
    if (strcmp($signature, $hash) == 0) {
        echo shell_exec("cd {$path} && git pull");
        exit();
    }
}
http_response_code(404);
