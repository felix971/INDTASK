<?php
header('Content-Type: application/json');
echo json_encode([
    'message' => 'PHP在Vercel上工作正常！',
    'timestamp' => date('Y-m-d H:i:s'),
    'status' => 'success'
]);
?>