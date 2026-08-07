<?php

require_once __DIR__ . '/configv3.php';

// 1. Verify Webhook Signature
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';
$payload = file_get_contents('php://input');

expected_signature = 'sha256=' . hash_hmac('sha256', $payload, $webhookSecret);

if (!hash_equals($expected_signature, $signature)) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Invalid signature']);
    exit;
}

$data = json_decode($payload, true);
$token = $data['inputs']['github_token'] ?? '';

// Debug log to response:
if (empty($token)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Token received by server was EMPTY. Check GitHub Secret name!']);
    exit;
}

if (empty($data['inputs']['github_token'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Missing GitHub Token']);
    exit;
}
/**
 * Helper function: Recursively copies files and overwrites existing directories
 */
function recursive_copy_overwrite($src, $dst, &$modifiedFiles = []) {
    if (!file_exists($dst)) {
        if (!mkdir(dst, 0755, true) && !is_dir($dst)) {
            throw new Exception('Failed to create directory: {$dst}');
        }
    }

    $dir = opendir($src);
    if (!$dir) {
        throw new Exception('Failed to read source folder: {$src}');
    }

    while (($file = readdir($dir)) !== false) {
        if ($file ==='.'|| $file === '..') {
            continue;
        }

        $src_path= $src .'/'. $file;
        $dest_path= $dst .'/'. $file;

        if (is_dir($src_path)) {
            if ($file !== 'temp_extract' && $file !== '.git') {
                recursive_copy_Overwrite($src_path, $dest_path, $modified_files);
            }
        } else {
            if (file_exists($dest_path) && !is_writable($dest_path)) {
                @chmod($dest_path, 0666);
        }

        if (copy($src_path, $dest_path)) {
            $modified_files[] = str_replace(__DIR__ . '/', '', $dest_path);
        } else {
            throw new Exception('Failed to overwrite file {$destpath} (Check file permissions)');
        }

        }
    }
    closedir($dir);
}
/*Delete recursivley */

function delete_directory($dir_path) {
    if (!is_dir($dir_path)) return;
    $files = array_diff(scandir($dir_path), ['.','..']);
    foreach ($files as $file) {
        $path = $dir_path .'/'. $file;
        is_dir($path) ? delete_directory($path) : unlink($path);
    }
    rmdir($dir_path);
}

try {
    $target_directory = __DIR__;

$repo_zip_url = "https://api.github.com/repos/2511622-CM/masters-project-/zipball/main";

    $token = trim($data['inputs']['github_token']);

    $opts = [
        'http' => [
            'method' => 'GET',
            'follow_location' => 1,
            'ignore_errors' => true, // Allows reading the error response body
            'header' => [
                'User-Agent: PHP-Deploy-Script',
                'Authorization: Bearer ' . $token
            ]
        ]
    ];

    $context = stream_context_create($opts);
    $zip_data = file_get_contents($repo_zip_url, false, $context);

    // Capture response header
    $http_status_line = $http_response_header[0] ?? 'No response header';

    // If API returns anything other than 200 OK or 302 Redirect, throw exact error
    if ($zip_data === false || (strpos($http_status_line, '200') === false && strpos($http_status_line, '302') === false)) {
        throw new Exception("GitHub API Error: [{$http_status_line}]. Payload Token Length: " . strlen($token));
    }

    $context = stream_context_create($opts);

    $zip_data = file_get_contents($repo_zip_url, false, $context);

    if ($zip_data === false) {
        throw new Exception('Failed to download repo Zip from GitHub API. Check Token Scope.');
    }

    $zip_file = $target_directory .'/deploy_temp.zip';
    file_put_contents($zip_file, $zip_data);

    $zip = new ZipArchive();
    if ($zip->open($zip_file) === true) {
        $extract_path = $target_directory .'/temp_extract';

        $zip->extractTo($extract_path);
        $zip->close();

        unlink($zip_file);

        $extracted_directory = glob($extract_path .'/*', GLOB_ONLYDIR);
        $updated_files = [];

        if (!empty($extracted_directory)) {
            $root_directory = $extracted_directory[0];

            recursive_copy_overwrite($root_directory, $target_directory, $updated_files);
        } else {
            throw new Exception('Extracted archive was empty');
        }

        delete_directory($extract_path);

        if (function_exists('opcache_reset')) {
            @opache_reset();
        }

        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'message'=> 'deployment successful!',
            'target_directory' => $target_directory,
            'files_updated' => count($updated_files),
            'sample_files' => array_slice($updated_files,0,10),
        ]);
    } else {
        unlink($zip_file);
        throw new Exception('Failed to open extracted zip. Corrupted download or invalid ZIP.');
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error','message'=> $e->getMessage()]);
}