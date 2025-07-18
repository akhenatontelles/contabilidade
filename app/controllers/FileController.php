<?php

require_once '../app/models/File.php';
require_once '../app/models/Folder.php';

class FileController
{
    // ... (código existente)

    public function downloadFolder()
    {
        if (isset($_GET['id'])) {
            $folderId = $_GET['id'];
            $folderModel = new Folder();
            $folder = $folderModel->findById($folderId); // You'll need to create this method

            if ($folder) {
                $zip = new ZipArchive();
                $zipFileName = sys_get_temp_dir() . '/' . $folder['nome'] . '.zip';

                if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                    $this->addFolderToZip($folderId, $zip);
                    $zip->close();

                    header('Content-Type: application/zip');
                    header('Content-Disposition: attachment; filename="' . basename($zipFileName) . '"');
                    header('Content-Length: ' . filesize($zipFileName));
                    readfile($zipFileName);
                    unlink($zipFileName); // Clean up the temporary file
                }
            }
        }
    }

    private function addFolderToZip($folderId, &$zip, $parentPath = '')
    {
        $fileModel = new File();
        $folderModel = new Folder();

        $files = $fileModel->findByUserAndFolder($_SESSION['user_id'], $folderId);
        foreach ($files as $file) {
            $zip->addFile($file['path'], $parentPath . $file['nome'] . '.' . $file['extensao']);
        }

        $subFolders = $folderModel->findByUserAndParent($_SESSION['user_id'], $folderId);
        foreach ($subFolders as $subFolder) {
            $this->addFolderToZip($subFolder['id'], $zip, $parentPath . $subFolder['nome'] . '/');
        }
    }
}
