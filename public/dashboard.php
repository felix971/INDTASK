<?php
session_start();
require_once '../api/database.php';

// 检查用户是否登录
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$db = new Database();
$pdo = $db->getConnection();

// 获取统计数据
$stats = [
    'total_units' => $pdo->query("SELECT COUNT(*) FROM residents")->fetchColumn(),
    'pending_maintenance' => $pdo->query("SELECT COUNT(*) FROM maintenance_requests WHERE status = 'pending'")->fetchColumn(),
    'overdue_levies' => $pdo->query("SELECT COUNT(*) FROM levies WHERE status = 'overdue'")->fetchColumn(),
    'total_documents' => $pdo->query("SELECT COUNT(*) FROM documents")->fetchColumn()
];

// 获取最近的维护请求
$recent_maintenance = $pdo->query("SELECT * FROM maintenance_requests ORDER BY created_at DESC LIMIT 5")->fetchAll();
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>仪表板 - Strata Management</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <nav class="sidebar">
            <h2>Strata Management</h2>
            <ul>
                <li><a href="dashboard.php" class="active">仪表板</a></li>
                <li><a href="residents.php">住户管理</a></li>
                <li><a href="maintenance.php">维护请求</a></li>
                <li><a href="levies.php">征收费用</a></li>
                <li><a href="documents.php">文档管理</a></li>
                <li><a href="budget.php">预算管理</a></li>
                <li><a href="logout.php">退出登录</a></li>
            </ul>
        </nav>
        
        <main class="main-content">
            <h1>欢迎, <?php echo $_SESSION['username']; ?>!</h1>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>总单元数</h3>
                    <div class="stat-number"><?php echo $stats['total_units']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>待处理维护</h3>
                    <div class="stat-number"><?php echo $stats['pending_maintenance']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>逾期费用</h3>
                    <div class="stat-number"><?php echo $stats['overdue_levies']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>文档总数</h3>
                    <div class="stat-number"><?php echo $stats['total_documents']; ?></div>
                </div>
            </div>
            
            <div class="recent-activity">
                <h2>最近的维护请求</h2>
                <table>
                    <thead>
                        <tr>
                            <th>单元号</th>
                            <th>描述</th>
                            <th>优先级</th>
                            <th>状态</th>
                            <th>提交时间</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_maintenance as $request): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($request['unit_number']); ?></td>
                            <td><?php echo htmlspecialchars(substr($request['description'], 0, 50)); ?>...</td>
                            <td><span class="priority-<?php echo $request['priority']; ?>"><?php echo $request['priority']; ?></span></td>
                            <td><span class="status-<?php echo $request['status']; ?>"><?php echo $request['status']; ?></span></td>
                            <td><?php echo date('Y-m-d H:i', strtotime($request['created_at'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>