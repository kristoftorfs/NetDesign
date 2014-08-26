<?php

/** @var NetDesign $this */
if (!isset($gCms)) exit;

$this->SendHeaders();
// Show our default template (process_pagedata)
echo $this->smarty->fetch($this->GetFileResource('default.tpl'));
// If a custom template is set, show that as well
if (array_key_exists('template', $params)) $this->DisplayTemplate($params['template']);