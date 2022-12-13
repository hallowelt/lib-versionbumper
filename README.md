# Version Bumper

## Examples

Example of `VersionBumper`:

```php
$bumper = new HalloWelt\Lib\VersionBumper\VersionBumper();
$bumper->bumpMAJOR( '1.1.6' ); // "2.0.0"
$bumper->bumpMINOR( '1.1.6' ); // "1.2.0"
$bumper->bumpPATCH( '1.1.6' ); // "1.1.7"
$bumper->bumpPRERELEASE( '1.1.6', 'rc' ); // "1.1.6-rc.1"
$bumper->setMETADATA( '1.1.6-beta', 'WEEK27' ); // "1.1.6-beta.1+WEEK27" ATTENTION: PRERELEASE WILL BE NORMALIZED
```

Example of `Utils::isVersionValid`:

```php
$utils = new HalloWelt\Lib\VersionBumper\Utils();
$utils->isVersionValid( '1.1.6' ); // true
$utils->isVersionValid( '1.1.6-beta' ); // true
$utils->isVersionValid( '1.1.6-beta.1' ); // true
$utils->isVersionValid( '1.1.6-beta.1+WEEK27' ); // true
```

Example of `Utils::getPreviousVersion`:

```php
$utils = new HalloWelt\Lib\VersionBumper\Utils();
$utils->getPreviousVersion( '1.1.6', [ '1.1.7', '1.1.6', 'NOTAVERSION', '1.1.5', '1.1.4' ] ); // "1.1.5"
$utils->getPreviousVersion( '1.1.6-beta', [ '1.1.7', '1.1.6', 'NOTAVERSION', '1.1.5', '1.1.4' ]); // "1.1.5"
```

Example of `Utils::getUpdateType`:

```php
$utils = new HalloWelt\Lib\VersionBumper\Utils();
$utils->getUpdateType( '1.1.6', '1.1.7' ); // Utils::TYPE_PATCH
$utils->getUpdateType( '1.1.6', '1.2.0' ); // Utils::TYPE_MINOR
$utils->getUpdateType( '1.1.6', '2.0.0' ); // Utils::TYPE_MAJOR
```