SYMFONY EXAMPLES : doctrine mongodb
===================================
[![CI](https://github.com/symfony-examples/doctrine-mongodb/actions/workflows/ci.yaml/badge.svg?branch=main)](https://github.com/symfony-examples/doctrine-mongodb/actions/workflows/ci.yaml?query=branch%3Amain)
[![Security](https://github.com/symfony-examples/doctrine-mongodb/actions/workflows/security.yaml/badge.svg?branch=main)](https://github.com/symfony-examples/doctrine-mongodb/actions/workflows/security.yaml?query=branch%3Amain)

# About this repository
This is a simple implementation of doctrine mongodb.

# Installation
## Requirements
* git
* docker
* docker-compose
* makefile

## Steps
Clone the project
```bash
git clone https://github.com/symfony-examples/doctrine-mongodb.git
```
Installation
```bash
make install-local
```
Enjoy ! 🥳

# Documentation
## Mongodb extension
Add mongodb extension to php.ini
```ini
; mongodb.ini
extension=mongodb.so
```

## Mongodb driver
Install mongodb driver
```dockerfile
## INSTALL MONGODB DRIVER
RUN set -xe \
    && apk add --no-cache --update --virtual .phpize-deps $PHPIZE_DEPS openssl curl-dev openssl-dev \
    && pecl install mongodb

COPY ./.docker/php/mongodb.ini /usr/local/etc/php/conf.d/
```

## Installation using composer
> Note : If you use flex, you need to run this command before installation of the `doctrine/mongodb-odm-bundle` package
```bash
composer config extra.symfony.allow-contrib true
```
Install the package
```bash
composer require doctrine/mongodb-odm-bundle
```
> Show mongodb commands<br>
> `./bin/console list doctrine:mongodb`


## ODM
### Document
```php
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: 'Company')]
class Company
{
    #[ODM\Id(type: 'string', strategy: 'auto')]
    private ?string $id = null;
}
```
### EmbeddedDocument
> NOTE : don't add primary key to an embedded document
#### EmbedOne
```php
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\EmbeddedDocument]
class Registration
{
    // without id
}

#[ODM\Document(collection: 'Company')]
class Company
{
    #[ODM\Id(type: 'string', strategy: 'auto')]
    private ?string $id = null;

    #[ODM\EmbedOne(targetDocument: Registration::class)]
    private Registration $registration;
}
```
#### EmbedMany
```php
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\EmbeddedDocument]
class Address
{
}

#[ODM\Document(collection: 'Company')]
class Company
{
    #[ODM\Id(type: 'string', strategy: 'auto')]
    private ?string $id = null;

    #[ODM\EmbedMany(targetDocument: Address::class)]
    private Collection $addresses;
    
    public function __construct()
    {
        $this->addresses = new ArrayCollection();
    }
}
```
### ReferenceDocument
#### ReferenceOne
```php
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: 'Product')]
class Product
{
    #[ODM\Id(type: 'string', strategy: 'auto')]
    private ?string $id = null;

    #[ODM\ReferenceOne(targetDocument: Store::class, cascade: 'persist')]
    private Store $store;
}

#[ODM\Document(collection: 'Store')]
class Store
{
    #[ODM\Id(type: 'string', strategy: 'auto')]
    private ?string $id = null;
}
```
#### ReferenceMany
```php
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: 'Store')]
class Store
{
    #[ODM\Id(type: 'string', strategy: 'auto')]
    private ?string $id = null;
    
    #[ODM\ReferenceMany(targetDocument: Product::class, mappedBy: 'store')]
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }
}

#[ODM\Document(collection: 'Product')]
class Product
{
    #[ODM\Id(type: 'string', strategy: 'auto')]
    private ?string $id = null;

    // inversedBy is optional, don't add it if you don't need the bi-directional reference
    #[ODM\ReferenceOne(targetDocument: Store::class, cascade: 'persist', inversedBy: 'products')]
    private Store $store;
}
```

# References
* [Mongodb driver](https://www.mongodb.com/docs/drivers/php/)
* [DoctrineMongodbBundle](https://www.doctrine-project.org/projects/doctrine-mongodb-bundle/en/current/installation.html#install-the-bundle-with-symfony-flex)
