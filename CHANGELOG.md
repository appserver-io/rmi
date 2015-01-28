# Version 0.6.0

## Bugfixes

* None

## Features

* Upgraded dependencies to new appserver-io/collections 0.2.0

# Version 0.5.0

## Bugfixes

* None

## Features

* Applied new file name and comment conventions

# Version 0.4.0

## Bugfixes

* None

## Features

* Merge with appserver-io/persistencecontainerclient package

# Version 0.3.0

## Bugfixes

* None

## Features

* Move interfaces to appserver-io-psr/epb package
* Rename package to appserver-io/rmi

# Version 0.2.9

## Bugfixes

* None

## Features

* Add dependency to appserver-io-psr/epb package
* Move interfaces ResourceLocator, ServiceContext, ServiceExecutor, ServiceProvider, ServiceResourceLocator + TimerServiceContext from appserver-io/appserver package
* Move exceptions InvalidBeanTypeException + ServiceAlreadyRegisteredException from appserver-io/appserver package

# Version 0.2.8

## Bugfixes

* None

## Features

* Add getReflectionClass() + getReflectionClassForObject() for object

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