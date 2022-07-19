# WordPress Plugin Boilerplate

This repository offers an open source WordPress Plugin Template with many useful functions, specially to prepare basic widgets, short codes and settings page. The aim of this Boilerplate Template is to save time for WordPress Developers in building high-quality plugins.

## Contents

The WordPress Plugin Template Boilerplate includes the following files:

* `.gitignore`. Used to exclude certain files from the repository.
* `CHANGELOG.md`. The list of changes to the core project.
* `README.md`. The file that you’re currently reading.
* A `plugin-name` directory that contains the source code - a fully executable WordPress plugin.


## Features

* The WordPress Plugin Template Boilerplate is based on the [Plugin API](http://codex.wordpress.org/Plugin_API), [Coding Standards](http://codex.wordpress.org/WordPress_Coding_Standards), and [Documentation Standards](https://make.wordpress.org/core/handbook/best-practices/inline-documentation-standards/php/).
* Templates for WordPress widgets and shortcodes. 
* Template to create fast-loading setting page.
* Includes EDD Software Licensing System, which allows you to sell your plugin by using Easy Digital Downloads and manage future access to plugin updates.
* All classes, functions, and variables are documented
* The WordPress Plugin Template Boilerplate uses a strict file organization scheme corresponding to the WordPress Plugin Repository structure. This makes it easy to organize the files of your plugin.
* The plugin template includes a `.pot` file as a starting point for internationalization.


## Installation

The WordPress Plugin Template Boilerplate can be installed directly into your plugins folder "as-is". You will want to rename it and the classes inside of it to fit your needs. For example, if your plugin is named 'example-me' then:

* rename files from `plugin-name` to `example-me`
* change `plugin_name` to `example_me`
* change `plugin-name` to `example-me`
* change `Plugin_Name` to `Example_Me`
* change `PLUGIN_NAME_` to `EXAMPLE_ME_`

It's safe to activate the plugin at this point. Because the WordPress Plugin Template Boilerplate has no real functionality there will be no menu items, meta boxes, or custom post types added until you write the code.


## License

The WordPress Plugin Template Boilerplate is licensed under the GPL v2 or later.

> This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License, version 2, as published by the Free Software Foundation.

> This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

> You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA

A copy of the license is included in the root of the plugin’s directory. The file is named `LICENSE`.

## Important Notes

### Licensing

The WordPress Plugin Template Boilerplate is licensed under the GPL v2 or later; however, if you opt to use third-party code that is not compatible with v2, then you may need to switch to using code that is GPL v3 compatible.

For reference, [here's a discussion](http://make.wordpress.org/themes/2013/03/04/licensing-note-apache-and-gpl/) that covers the Apache 2.0 License used by [Bootstrap](http://twitter.github.io/bootstrap/).

### Includes

Note that if you include your own classes, or third-party libraries, there are three locations in which said files may go:

* `plugin-name/includes` is where functionality shared between the admin area and the public-facing parts of the site reside
* `plugin-name/admin` is for all admin-specific functionality
* `plugin-name/public` is for all public-facing functionality

Note that previous versions of the WordPress Plugin Template Boilerplate did not include `Plugin_Name_Loader` but this class is used to register all filters and actions with WordPress.

The example code provided shows how to register your hooks with the Loader class.

# Credits

The WordPress Plugin Boilerplate was started in 2022 under the Company [Plugins & Snippets](https://www.pluginsandsnippets.com).

The current version of the Boilerplate was developed in conjunction by [Dilip Sakariya](https://github.com/dilipsakariya), and [Siawa Ahmed](https://github.com/siawaahmed).

## Documentation, FAQs, and More

If you’re interested in writing any documentation or creating tutorials please [let us know](https://www.pluginsandsnippets.com/contact/) .
