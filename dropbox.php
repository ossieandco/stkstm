<?php
$accessToken = 'YOUR_ACCESS_TOKEN';
$folderPath = '/Gallery';
$url = 'https://api.dropboxapi.com/2/files/list_folder';

$data = array(
    'path' => $folderPath,
    'recursive' => false,
    'include_media_info' => true,
    'include_deleted' => false,
    'include_has_explicit_shared_members' => false,
    'include_mounted_folders' => true
);

$options = array(
    'http' => array(
        'header' => "Authorization: Bearer " . $accessToken . "\r\n" .
                    "Content-Type: application/json\r\n",
        'method' => 'POST',
        'content' => json_encode($data)
    )
);

$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);
echo $result;
?>
