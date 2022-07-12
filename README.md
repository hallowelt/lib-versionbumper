# Version Bumper

## Examples

```php
$bumper = new HalloWelt\Lib\VersionBumper\VersionBumper();
$bumper->bumpMAJOR( '1.1.6' ); // "2.0.0"
$bumper->bumpMINOR( '1.1.6' ); // "1.2.0"
$bumper->bumpPATCH( '1.1.6' ); // "1.1.7"
$bumper->bumpPRERELEASE( '1.1.6', 'rc' ); // "1.1.6-rc.1"
$bumper->setMETADATA( '1.1.6-beta', 'WEEK27' ); // "1.1.6-beta.1+WEEK27" ATTENTION: PRERELEASE WILL BE NORMALIZED
```