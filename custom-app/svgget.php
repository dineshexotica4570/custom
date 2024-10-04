<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$directory = $keyword . '/'; 
$sidebar_dir = $keyword . '/'.'Sidebar/'; 
$files = glob($directory . "*.svg");
$files_sidebar = glob($sidebar_dir . "*.svg");
$response = [
    'main_folder' => $files ? array_map('basename', $files) : [],
    'sidebar' => $files_sidebar ? array_map('basename', $files_sidebar) : []
];
echo json_encode($response);
?>
