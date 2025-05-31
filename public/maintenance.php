<?php
session_start();
require_once '../api/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$db = new Database();
$pdo = $db->getConnection();

// 处理添加新维护请求
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_request'])) {
    $unit_number = $_POST['unit_number'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];
    
    $stmt = $pdo->prepare("INSERT INTO maintenance_requests (unit_number, description, priority) VALUES (?, ?, ?)");
    $stmt->execute([$unit_number, $description, $priority]);
    
    header('Location: maintenance.php?success=1');
    exit;
}

// 处理状态更新
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $request_id = $_POST['request_id'];
    $new_status = $_POST['new_status'];
    
    $stmt = $pdo->prepare("UPDATE maintenance_requests SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $request_id]);
    
    header('Location: maintenance.php?updated=1');
    exit;
}

// 获取所有维护请求
$requests = $pdo->query("SELECT * FROM maintenance_requests ORDER BY created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>维护请求 - Strata Management</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <nav class="sidebar">
            <h2>Strata Management</h2>
            <ul>
                <li><a href="dashboard.php">仪表板</a></li>
                <li><a href="residents.php">住户管理</a></li>
                <li><a href="maintenance.php" class="active">维护请求</a></li>
                <li><a href="levies.php">征收费用</a></li>
                <li><a href="documents.php">文档管理</a></li>
                <li><a href="budget.php">预算管理</a></li>
                <li><a href="logout.php">退出登录</a></li>
            </ul>
        </nav>
        
        <main class="main-content">
            <h1>维护请求管理</h1>
            
            <?php if (isset($_GET['success'])): ?>
                <div class="success">维护请求已成功提交！</div>
            <?php endif; ?>
            
            <?php if (isset($_GET['updated'])): ?>
                <div class="success">维护请求状态已更新！</div>
            <?php endif; ?>
            
            <div class="form-section">
                <h2>提交新的维护请求</h2>
                <form method="POST" action="">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="unit_number">单元号:</label>
                            <input type="text" id="unit_number" name="unit_number" required>
                        </div>
                        <div class="form-group">
                            <label for="priority">优先级:</label>
                            <select id="priority" name="priority" required>
                                <option value="low">低</option>
                                <option value="medium" selected>中</option>
                                <option value="high">高</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">问题描述:</label>
                        <textarea id="description" name="description" rows="4" required></textarea>
                    </div>
                    <button type="submit" name="add_request">提交请求</button>
                </form>
            </div>
            
            <div class="table-section">
                <h2>维护请求列表</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>单元号</th>
                            <th>描述</th>
                            <th>优先级</th>
                            <th>当前状态</th>
                            <th>创建时间</th>
                            <th>状态操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requests as $request): ?>
                        <tr>
                            <td><?php echo $request['id']; ?></td>
                            <td><?php echo htmlspecialchars($request['unit_number']); ?></td>
                            <td><?php echo htmlspecialchars(substr($request['description'], 0, 100)); ?>...</td>
                            <td><span class="priority-<?php echo $request['priority']; ?>"><?php echo $request['priority']; ?></span></td>
                            <td><span class="status-<?php echo $request['status']; ?>"><?php echo $request['status']; ?></span></td>
                            <td><?php echo date('Y-m-d H:i', strtotime($request['created_at'])); ?></td>
                            <td>
                                <form method="POST" action="" style="display: inline;">
                                    <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                    <select name="new_status" onchange="this.form.submit()">
                                        <option value="pending" <?php echo $request['status'] === 'pending' ? 'selected' : ''; ?>>待处理</option>
                                        <option value="in_progress" <?php echo $request['status'] === 'in_progress' ? 'selected' : ''; ?>>进行中</option>
                                        <option value="completed" <?php echo $request['status'] === 'completed' ? 'selected' : ''; ?>>已完成</option>
                                    </select>
                                    <input type="hidden" name="update_status" value="1">
                                </form>
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