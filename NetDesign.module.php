<?php

/**
 * @property ADOConnection $db
 * @property Smarty $smarty
 * @property CmsApp $cms
 * @property array $config
 */
class NetDesign extends CMSModule {
    function GetVersion() {
        return '1.0.0';
    }

    protected function InitializeFrontend() {
        $this->RegisterModulePlugin();
    }

    protected function InitializeAdmin() {
        $this->RegisterModulePlugin();
    }

    function GetAuthor() {
        return 'Kristof Torfs';
    }

    function GetAuthorEmail() {
        return 'kristof@torfs.org';
    }

    function HasAdmin() {
        return true;
    }

    function GetAdminSection() {
        return 'extensions';
    }

    function IsPluginModule() {
        return true;
    }

    public function GetModuleId() {
        $id = 'm1_';
        if (isset($_REQUEST['id'])) $id = $_REQUEST['id'];
        elseif (isset($_REQUEST['mact'])) list($dummy, $id) = explode(',', $_REQUEST['mact']);
        return $id;
    }

    protected function GetTable($table = null) {
        $ret = sprintf('%smodule_%s', cms_db_prefix(), strtolower(get_class($this)));
        if (!empty($table)) $ret .= '_' . $table;
        return $ret;
    }

    public function AssignLang() {
        $this->smarty->assign('lang', current($this->langhash));
    }

}