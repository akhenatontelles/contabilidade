<?php

require_once '../app/models/SharedLink.php';
require_once '../app/models/File.php';
require_once '../app/models/Folder.php';

class ShareController
{
    public function createLink()
    {
        if (isset($_GET['file_id']) || isset($_GET['folder_id'])) {
            $type = isset($_GET['file_id']) ? 'file' : 'folder';
            $id = $_GET['file_id'] ?? $_GET['folder_id'];

            $token = bin2hex(random_bytes(16));

            $sharedLinkModel = new SharedLink();
            $linkData = [
                'file_id' => $type === 'file' ? $id : null,
                'folder_id' => $type === 'folder' ? $id : null,
                'tipo' => $type,
                'token' => $token,
            ];
            $sharedLinkModel->create($linkData);

            // Return the link to the user (e.g., as JSON)
            echo json_encode(['link' => "/share?token=$token"]);
        }
    }

    public function accessLink()
    {
        if (isset($_GET['token'])) {
            $token = $_GET['token'];
            $sharedLinkModel = new SharedLink();
            $link = $sharedLinkModel->findByToken($token);

            if ($link) {
                // Here you would display the shared file or folder
                // For now, just a placeholder
                echo "Accessing shared content...";
            } else {
                echo "Link not found or expired.";
            }
        }
    }
}
