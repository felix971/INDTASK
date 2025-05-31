<?php
class SimpleDatabase {
    private $db;
    
    public function __construct() {
        // 使用SQLite，无需外部数据库
        $this->db = new PDO('sqlite:/tmp/strata.db');
        $this->initTables();
    }
    
    private function initTables() {
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY,
                username TEXT UNIQUE,
                password TEXT,
                role TEXT
            )
        ");
        
        // 插入测试用户
        $stmt = $this->db->prepare("INSERT OR IGNORE INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->execute(['admin', password_hash('admin123', PASSWORD_DEFAULT), 'admin']);
        $stmt->execute(['owner1', password_hash('owner123', PASSWORD_DEFAULT), 'owner']);
    }
    
    public function verifyUser($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}

// 测试数据库
$db = new SimpleDatabase();
echo "数据库初始化成功！";
?>