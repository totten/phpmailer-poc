This folder provides external packages required by the extension.

In this folder, class files are stored as a bundle (`vendor.phar`), and they
share a common namespace-prefix (`PHM7`).

## Prefixing

Packages in this folder use namespace-prefixes. Compare:

* __Original Class__: `\PHPMailer\PHPMailer\PHPMailer`
* __Prefixed Class__: `\PHM7\PHPMailer\PHPMailer\PHPMailer`

## Autoloader

To setup autoloading, enable `vendor-phar@1` mixin.

## Building

The `build.sh` script will run `composer` and `box` to produce a suitable `vendor.phar`.

> TIP: This will also leave an extra folder called `vendor/` that contains the original files.
> If you use an IDE like PhpStorm, then you should delete this -- it will create extra noise
> that makes it harder to use auto-completion.

Here is how I typically run it:

```
nix-shell --run ./packages/build.sh && rm -rf packages/vendor/
```

## Limitation

In `pathload.main.php`, you must maintain list of namespaces for embedded packages.

The build-script could updated to prepare `pathload.main.php` or `pathload.json` automatically...

Alternatively, you can try to daisy-chain with composer-generated `vendor/autoload.php` because of an ordering issue.
(In pathload-poc, `loadClass()` calls `loadPackagesByNamespace()` - but the load operation is already active.  This is
fine for the autoloading rules defined in `pathload.json`, but daisy-chaining at that precise moment is tricky.)
