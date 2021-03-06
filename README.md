# Smart and Readable Documentation for your PHP project

[![Build Status](https://img.shields.io/travis/ApiGen/ApiGen/master.svg?style=flat-square)](https://travis-ci.org/ApiGen/ApiGen)
[![Build status](https://ci.appveyor.com/api/projects/status/p8y6685thhh7mgw0/branch/4.2?svg=true)](https://ci.appveyor.com/project/ek9/apigen/branch/4.2)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/ApiGen/ApiGen.svg?style=flat-square)](https://scrutinizer-ci.com/g/ApiGen/ApiGen)
[![Downloads](https://img.shields.io/packagist/dt/apigen/apigen.svg?style=flat-square)](https://packagist.org/packages/apigen/apigen)
[![Latest stable](https://img.shields.io/packagist/v/apigen/apigen.svg?style=flat-square)](https://packagist.org/packages/apigen/apigen)

Just look at [CakePHP Framework](http://api.cakephp.org/3.0/) or [Doctrine ORM API](http://www.doctrine-project.org/api/orm/2.4/).

**Note!** This is `4.2` branch, final `4.x` series of ApiGen. The project is
undergoing a rewrite (`5.x` series on `master`). Please submit any PRs for
versions `4.2.x` to `4.2` branch.

## Requirements

- PHP 5.5+

## Install

Install using composer as a development dependency in your project:

```
composer require --dev apigen/apigen
```

Or if you want it globally:

```
composer global require --dev apigen/apigen
```

## Usage

Run ApiGen with source and destination options:

```sh
vendor/bin/apigen generate -s ./src -d ./docs
```

To omit cli options just create `apigen.yaml` or `apigen.neon` file in your project's root folder:

```yaml
source:
    - ./src

destination: ./docs
```

For all available options, along with descriptions and default values, just run:

```sh
vendor/bin/apigen generate --help
```

## Documentation

### DocBlock Annotations

This section provides a list of [PHP DocBlock
annotations](https://www.phpdoc.org/docs/latest/guides/docblocks.html) (tags)
that are supported by ApiGen:

- `@author` - documents the author of the associated element.
- `@copyright` - documents the copyright information for the associated element.
- `@deprected` - indicated that the associated element is deprecated and can be
  removed in the future version.
- `@internal` - denotes that the associated elements is internal to this
  application or library and hides it by default.
- `@license` - indicates which license is applicable for the associated
  element.
- `@link` - indicates a relation between the associated element and a page of
  a website.
- `@method` - allows a class to know which ‘magic’ methods are callable.
- `@package` - categorizes the associated element into a logical grouping or
  subdivision.
- `@param` - documents a single argument of a function or method.
- `@property` - allows a class to know which ‘magic’ properties are present.
- `@return` - documents the return value of functions or methods.
- `@see` - indicates a reference from the associated element to a website or
  other elements.
- `@subpackage` - categorizes the associated element into a logical grouping or
  subdivision.
- `@throws` - indicates whether the associated element could throw a specific
  type of exception.
- `@usedby` indicates a "from" reference with a single associated element.
- `@uses` - indicates a reference to (and from) a single associated element.

### Configuration

This section provides information on all available configuration options that
can be included in configuration files (`apigen.yml` or `apigen.neon`).

#### Minimal Configuration

A minimal configuration file:

```yaml
# apigen.neon.dist
# This is minimal configuration for ApiGen.
source: [src]           # directory(-ies) to scan PHP files from
destination: docs       # destination directory to generate API docs in
```

**Note!** The configuration files match CLI options for [generate](#generate)
command. The only difference is that when defining these options in
configuration file, you have to use `camelCased` format (i.e.
`--annotation-groups` CLI option becomes `annotationGroups` configuration
parameter).

#### Reference Configuration

A reference configuration file with all of the available and supported
configuration options.

```yaml
# apigen.neon.dist
# This is reference configuration for ApiGen. It contains all of the available
# and supported configuration options, together with their default values.
# source options
source: [src]                       # Source directory(-ies) to build API docs for
                                    # (array)
extensions: [php]                   # A list of file extension to include when
                                    # scanning source dir (array)
accessLevels: [public, protected]   # Access levels of methods and properties
                                    # to include (array)
annotationGroups: [todo, deprecated]# Annotation Groups to include (array)
internal: false                     # Set to `true` to include @internal in API
                                    # docs (boolean)
#main: 'SomePrefix'                 # Elements with this name prefix will be
                                    # first in the tree (string)
php: false                          # Set to `true` to generate docs for PHP
                                    # internal classes (boolean)
noSourceCode: false                 # Set to `true` to NOT generate highlighted
                                    # source code for elements (boolean)

# destination / generated docs options
destination: doc                    # Destination directory for API docs (string)
exclude: tests                      # A blob pattern to exclude from API docs
                                    # generation (string (blob))
overwrite: true                     # Overwrite destination directory by
                                    # default (boolean)
title: "ApiGen Docs"                # Title of generated API docs (string)
baseUrl: http://apigen.org/api      # Base URL for generated API docs (string (URL))
templateConfig: path/to/config.neon # path to template configuration (string (path))

# templates parameters
googleAnalytics: 123                # Google Analytics tracking code (string)
googleCseId: 456                    # Google Custom Search Engine ID (string)
download: true                      # show a link to download API docs ZIP
                                    # archive in the API docs (boolean)

# debug
debug: false                        # set to true to enable debug (boolean)
```

### CLI Commands

This section provides information on all available CLI commands and their
options.

Main ApiGen commands:

- [generate](#generate) - generates API documentation.

To get a list of available `apigen list` command. To get help on specific
command use `apigen help`, i.e.:

    $ apigen help generate

#### Generate

`generate` command is the main command which generates API documentation. The
command relies either on passing it CLI options or reading data from
[configuration files](#Configuration).

A list of options accepted by `generate` command:

| Option                | Description                                         |
| --------------------- | --------------------------------------------------- |
| `--source` (`-s`)     | Source directory(-ies) to generate API docs for. **Multiple values are allowed**.
| `--destination` (`-d`)| Destination directory for API docs.
| `--access-levels`     | Access levels of methods and properties to be included in API docs [options: `public`, `protected`, `private`]. Default: `["public","protected"]`.
| `--annotation-groups` | Generate page with elements with specific annotation.
| `--config`            | Custom path to ApiGen configuration file. Default: `./apigen.neon`
| `--google-cse-id`     | Custom Google Search Engine ID (for search box).
| `--base-url`          | Base URL (used for Sitemap / search box).
| `--google-analytics`  | Google Analytics tracking code to include in generated API docs.
| `--debug`             | Turn on debug mode (prints verbose information from low-level parser). Useful when debugging / during development.
| `--download`          | Pass this option to include a link to a generated ZIP archive in the API docs.
| `--extensions`        | A list of scanned file extensions. **Multiple values are allowed**. Default: `["php"]`.
| `--exclude`           | Diretories or files matching provided mask will be excluded (e.g. `*/tests/*`). **Multiple values are allowed**.
| `--groups`            | Define element grouping in the menu [options: `namespaces`, `packages`]. Default: `namespaces`.
| `--main`              | Elements with provided main name prefix will be first in the tree.
| `--internal`          | Include elements marked as `@internal`.
| `--php`               | Generate documentation for PHP internal classes.
| `--no-source-code`    | Do not generate highlighted source code for elements.
| `--template-theme`    | ApiGen template theme name. [default: "default"].
| `--template-config`   | Specify your own template config (this setting will override `--template-theme`).
| `--title`             | Custom title of API docs.
| `--tree`              | Generate a tree view of classes, interfaces, traits and exceptions.
| `--deprecated`        | deprecated, only present for BC
| `--todo`              | deprecated, only present for BC
| `--charset`           | deprecated, only present for BC
| `--skip-doc-path`     | deprecated, only present for BC
| `--overwrite` (`-o`)  | Force overwriting of destination directory.
| `--help` (`-h`)       | Display help message for all or specific commands.
| `--quiet` (`-q`)      | Do not output any messages.
| `--version` (`-V`)    | Display version of ApiGen.

### Themes

In order to enable a custom theme, you have to either provide `--theme-config`
CLI option when runing `apigen generate` or add `themeConfig` configuration
option in your ApiGen configuration file:

```yaml
themeConfig: path/to/theme/config.neon # path to theme's config file
```

## Contributing

Please refer to [CONTRIBUTING](CONTRIBUTING.md) for details.

### Tests

To run tests:

```sh
$ vendor/bin/phpunit
```

## Get Support!

* [#apigen](http://webchat.freenode.net/?channels=#apigen) on irc.freenode.net - Come chat with us, we have cake.
* [GitHub Issues](https://github.com/ApiGen/ApiGen/issues) - Got issues? Please tell us!
* [Roadmaps](https://github.com/ApiGen/ApiGen/wiki/Roadmaps) - Want to contribute? Get involved!

