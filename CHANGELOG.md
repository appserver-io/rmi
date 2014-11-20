# Version 0.2.7

## Bugfixes

* None

## Features

* Update constant IDENTIFIER to use short class name instead of fully qualified one

# Version 0.2.6

## Bugfixes

* None

## Features

* Add session-ID, necessary to create new SFBs, to BeanContext::newInstance() method

# Version 0.2.5

## Bugfixes

* None

## Features

* Add BeanContext::lookup() method

# Version 0.2.4

## Bugfixes

* Move composer dependenies to techdivision/application + techdivision/server to require-dev

## Features

* None

# Version 0.2.3

## Bugfixes

* None

## Features

* Remove BeanContext::getBeanAnnotation() method => method has been moved to BeanUtils

# Version 0.2.2

## Bugfixes

* Replace BeanContext::class with class name for PHP 5.4 compatibility
* Resolve some PHPMD errors/warnings

## Features

* None

# Version 0.2.1

## Bugfixes

* None

## Features

* Refactoring ANT PHPUnit execution process
* Composer integration by optimizing folder structure (move bootstrap.php + phpunit.xml.dist => phpunit.xml)
* Switch to new appserver-io/build build- and deployment environment