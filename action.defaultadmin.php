<?php

/** @var NetDesign $this */
if (!isset($gCms)) exit;
if (!$this->CheckPermission('NetDesign.usage')) exit;

// Form processing
if (array_key_exists('cancel', $params)) {
    $this->Redirect($id, 'defaultadmin', '', array('module_error' => $this->Lang('undone')));
} elseif (array_key_exists('apply', $params)) {
    foreach($params as $key => $value) {
        if (in_array($key, array('apply', 'action'))) continue;
        set_site_preference($key, trim($value));
    }
    // If the active site was changed we need to relink the language files
    $mops = ModuleOperations::get_instance();
    foreach($mops->GetInstalledModules() as $mod) {
        $mod = $mops->get_module_instance($mod);
        if (!($mod instanceof NetDesign)) continue;
        $mod->IncludeSiteLang(true);
    }
    $this->Redirect($id, 'defaultadmin', '', array('module_message' => $this->Lang('applied')));
}

// Get a list of available site ids
$site_ids = glob(sprintf('%s/netdesign/*', $this->config['root_path']), GLOB_ONLYDIR);
array_walk($site_ids, function(&$item) {
    $item = basename($item);
});
sort($site_ids);
$site_ids = array_combine($site_ids, $site_ids);

// Generate list of settings
$settings = array(
    // NetDesign module
    $this->Lang('general') => array(
        array('caption' => $this->Lang('setting.site_id'), 'input' => $this->CreateInputDropdown($id, 'NetDesign_site_id', $site_ids, -1, get_site_preference('NetDesign_site_id')))
    )
);
// Add settings registered by other modules
$settings = array_merge($settings, NetDesign::$settings);

// Generate form
$form = array(
    'start' => $this->CreateFormStart($id, 'defaultadmin', ''),
    'end' => $this->CreateFormEnd(),
    'submit' => $this->CreateInputSubmit($id, 'apply', $this->Lang('apply'), sprintf('title="%s"', htmlentities($this->Lang('apply.title')))),
    'cancel' => $this->CreateInputSubmit($id, 'cancel', $this->Lang('undo'), sprintf('title="%s"', htmlentities($this->Lang('undo.title'))))
);

// Show template
$this->AssignLang();
$this->smarty->assign('settings', $settings);
$this->smarty->assign('form', $form);
echo $this->smarty->fetch($this->GetFileResource('defaultadmin.tpl'));