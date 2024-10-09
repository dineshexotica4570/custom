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
            function deleteFolder($folder) {
                $files = array_diff(scandir($folder), ['.', '..']);
                foreach ($files as $file) {
                    $filePath = $folder . '/' . $file;
                    if (is_dir($filePath)) {
                        deleteFolder($filePath);
                    } else {
                        unlink($filePath);
                    }
                }
                return rmdir($folder);
            }
            if (deleteFolder($folderPath)) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Folder and its contents deleted successfully.'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Failed to delete the folder.'
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
