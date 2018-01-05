# EdgarEzUIAuditBundle

## Installation

### Get the bundle using composer

Add EdgarEzUIAuditBundle by running this command from the terminal at the root of
your symfony project:

```bash
composer require edgar/ez-uiaudit-bundle
```

## Enable the bundle

To start using the bundle, register the bundle in your application's kernel class:

```php
// app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Edgar\CronBundle\EdgarCronBundle(),
        new Edgar\EzUICronBundle\EdgarEzUICronBundle(),
        new Edgar\EzUIAuditBundle\EdgarEzUIAuditBundle(),
        // ...
    );
}
```

## Add doctrine ORM support

in your ezplatform.yml, add

```yaml
doctrine:
    orm:
        auto_mapping: true
```

## Update your SQL schema

```
php bin/console doctrine:schema:update --force
```

## Add routing

Add to your global configuration app/config/routing.yml

```yaml
edgar.ezuicron:
    resource: '@EdgarEzUICronBundle/Resources/config/routing.yml'
    defaults:
        siteaccess_group_whitelist: 'admin_group'
        
edgar.ezuiaudit:
    resource: '@EdgarEzUIAuditBundle/Resources/config/routing.yml'
    defaults:
        siteaccess_group_whitelist: 'admin_group'    
```
