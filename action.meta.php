<?php

/** @var NetDesign $this */
if (!isset($gCms)) exit;

echo $this->smarty->fetch($this->GetFileResource('meta.tpl'));