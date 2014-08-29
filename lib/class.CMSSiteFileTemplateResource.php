<?php

class CMSSiteFileTemplateResource extends CMS_Fixed_Resource_Custom
{
    protected function fetch($name,&$source,&$mtime)
    {
        $source = null;
        $mtime = null;

        $config = cmsms()->GetConfig();
        $files = array();
        $file = cms_join_path(NetDesign::GetInstance()->GetSitePath(), 'templates', $name);
        if (!file_exists($file)) return;
        $source = @file_get_contents($file);
        $mtime = @filemtime($file);
        return;
        var_dump($file);
        exit;

        foreach( $files as $one ) {
            if( file_exists($one) ) {

                return;
            }
        }
    }
}