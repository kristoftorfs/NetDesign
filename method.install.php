<?php

if (!isset($gCms)) exit;
// Create permission(s)
$this->CreatePermission('NetDesign.usage', 'NetDesign CMS: Manage NetDesign CMS settings');
// Copy imagecache.php to the uploads directory
$src = cms_join_path($this->GetModulePath(), 'imagecache.php');
$dst = cms_join_path($this->config['uploads_path'], 'imagecache.php');
copy($src, $dst);