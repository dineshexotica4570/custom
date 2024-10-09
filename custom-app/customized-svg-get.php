<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $unique_id = isset($_POST['unique_id']) ? $_POST['unique_id'] : '';
    if (!empty($unique_id)) {
        $folderPath = 'uploads/' . $unique_id . '/';
        if (is_dir($folderPath)) {
            $svgFiles = glob($folderPath . '*.svg');
            $response = [];
            foreach ($svgFiles as $file) {
                $fileName = basename($file);
                $svgContent = file_get_contents($file); 
                $response[] = [
                    'file_name' => 'uploads/' .$unique_id.'/'.$fileName
                    
                ];
            }
            if (!empty($response)) {
                echo json_encode([
                    'status' => 'success',
                    'data' => $response
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No SVG files found in the folder.'
                ]);
            }
        } else {
           
            echo json_encode([
                'status' => 'error',
                'message' => 'Folder not found for the given unique_id.'
            ]);
        }
    } 
} 
?>
