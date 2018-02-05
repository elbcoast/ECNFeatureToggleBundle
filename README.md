# ECNFeatureToggleBundle

[![Build Status](https://travis-ci.org/elbcoast/ECNFeatureToggleBundle.svg?branch=master)](https://travis-ci.org/elbcoast/ECNFeatureToggleBundle)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/85a8ed5d-78ac-4523-bb9a-ebf03e15f1a6/mini.png)](https://insight.sensiolabs.com/projects/85a8ed5d-78ac-4523-bb9a-ebf03e15f1a6)

**This bundle adds feature toggle functionality to your Symfony 2 project.**


## Installation


### Step 1: Install via composer

```bash
$ composer require ecn/featuretoggle-bundle
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

The idea behind Feature Toggle development is that features are defined on a local basis. Because of this it is
a good idea to have your feature definitions in a separate config file, which shouldn't be under version control:

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

Or using the tag:

``` jinja
{% feature MyNewFeature %}
    <p>Here is my new feature!</p>
{% endfeature %}
```


Inside an action:

``` php
if ($this->get('feature')->has('MyNewFeature')) {
    // Your new feature here
}
```

## Voters

In order to decide if a feature is available or not, voters are being used. Currently there are three voters included.


### AlwaysTrueVoter

This is the default voter and it will always pass. So if you have a feature defined, it will always be displayed.

The full configuration for using this voter looks like this:

``` yaml
ecn_feature_toggle:
    features:
        MyNewFeature:
            voter: AlwaysTrueVoter
```

Because this is the default voter, the voter part in the configuration can be omitted.

### AlwaysFalseVoter

This voter will always fail. If you have a feature defined that uses this voter, it will never be displayed.

The full configuration for using this voter looks like this:

``` yaml
ecn_feature_toggle:
    features:
        MyNewFeature:
            voter: AlwaysFalseVoter
```

### RatioVoter

This voter passes on a given ratio between 0 and 1, which makes it suitable for A/B testing. The default ratio is 0.5.

The higher the ratio, the more likely the voter will pass. A value of 1 will make it pass every time, 0 will make it
never pass.

Additionally, the result of the first check can be bound to the users session. This is useful if you need a feature
to be persistent across multiple requests. To enable this, just set `sticky` to `true`.

If you want to use this voter, this is the full configuration:


``` yaml
ecn_feature_toggle:
    features:
        MyNewFeature:
            voter: RatioVoter
            params: { ratio: 0.5, sticky: false }
```


### ScheduleVoter

This voter passes on a given schedule being after now.

If you want to use this voter, this is the full configuration:


``` yaml
ecn_feature_toggle:
    features:
        MyNewFeature:
            voter: ScheduleVoter
            params: { schedule: '2015-10-23' }
```


### RequestHeaderVoter

The name of the request header itself is by design case-insensitive.

Request header values are always treated as strings, so equal (==) checks are used and *not* identical matching (===).

Request header keys are by design case-insensitive.

The Voter does *not* pass if the request stack contains no current requests.


#### a. Specify key/value pairs

This voter passes, when *all* of the specified headers and their corresponding values are found and *equal* 
to that of the current request headers.

Example for key/value config:


``` yaml
ecn_feature_toggle:
    features:
        FooRequestFeature:
            voter: RequestHeaderVoter
            params: { headers: { foo: bar, x-cdn: 'akamai', x-location: 'cn' } }
```


#### b. specify request header keys only

You can also specify a list of request header keys without values.

In this case, only the existence of *all* of the specified request headers is checked.

All request header names are by the standard case-insensitive.


Example:

``` yaml
ecn_feature_toggle:
    features:
        FooRequestFeature:
            voter: RequestHeaderVoter
            params: { headers: { x-mobile, x-foo-debug-header } }
```


Mixing the two configurations is discouraged as it will lead to unexpected results by treating the config as key/value pairs, 
and will most likely cause the Voter to *not* pass.


## Overriding the default voter

You can override the default voter like this:

``` yaml
ecn_feature_toggle:
    default:        
        voter: RatioVoter
        params: { ratio: 0.1, sticky: true }
```

## Adding your own voters

Adding voters is straight forward. First make sure, that your voter implements `\Ecn\FeatureToggleBundle\Voters\VoterInterface`.

Then define your voter as service, tag it as ``ecn_featuretoggle.voter`` and give it an alias:


``` xml
<!-- Example Voter -->
<service id="ecn_featuretoggle.voter_example" class="My\Example\Voter">
    <tag name="ecn_featuretoggle.voter" alias="ExampleVoter" />
</service>
```


## Testing

``` bash
$ composer test
```


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
