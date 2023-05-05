# Symfony doctrine mongodb example

## INSTALLATION


Add mongodb extension to php.ini
```ini
; mongodb.ini
extension=mongodb.so
```

Install mongodb driver
```dockerfile
## INSTALL MONGODB DRIVER
RUN set -xe \
    && apk add --no-cache --update --virtual .phpize-deps $PHPIZE_DEPS openssl curl-dev openssl-dev \
    && pecl install mongodb

COPY ./.docker/php/mongodb.ini /usr/local/etc/php/conf.d/
```

Install the package using composer
```text
INFO
If you use flex you need to run this command before installation of the "doctrine/mongodb-odm-bundle" package
$ composer config extra.symfony.allow-contrib true
```
```composer
composer require doctrine/mongodb-odm-bundle
```

## REFERENCES
### Mongodb driver
https://www.mongodb.com/docs/drivers/php/

### DoctrineMongodbBundle
https://www.doctrine-project.org/projects/doctrine-mongodb-bundle/en/current/installation.html#install-the-bundle-with-symfony-flex
