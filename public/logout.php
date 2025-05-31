<?php
session_start();

// 清除所有会话数据
session_destroy();

// 清除登录Cookie
setcookie('user_login', '', time() - 3600, '/');

// 重定向到登录页面
header('Location: login.php');
exit;
?>