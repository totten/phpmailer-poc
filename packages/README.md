This folder provides external packages required by the extension.

In this folder, class files are stored as a bundle (`vendor.phar`), and they
share a common namespace-prefix (`PHM7`).

## Prefixing

Packages in this folder use namespace-prefixes. Compare:

* __Original Class__: `\PHPMailer\PHPMailer\PHPMailer`
* __Prefixed Class__: `\PHM7\PHPMailer\PHPMailer\PHPMailer`

## Autoloader

To setup autoloading, use this file:

```php
require_once __DIR__ . '/packages/autoload.php';
```

## Building

The `build.sh` script will run `composer` and `box` to produce a suitable `vendor.phar`.

> TIP: This will also leave an extra folder called `vendor/` that contains the original files.
> If you use an IDE like PhpStorm, then you should delete this -- it will create extra noise
> that makes it harder to use auto-completion.

Here is how I typically run it:

```
cd packages
nix-shell --run ./build.sh && rm -rf vendor/
```
