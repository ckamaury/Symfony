# DEV FIGHTERS - Symfony

Installation
------------
* Install with composer : composer/require ckamaury/symfony
* Requires PHP >= 8.1

Installation
------------
* Add to ``public/index.php``
```php
    APP:initialize($kernel);
```

* Add to ``config/services.yaml`` :

```yaml
  CkAmaury\Symfony\Repository\SecurityAccessRepository:
  CkAmaury\Symfony\Repository\SecurityRoleRepository:
  CkAmaury\Symfony\Repository\SecurityRoleAccessRepository:
      tags: ['doctrine.repository_service']
```

* Add to ``config/packages/doctrine.yaml`` :

```yaml
  doctrine:
    dbal:
      charset: utf8
      default_table_options:
        charset: utf8
        collate: utf8_unicode_ci
      types:
        mediumint: CkAmaury\Symfony\Database\Type\MediumintType
        tinyint: CkAmaury\Symfony\Database\Type\TinyintType
        datetime: CkAmaury\Symfony\Database\Type\DateTimeType
        date: CkAmaury\Symfony\Database\Type\DateType
    orm:
      auto_mapping: true
      mappings:
        CkAmaury:
          type: attribute
          prefix: 'CkAmaury\Symfony\Entity'
          dir: "%kernel.project_dir%/vendor/ckamaury/symfony/src/Entity"
          is_bundle: false
      dql:
        string_functions:
          replace: DoctrineExtensions\Query\Mysql\Replace
          MONTH: DoctrineExtensions\Query\Mysql\Month
          YEAR: DoctrineExtensions\Query\Mysql\Year
```


Pack Includes
------------
* Symfony WebPack v1.2



