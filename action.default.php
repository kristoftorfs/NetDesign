<?php

/** @var NetDesign $this */
if (!isset($gCms)) exit;

if (!array_key_exists('site_action', $params)) {
    $this->SendHeaders();
    // Show our default template (process_pagedata)
    echo $this->smarty->fetch($this->GetFileResource('default.tpl'));
    // If a site template is set, show that as well
    if (array_key_exists('template', $params)) $this->DoAction('template', $id, $params, $returnid);
} else {
    $this->DoSiteAction($params['site_action'], $id, $params, $returnid);
}