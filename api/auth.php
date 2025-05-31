<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once 'database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? $_POST['action'] ?? '';
    
    switch($action) {
        case 'login':
            $username = $data['username'] ?? '';
            $password = $data['password'] ?? '';
            
            if ($username && $password) {
                $db = new Database();
                $pdo = $db->getConnection();
                
                $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
                $stmt->execute([$username]);
                $user = $stmt->fetch();
                
                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    
                    setcookie('user_login', $user['username'], time() + (86400 * 30), '/');
                    
                    echo json_encode(['success' => true, 'user' => $user]);
                } else {
                    echo json_encode(['success' => false, 'error' => '用户名或密码错误']);
                }
            } else {
                echo json_encode(['success' => false, 'error' => '请填写用户名和密码']);
            }
            break;
            
        case 'logout':
            session_destroy();
            setcookie('user_login', '', time() - 3600, '/');
            echo json_encode(['success' => true]);
            break;
            
        case 'check':
            if (isset($_SESSION['user_id'])) {
                echo json_encode(['logged_in' => true, 'user' => $_SESSION]);
            } else {
                echo json_encode(['logged_in' => false]);
            }
            break;
            
        default:
            echo json_encode(['success' => false, 'error' => '无效的操作']);
    }
} else {
    echo json_encode(['success' => false, 'error' => '仅支持POST请求']);
}
?>