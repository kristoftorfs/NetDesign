<?php

/** @var NetDesign $this */
if (!isset($gCms)) exit;

if (!array_key_exists('template', $params)) $params['template'] = '';
echo $this->smarty->fetch($this->GetSiteResource($params['template']));