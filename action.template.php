<?php

/** @var NetDesign $this */
if (!isset($gCms)) exit;

if (!array_key_exists('template', $params)) $params['template'] = '';
$tpl = sprintf('file:%s/netdesign/%s', cmsms()->GetConfig()->offsetGet('root_path'), $params['template']);
if ($this->smarty->template_exists($tpl)) {
    $old = $this->smarty->get_template_vars('templateUrl');
    $url = sprintf('%s/netdesign/%s', cmsms()->GetConfig()->offsetGet('root_url'), array_shift(explode('/', $params['template'])));
    $this->smarty->assign('templateUrl', $url);
    echo $this->smarty->fetch($tpl);
    $this->smarty->assign('templateUrl', $old);
}