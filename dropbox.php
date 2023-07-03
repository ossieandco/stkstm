<?php
$accessToken = 'sl.Bhg1hyIZAPnVZiAYHwtg1NggyNahaSr5RT_DfO-6QmiJNuswF7kXwV6MZLftRCSyn7W2hBFXzBR76e8qoavwAowOCh3ofUomHL7B49bzxkgEm1hfOpoL4YgFkIZZjV_qrxgLDJa6t8Ad';
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
$response = json_decode($result, true);

$entries = $response['entries'] ?? [];

$images = array();
foreach ($entries as $entry) {
    if ($entry['.tag'] === 'file' && isset($entry['media_info'])) {
        $thumbnailUrl = 'https://content.dropboxapi.com/2/files/get_thumbnail';
        $thumbnailData = array(
            'path' => $entry['path_display'],
            'format' => 'jpeg',
            'size' => 'w640h480'
        );

        $thumbnailOptions = array(
            'http' => array(
                'header' => "Authorization: Bearer " . $accessToken . "\r\n" .
                            "Content-Type: application/json\r\n",
                'method' => 'POST',
                'content' => json_encode($thumbnailData)
            )
        );

        $thumbnailContext = stream_context_create($thumbnailOptions);
        $thumbnailResult = file_get_contents($thumbnailUrl, false, $thumbnailContext);
        $thumbnailData = base64_encode($thumbnailResult);
        $thumbnailSrc = 'data:image/jpeg;base64,' . $thumbnailData;

        $images[] = array(
            'thumbnailLink' => $thumbnailSrc,
            'sharedLink' => $entry['path_lower']
        );
    }
}

echo json_encode($images);
?>