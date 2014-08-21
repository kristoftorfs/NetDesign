# NetDesign core module

## Description

This module serves as the base for all other modules.

## Module actions

### Default

The default action should be called on top of every page template as it does the following basic tasks:

1. It serves the browser the X-UA-Compatible header to make sure Internet Explorer always runs in standards mode for the latest version.
2. It executes the CMS Made Simple "process_pagedata" tag.

An optional argument "template" can be given to render that Smarty template (residing in docroot/netdesign directory).

### Template

Renders the template given by the argument "template". The template should reside in docroot/netdesign directory.