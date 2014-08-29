# NetDesign core module

## Description

This module serves as the base for all other modules.

It also ensures that we can have multiple websites running on a single CMS Made Simple installation for development.
Switching sites is available in the module admin page.

## Coding conventions

If a child module needs to have files in the site directory it should do it like in the following example used by the Mapper module:

```
<docroot>/netdesign/modules/Mapper/lang/en_US.php
<docroot>/netdesign/modules/Mapper/lang/ext/nl_NL.php
<docroot>/netdesign/modules/Mapper/maps/MapNews.php
```

Files uploaded by the user should be put in the uploads directory as in the following example used by the PhotoGallery module:

```
<docroot>/uploads/.PhotoGallery/<gallery id>/uploadedfile1.jpg
<docroot>/uploads/.PhotoGallery/<gallery id>/uploadedfile2.jpg
<docroot>/uploads/.PhotoGallery/<gallery id>/uploadedfile3.jpg
```

As you can see, all files are in a hidden directory so the end user doesn't see them in his file manager, but they can be easily copied
to a new installation if/when needed.

See the methods ```GetModuleUploadsPath()``` and ```GetModuleUploadsUrl()```.

## Developer functions

Several methods are made available through this module. These methods are the ones that are documented in the source code.

## Module settings

Child modules can register with this module to have their settings shown in the admin interface of NetDesign CMS. For an example, see PhotoGallery::__construct().
Associated methods for this are:

- GenerateSetting
- RegisterSetting
- GetSetting

## Module actions

### Default

The default action should be called on top of every page template as it does the following basic tasks:

1. It serves the browser the X-UA-Compatible header to make sure Internet Explorer always runs in standards mode for the latest version.
2. It executes the CMS Made Simple "process_pagedata" tag.

An optional argument "template" can be given to render that Smarty template (residing in docroot/netdesign/site_id/templates directory).

### Site actions

Custom actions can be put in the site directory The default action is written so that if a parameter site_action is set, it doesn't perform
the above-mentioned default action, but simply passes everything through to the site action. Some example code below:

```
<!-- netdesign/mysite.com/templates/helloword.tpl -->
<p>Hello, {$who}!</p>
```

```php
// netdesign/mysite.com/actions/action.helloword.php
/** @var NetDesign $this */
if (!isset($gCms)) exit;
$this->smarty->assign('who', $params['who']);
echo $this->smarty->fetch($this->GetSiteResource('helloword.tpl'));
```

Now somewhere in a page just include ```{NetDesign site_action="helloword" who="world"}```.

### Template

Renders the site template given by the argument "template". The template should reside in docroot/netdesign/site_id/templates directory.

### Meta

Add default metadata to the HTML head.

```
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>{title} | {sitename}</title>
{if isset($canonical)}<link rel="canonical" href="{$canonical}" />{elseif isset($content_obj)}<link rel="canonical" href="{$content_obj->GetURL()}" />{/if}
{cms_stylesheet}
{metadata}
{cms_selflink dir="start" rellink=1}
{cms_selflink dir="prev" rellink=1}
{cms_selflink dir="next" rellink=1}
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
```