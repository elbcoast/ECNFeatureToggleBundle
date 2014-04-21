ECNFeatureToggleBundle
======================

[![Build Status](https://travis-ci.org/elbcoast/ECNFeatureToggleBundle.svg?branch=master)](https://travis-ci.org/elbcoast/ECNFeatureToggleBundle)

This bundle adds feature toggle functionality to your Symfony 2 project.


## Installation

### Step 1: Install via composer

Add the EcnFeatureToggleBundle to your `composer.json`:

```js
"require": {
    // ...
    "ecn/featuretoggle-bundle": "dev-master"
},
```

After that use composer to install the new bundle:

```bash
$ php composer.phar update
```

### Step 2: Activate the bundle

Add the bundle to the AppKernel.php:

```php
<?php

// in AppKernel::registerBundles()
$bundles = array(
    // ...
    new Ecn\FeatureToggleBundle\EcnFeatureToggleBundle(),
    // ...
);
```


## Configuration

The idea behind Feature Toggle development is, that features are defined on a local basis. Because of this it is
a good idea to have you feature definitions in a separate config file, which shouldn't be under version control:

Create a new yml file, e.g. `features.yml` and make your SCM ignore this file.

Then add the following configuration to your features.yml:

``` yaml
ecn_feature_toggle:
    features:
```

Import the features.yml file into your configuration:

``` yaml
imports:
    - { resource: features.yml, ignore_errors: true }
```


## Usage

### Step 1: Add a feature toggle

Define a new feature toggle inside your feature configuration:

``` yaml
ecn_feature_toggle:
    features:
        MyNewFeature:
```

### Step 2: Check for a feature toggle inside the code

Now you can check inside your code, if this feature is defined.

Inside a twig template:

``` jinja
{% if feature('MyNewFeature') %}
    <p>Here is my new feature!</p>
{% endif %}
```


Inside an action:

``` php
if ($this->get('feature')->has('MyNewFeature') {
    // Your new feature here
}
```
