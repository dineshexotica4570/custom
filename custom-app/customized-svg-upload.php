<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $unique_id = isset($_POST['unique_id']) ? $_POST['unique_id'] : '';
    $svg_data = isset($_POST['svg_data']) ? $_POST['svg_data'] : '';
    if (!empty($unique_id) && !empty($svg_data)) {
        $uploadDir = 'uploads/' . $unique_id . '/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $svgFiles = explode('---', $svg_data);
        $filePaths = [];
        foreach ($svgFiles as $index => $svgContent) {
            $fileName = $uploadDir . 'svg_' . ($index + 1) . '.svg';
            file_put_contents($fileName, $svgContent);
            $filePaths[] = $fileName;
        }
        echo json_encode([
        'status' => 'success', 
        'message' => 'SVG files uploaded successfully.',
        'svg_path' => $filePaths
    
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
