# NetDesign core module

## Description

This module serves as the base for all other modules.

It also ensures that we can have multiple websites running on a single CMS Made Simple installation for development.
Switching sites is available in the module admin page.

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

Several methods are made available through this module. See (documented) methods in the source code.

## Module actions

### Default

The default action should be called on top of every page template as it does the following basic tasks:

1. It serves the browser the X-UA-Compatible header to make sure Internet Explorer always runs in standards mode for the latest version.
2. It executes the CMS Made Simple "process_pagedata" tag.

An optional argument "template" can be given to render that Smarty template (residing in docroot/netdesign/site_id directory).

### Template

Renders the site template given by the argument "template". The template should reside in docroot/netdesign/site_id directory.