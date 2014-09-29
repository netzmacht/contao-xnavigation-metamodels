
MetaModels integration for contao-xnavigation
=====

This extension integrates [MetaModels](now.metamodel.me) into the highly flexibile extended navigation extension 
[contao-xnavigation](https://github.com/bit3/contao-xnavigation/). 

It provides a new item provider and new conditions to work with MetaModels.

Installation
---------

* Install the composer plugin for Contao
* Install netzmacht/contao-xnavigation-metamodels


Usage
----------

* Create a render setting for the metamodel. this will be used to render the navigation link.
* **Important**: Choose one of the template `metamodel_xnav_item` or `metamodel_xnav_item_plain`
* Create a new item provider for the metamodel
* Choose the parent page and the created render setting.
* Add the created provider to you menu

That's it.