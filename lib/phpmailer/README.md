This folder defines the `phpmailer` PHAR library.  Sources are downloaded
via `composer` and moved into an isolated namespace (`PHM7`).  The resulting
library is tracked as `dist/phpmailer@X.Y.Z.phar`.

## Prefixing

Packages in this folder use namespace-prefixes. Compare:

* __Original Class__: `\PHPMailer\PHPMailer\Exception`
* __Prefixed Class__: `\PHM7\PHPMailer\PHPMailer\Exception`

## Autoloader

To use the PHAR library, register via Pathload:

```php
pathload()->addSearchDir(__DIR__ . '/dist');
pathload()->addNamespace('phpmailer@7', ['PHM7\\']);
```

When any classes from `PHM7\\` are used, the PHAR file is mounted.  Within
the PHAR, we inherit autoloading rules from the `composer.json` of each
nested library.

## Managing packages

To add, remove, or update the packages within `phpmailer.phar`, you can use
`composer` commands, e.g.

```bash
cd lib/phpmailer
composer update foo/bar
compsoer remove baz/quux
composer require whiz/bang
```

When you test the new libraries, you may find that you need to fine-tune the
prefixing rules (`scoper.inc.php`).

## Building

The `build.sh` script will run `composer` and `box` to produce a suitable PHAR.

> TIP: This will also leave an extra folder called `vendor/` that contains the original files.
> If you use an IDE like PhpStorm, then the folder will create extra noise
> that makes it harder to use auto-completion. You should delete the  leftover `vendor/`

Here is how I typically run it:

```
nix-shell --run ./lib/phpmailer/build.sh && rm -rf lib/phpmailer/vendor/
```
