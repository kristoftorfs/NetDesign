<?php

if (!isset($gCms)) exit;
// Remove permission(s)
$this->RemovePermission('NetDesign.usage');
// Remove imagecache.php from the uploads directory
$dst = cms_join_path($this->config['uploads_path'], 'imagecache.php');
@unlink($dst);