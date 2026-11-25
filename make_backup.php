<?php

$backupDir = __DIR__ . '/backup';
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0777, true);
}

// 1. Create Lightweight Source & Database Backup (Tanpa vendor)
$zipSourcePath = $backupDir . '/SchoolCare_Source_Dan_Database.zip';
if (file_exists($zipSourcePath)) {
    unlink($zipSourcePath);
}

$zipSource = new ZipArchive();
if ($zipSource->open($zipSourcePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
    $baseDir = __DIR__;
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($baseDir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($iterator as $file) {
        if ($file->isDir()) {
            continue;
        }

        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($baseDir) + 1);
        $relativePathStandard = str_replace('\\', '/', $relativePath);

        // Exclude vendor, backup, and git
        if (
            str_starts_with($relativePathStandard, 'vendor/') ||
            str_starts_with($relativePathStandard, 'backup/') ||
            str_starts_with($relativePathStandard, '.git/')
        ) {
            continue;
        }

        $zipSource->addFile($filePath, $relativePathStandard);
    }

    $zipSource->close();
    echo "1. Backup Source & Database berhasil: " . round(filesize($zipSourcePath) / 1024, 2) . " KB\n";
}

// 2. Create Full Package Backup (Termasuk vendor)
$zipFullPath = $backupDir . '/SchoolCare_Full_Package_Termasuk_Vendor.zip';
if (file_exists($zipFullPath)) {
    unlink($zipFullPath);
}

$zipFull = new ZipArchive();
if ($zipFull->open($zipFullPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
    $baseDir = __DIR__;
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($baseDir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($iterator as $file) {
        if ($file->isDir()) {
            continue;
        }

        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($baseDir) + 1);
        $relativePathStandard = str_replace('\\', '/', $relativePath);

        // Exclude only backup and git
        if (
            str_starts_with($relativePathStandard, 'backup/') ||
            str_starts_with($relativePathStandard, '.git/')
        ) {
            continue;
        }

        $zipFull->addFile($filePath, $relativePathStandard);
    }

    $zipFull->close();
    echo "2. Backup Full Package (dengan vendor) berhasil: " . round(filesize($zipFullPath) / (1024 * 1024), 2) . " MB\n";
}
