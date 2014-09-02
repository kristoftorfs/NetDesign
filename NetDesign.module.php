<?php

// Register Smarty resource
require_once(__DIR__ . '/lib/class.CMSSiteFileTemplateResource.php');
cmsms()->GetSmarty()->registerResource('site_file_tpl', new CMSSiteFileTemplateResource());

/**
 * @property ADOConnection $db
 * @property Smarty $smarty
 * @property CmsApp $cms
 * @property array $config
 */
class NetDesign extends CMSModule {
    private static $loaders = array();
    protected static $settings = array();

    function __construct() {
        parent::__construct();
        spl_autoload_register(function($class) {
            foreach(NetDesign::$loaders as $loader) {
                list($directory, $pattern) = $loader;
                if (!fnmatch($pattern, $class)) continue;
                $fn = cms_join_path($directory, sprintf('class.%s.php', $class));
                if (!file_exists($fn)) continue;
                require_once($fn);
            }
        }, true);
    }

    function GetFriendlyName() {
        return $this->Lang('friendlyname');
    }

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
        return 'siteadmin';
    }

    function VisibleToAdminUser() {
        return $this->CheckPermission('usage');
    }

    function IsPluginModule() {
        return true;
    }

    /**
     * Convenience function to easily get an instance of a module, with code insight.
     *
     * @return static
     */
    public static function GetInstance() {
        return cms_utils::get_module(get_called_class());
    }

    /**
     * Returns the CMSMS module id e.g. m1_
     *
     * @return string
     */
    public function GetModuleId() {
        $id = 'm1_';
        if (isset($_REQUEST['id'])) $id = $_REQUEST['id'];
        elseif (isset($_REQUEST['mact'])) list($dummy, $id) = explode(',', $_REQUEST['mact']);
        return $id;
    }

    /**
     * Returns the base URL for this module when using imagecache.php. E.g. for Mapper this is 'http://root_url/uploads/imagecache.php/.Mapper'.
     *
     * @return string
     */
    public function GetImageCacheUrl() {
        return sprintf('%s/imagecache.php/.%s', $this->config['uploads_url'], get_class($this));
    }

    /**
     * Returns a generated table name for the module.
     *
     * Examples:
     * - when called from module Mapper and $table set to MapNews it will return '<cmsms_db_prefix>module_mapper_MapNews'
     * - when called from module Mapper and $table is omitted it will return '<cmsms_db_prefix>module_mapper'
     *
     * @param string $table Optional suffix.
     * @param string $module Optional module name. Defaults to the class name of the called module.
     * @return string
     */
    final public function GetTable($table = null, $module = null) {
        if (!empty($module)) $class = $module;
        else $class = get_class($this);
        $ret = sprintf('%smodule_%s', cms_db_prefix(), strtolower($class));
        if (!empty($table)) $ret .= '_' . $table;
        return $ret;
    }

    /**
     * Assigns all language strings for this module to a Smarty variable called $lang.
     */
    final public function AssignLang() {
        $this->smarty->assign('lang', current($this->langhash));
    }

    /**
     * Serves the browser the X-UA-Compatible header to make sure Internet Explorer always runs in standards mode for the latest version.
     */
    final public function SendHeaders() {
        header('X-UA-Compatible: IE=edge,chrome=1');
    }

    /**
     * Returns the site id set in config.php ($config['netdesign']).
     *
     * @return string
     */
    final public function GetSiteId() {
        return (string)get_site_preference('NetDesign_site_id');
    }

    /**
     * Returns the filesystem path to the site directory.
     *
     * @return string
     */
    final public function GetSitePath() {
        return cms_join_path($this->config['root_path'], 'netdesign', $this->GetSiteId());
    }

    /**
     * Returns the filesystem path to the module directory in the site.
     *
     * @return string
     */
    final public function GetSiteModulePath() {
        return cms_join_path($this->config['root_path'], 'netdesign', $this->GetSiteId(), 'modules', $this->GetName());
    }

    /**
     * Returns the URL to the site directory.
     *
     * @return string
     */
    final public function GetSiteUrl() {
        return sprintf('%s/netdesign/%s', $this->config['root_url'], $this->GetSiteId());
    }

    /**
     * Assigns the site_id and site_url variables to Smarty. This function is automatically called in GetSiteResource.
     */
    public function AssignSiteVars() {
        $this->smarty->assign('site_id', $this->GetSiteId());
        $this->smarty->assign('site_url', $this->GetSiteUrl());
    }

    /**
     * Returns a Smarty resource for a site template.
     *
     * @param string $template
     * @return string
     */
    final public function GetSiteResource($template) {
        $this->AssignSiteVars();
        return sprintf('site_file_tpl:%s', $template);
    }

    /**
     * Returns the filesystem path to the (hidden) uploads-directory for this module.
     *
     * @return string
     */
    final public function GetModuleUploadsPath() {
        return cms_join_path($this->config['uploads_path'], sprintf('.%s', $this->GetName()));
    }

    /**
     * Returns the filesystem path to the (hidden) uploads-directory for this module.
     *
     * @return string
     */
    final public function GetModuleUploadsUrl() {
        return cms_join_path($this->config['uploads_url'], sprintf('.%s', $this->GetName()));
    }

    /**
     * Dev tool: var_dump but wraps it in a pre tag.
     *
     * @param $var
     */
    final public function VarDump($var) {
        echo "<pre>";
        call_user_func_array('var_dump', func_get_args());
        echo "</pre>";
    }

    /**
     * Includes the language files from the module site directory.
     */
    final public function IncludeSiteLang() {
        $inc = sprintf('%s/lang/%s.php', $this->GetSiteModulePath(), CmsNlsOperations::get_current_language());
        if (!file_exists($inc)) return;
        require_once($inc);
        if (!isset($lang)) return;
        if (!array_key_exists(CmsNlsOperations::get_current_language(), $this->langhash)) $this->langhash[CmsNlsOperations::get_current_language()] = array();
        $this->langhash[CmsNlsOperations::get_current_language()] = array_merge($this->langhash[CmsNlsOperations::get_current_language()], $lang);
    }

    /**
     * Register a directory to autoload classes matching $classPattern. Files should be named 'class.MyClassName.php'.
     *
     * @param string $directory
     * @param string $classPattern
     */
    final public function RegisterClassDirectory($directory, $classPattern = '*') {
        NetDesign::$loaders[] = array($directory, $classPattern);
    }

    /**
     * Includes all PHP files in the specified directory.
     *
     * @param string $directory
     * @param bool $recursive
     */
    final public function IncludeClassDirectory($directory, $recursive = true) {
        $files = array();
        if ($recursive === true) {
            $dir = new RecursiveDirectoryIterator($directory);
            $it = new RecursiveIteratorIterator($dir);
            $regex = new RegexIterator($it, '/^.+\.php$/i', RegexIterator::GET_MATCH);
            foreach($regex as $file) $files[] = $file[0];
        } else {
            $dir = new DirectoryIterator($directory);
            $it = new IteratorIterator($dir);
            $regex = new RegexIterator($it, '/^.+\.php$/i', RegexIterator::GET_MATCH);
            foreach($regex as $file) $files[] = cms_join_path($directory, $file[0]);
        }
        foreach($files as $file) require_once($file);
    }

    /**
     * Generates a name for an input used in the NetDesign settings.
     *
     * Follows the following convention:
     * MyModule_setting_name e.g. PhotoGallery_single_gallery
     *
     * @param string $name
     * @return string
     */
    final public function GenerateSetting($name) {
        return sprintf('%s_%s', get_class($this), trim(strtolower($name)));
    }

    /**
     * Registers a setting to be included in the admin interface of the NetDesign module.
     *
     * @param string $name The setting name as generated by GenerateSetting().
     * @param string $input The input created by $this->CreateInput... Use GenerateSetting() to get a name using the right conventions.
     * @param string $caption The (translated) caption for the input.
     * @param string
     */
    final public function RegisterSetting($name, $input, $caption) {
        $module = $this->GetFriendlyName();
        if (!array_key_exists($module, NetDesign::$settings)) NetDesign::$settings[$module] = array();
        NetDesign::$settings[$module][$name] = array('caption' => $caption, 'input' => $input);
        ksort(NetDesign::$settings);
    }

    /**
     * Clone of CMSModule::DoAction, but this executes an action in the site directory (netdesign/<side_id>/actions/action.<action>.php).
     *
     * @param string $name Name of the action to perform
     * @param string $id The ID of the module
     * @param string $params The parameters targeted for this module
     * @param int|string $returnid The current page id that is being displayed.
     * @return string output XHTML.
     */
    final function DoSiteAction($name, $id, $params, $returnid='') {
        $filename = cms_join_path($this->GetSitePath(), 'actions', sprintf('action.%s.php', $name));
        if (@is_file($filename)) {
            $gCms = cmsms();
            $db = $gCms->GetDb();
            $config = $gCms->GetConfig();
            $smarty = $gCms->GetSmarty();
            include($filename);
        }
    }

    /**
     * Returns the value of a setting set in the admin interface of the NetDesign module
     *
     * @param string $name The setting name as generated by GenerateSetting().
     * @param mixed $default
     */
    final public function GetSetting($name, $default = '') {
        return get_site_preference($name, $default);
    }
}