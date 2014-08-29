<?php

/** @var NetDesign $this */
if (!isset($gCms)) exit;

$this->SendHeaders();
// Show our default template (process_pagedata)
echo $this->smarty->fetch($this->GetFileResource('default.tpl'));
// If a site template is set, show that as well
$this->DoAction('template', $id, $params, $returnid);