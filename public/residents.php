<?php
session_start();
require_once '../api/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$db = new Database();
$pdo = $db->getConnection();

// 处理添加新住户
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_resident'])) {
    $unit_number = $_POST['unit_number'];
    $owner_name = $_POST['owner_name'];
    $contact_email = $_POST['contact_email'];
    $contact_phone = $_POST['contact_phone'];
    $unit_entitlements = $_POST['unit_entitlements'];
    
    $stmt = $pdo->prepare("INSERT INTO residents (unit_number, owner_name, contact_email, contact_phone, unit_entitlements) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$unit_number, $owner_name, $contact_email, $contact_phone, $unit_entitlements]);
    
    header('Location: residents.php?success=1');
    exit;
}

// 获取所有住户
$residents = $pdo->query("SELECT * FROM residents ORDER BY unit_number")->fetchAll();
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>住户管理 - Strata Management</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <nav class="sidebar">
            <h2>Strata Management</h2>
            <ul>
                <li><a href="dashboard.php">仪表板</a></li>
                <li><a href="residents.php" class="active">住户管理</a></li>
                <li><a href="maintenance.php">维护请求</a></li>
                <li><a href="levies.php">征收费用</a></li>
                <li><a href="documents.php">文档管理</a></li>
                <li><a href="budget.php">预算管理</a></li>
                <li><a href="logout.php">退出登录</a></li>
            </ul>
        </nav>
        
        <main class="main-content">
            <h1>住户管理</h1>
            
            <?php if (isset($_GET['success'])): ?>
                <div class="success">住户信息已成功添加！</div>
            <?php endif; ?>
            
            <div class="form-section">
                <h2>添加新住户</h2>
                <form method="POST" action="">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="unit_number">单元号:</label>
                            <input type="text" id="unit_number" name="unit_number" required>
                        </div>
                        <div class="form-group">
                            <label for="owner_name">业主姓名:</label>
                            <input type="text" id="owner_name" name="owner_name" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="contact_email">联系邮箱:</label>
                            <input type="email" id="contact_email" name="contact_email">
                        </div>
                        <div class="form-group">
                            <label for="contact_phone">联系电话:</label>
                            <input type="tel" id="contact_phone" name="contact_phone">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="unit_entitlements">单元权益 (0-1):</label>
                        <input type="number" step="0.0001" min="0" max="1" id="unit_entitlements" name="unit_entitlements" required>
                    </div>
                    <button type="submit" name="add_resident">添加住户</button>
                </form>
            </div>
            
            <div class="table-section">
                <h2>住户列表</h2>
                <table>
                    <thead>
                        <tr>
                            <th>单元号</th>
                            <th>业主姓名</th>
                            <th>联系邮箱</th>
                            <th>联系电话</th>
                            <th>单元权益</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($residents as $resident): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($resident['unit_number']); ?></td>
                            <td><?php echo htmlspecialchars($resident['owner_name']); ?></td>
                            <td><?php echo htmlspecialchars($resident['contact_email']); ?></td>
                            <td><?php echo htmlspecialchars($resident['contact_phone']); ?></td>
                            <td><?php echo number_format($resident['unit_entitlements'] * 100, 2); ?>%</td>
                            <td>
                                <button class="btn-edit">编辑</button>
                                <button class="btn-delete">删除</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>