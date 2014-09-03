<?php

/** @var NetDesign $this */

if (!isset($gCms)) exit;
// Create permission(s)
$this->CreatePermission('NetDesign.usage', 'NetDesign CMS: Manage NetDesign CMS settings');
// Copy imagecache.php to the uploads directory
$src = cms_join_path($this->GetModulePath(), 'imagecache.php');
$dst = cms_join_path($this->config['uploads_path'], 'imagecache.php');
copy($src, $dst);

$cfg = array(
    'design' => array('name' => 'NetDesign CMS'),
    'template' => array('name' => 'NetDesign CMS Template', 'content' => '{NetDesign template="design.tpl"}{capture assign=$content}{content}{/capture}'),
    'navigation' => array('name' => 'NetDesign CMS Navigation', 'content' => '{NetDesign action="template" template="navigation.tpl"}')
);

if (NetDesign::GetCMSVersion() == 1) {
    // Unset default for all existing templates
    $tpls = TemplateOperations::get_instance()->LoadTemplates();
    foreach($tpls as $tpl) {
        /** @var Template $tpl */
        $tpl->default = 0;
        $tpl->Save();
    }
    unset($tpls);
    // Create template
    $tpl = TemplateOperations::get_instance()->LoadTemplateByContentAlias($cfg['template']['name']);
    if (!($tpl instanceof Template)) {
        $tpl = new Template();
        $tpl->name = $cfg['template']['name'];
        $tpl->content = $cfg['template']['content'];
    }
    $tpl->default = true;
    $tpl->active = true;
    $tpl->Save();
    // Create navigation
    /** @var ModuleOperations $mops */
    $mops = ModuleOperations::get_instance();
    /** @var MenuManager $mod */
    $mod = $mops->get_module_instance('MenuManager');
    $mod->SetTemplate($cfg['navigation']['name'], $cfg['navigation']['content']);
    $mod->SetPreference('default_template', $cfg['navigation']['name']);
    // Set the template for every existing page
    $pages = ContentOperations::get_instance()->GetAllContent();
    foreach($pages as $page) {
        /** @var ContentBase $page */
        $page->SetTemplateId($tpl->Id());
        $page->Save();
    }
} else {
    // Create design
    // 1. Design
    try {
        $design = new CmsLayoutCollection();
        $design->set_name($cfg['design']['name']);
        $design->set_default(true);
        $design->save();
    } catch (Exception $e) {
    }
    // 2. Template
    try {
        $tpl = new CmsLayoutTemplate();
        $tpl->set_type(CmsLayoutTemplateType::load(sprintf('%s::page', CmsLayoutTemplateType::CORE)));
        $tpl->set_name($cfg['template']['name']);
        $tpl->set_content($cfg['template']['content']);
        $tpl->set_type_dflt(true);
        $tpl->set_designs(array($design->get_id()));
        $tpl->save();
    } catch (Exception $e) {
    }
    // 3. Navigation
    try {
        $menu = new CmsLayoutTemplate();
        $menu->set_type(CmsLayoutTemplateType::load('Navigator::navigation'));
        $tpl->set_name($cfg['navigation']['name']);
        $tpl->set_content($cfg['navigation']['content']);
        $menu->set_type_dflt(true);
        $menu->set_designs(array($design->get_id()));
        $menu->save();
    } catch (Exception $e) {
    }
    try {
        // 4. Set the design and template for every existing page
        $pages = ContentOperations::get_instance()->GetAllContent();
        foreach($pages as $page) {
            /** @var ContentBase $page */
            $page->SetPropertyValue('design_id', $design->get_id());
            $page->SetTemplateId($tpl->get_id());
            $page->Save();
        }
    } catch (Exception $e) {
    }
}