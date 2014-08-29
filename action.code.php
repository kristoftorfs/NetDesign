<?php

/** @var NetDesign $this */
if (!isset($gCms)) exit;
if (!array_key_exists('filename', $params)) $params['filename'] = '';
$this->IncludeSitePhp($params['filename']);