<?php

/** @var NetDesign $this */
if (!isset($gCms)) exit;

// Send some headers
header('X-UA-Compatible: IE=edge,chrome=1');
// Show our default template
echo $this->smarty->fetch($this->GetFileResource('default.tpl'));
// If a custom template is set, show that as well
if (!array_key_exists('template', $params)) $params['template'] = '';
$tpl = sprintf('file:%s/netdesign/%s', cmsms()->GetConfig()->offsetGet('root_path'), $params['template']);
if ($this->smarty->template_exists($tpl)) {
    $url = sprintf('%s/netdesign/%s', cmsms()->GetConfig()->offsetGet('root_url'), array_shift(explode('/', $params['template'])));
    $this->smarty->assign('templateUrl', $url);
    echo $this->smarty->fetch($tpl);
}