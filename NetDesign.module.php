<?php

class NetDesign extends CMSModule {
    function GetVersion() {
        return '0.1';
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

}