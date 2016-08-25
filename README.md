Welcome to Tiny Tasks Bundle
============================

I've developed this bundle for testing and demonstration purposes.

Some of the main libraries used for to build this bundle were:

- [Symfony - v3](http://symfony.com) As PHP Framework.
- [FOSRestBundle - v2.0.0](https://github.com/FriendsOfSymfony/FOSRestBundle) Tools to rapidly develop RESTful API's with Symfony
- [JMSSerializerBundle - v1.1.0](https://github.com/schmittjoh/JMSSerializerBundle) Easily serialize, and deserialize data of any complexity (supports XML, JSON, YAML)
- [DoctrineFixturesBundle - v2.3.0](https://github.com/doctrine/DoctrineFixturesBundle) Integrates the Doctrine2 Data Fixtures library into Symfony
- [jQuery - v1.12.4](https://jquery.com) jQuery is a fast, small, and feature-rich JavaScript library.
- [jQuery Mustache - v0.2.7](https://github.com/jonnyreeves/jquery-Mustache) jQuery Plugin which makes working light work of using the Mustache templating engine.
- [Bootstrap - v3.3.6](http://getbootstrap.com) Bootstrap is a HTML, CSS, and JS framework.
- [Mustache.js - v2.2.1](https://github.com/janl/mustache.js) Minimal templating in JavaScript.

Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
    $ composer require jarenal/tiny-tasks-bundle dev-master
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle and dependencies
------------------------------------------

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new Jarenal\TinyTasksBundle\JarenalTinyTasksBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
        );


        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            // ...

            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
        }
    }

    // ...
}
```

Step 3: Enable the routing
--------------------------

For to enable the routing add the next lines to your app/config/routing.yml file:

```
    jarenal_tiny_tasks_bundle:
        resource: "@JarenalTinyTasksBundle/Resources/config/routing.yml"
```

Step 4: FosRestBundle settings
------------------------------

In your app/config/config.yml file add the next lines for to set the default configuration for FosRestBundle:

```
    fos_rest:
        routing_loader:
            default_format: json
```

Step 5: Database setup
----------------------

```
NOTICE: Please note that this bundle is for testing purposes, so be careful before to execute the next commands in your database.
```

First enter the parameters of your database if you have not done yet in your app/config/parameters.yml

Then create the database if doesn't exist:

```bash
    $ php bin/console doctrine:database:create
```

Update schema for to generate the tables:

```bash
    $ php bin/console doctrine:schema:update
```

Load the default fixtures for the status table:

```bash
    $ php bin/console doctrine:fixtures:load
```

Step 6: Publish assets
----------------------

Publishing bundle assets:

```bash
    $ php bin/console assets:install --symlink
```

Step 7: Clearing cache and try it!
----------------------

Clear Symfony cache:

```bash
    $ php bin/console cache:clear
```

And finally call to the bundle using the next url:

    http://YOUR_SERVER/tiny-tasks

