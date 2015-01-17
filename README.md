# Decorators

[![Build Status](https://img.shields.io/travis/pushoperations/Decorators.svg)](https://travis-ci.org/pushoperations/Decorators)
[![Coverage Status](https://img.shields.io/coveralls/pushoperations/Decorators.svg)](https://coveralls.io/r/pushoperations/Decorators)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/pushoperations/Decorators.svg)](https://scrutinizer-ci.com/g/pushoperations/Decorators/?branch=master)

[![Total Downloads](https://poser.pugx.org/pushoperations/decorators/downloads.svg)](https://packagist.org/packages/pushoperations/Decorators)
[![Latest Stable Version](https://poser.pugx.org/pushoperations/decorators/v/stable.svg)](https://packagist.org/packages/pushoperations/Decorators)
[![Latest Unstable Version](https://poser.pugx.org/pushoperations/decorators/v/unstable.svg)](https://packagist.org/packages/pushoperations/Decorators)
[![License](https://poser.pugx.org/pushoperations/decorators/license.svg)](https://packagist.org/packages/pushoperations/Decorators)

<!--[![SensioLabsInsight](https://insight.sensiolabs.com/projects/3ab73b7b-5159-4bfe-8c85-2a15d03f9579/big.png)](https://insight.sensiolabs.com/projects/3ab73b7b-5159-4bfe-8c85-2a15d03f9579)-->

A library to decorate arrays (especially Laravel's Input::) for manipulation and usage as a service to return data for object construction.

Note: *this library may contain other patterns in the future*.

## Contents

- [Installation](#install)
- [Usage](#usage)
- [Examples](#examples)
- [API documentation](http://pushoperations.github.io/Decorators/docs)

## Install

The recommended way to install is through [Composer](http://getcomposer.org).

Update your project's composer.json file to include Decorators:

```json
{
    "require": {
        "pushoperations/decorators": "1.*"
    }
}
```

Then update the project dependencies to include this library:

```bash
composer update pushoperations/decorators
```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```

## Usage

Create your own decorator by:
- Extending from the `DataDecorator` abstract class
- Creating a constructor that accepts an array
- Optional: add methods that are specific to generating the array of data you need

One use case is to split input data into different arrays for different factories to use for the construction of new objects.

A handy effect is that you can perform sanitization within the decorator's methods before returning the data array.

## Examples

```php
use Decorators\DataDecorator;
use Decorators\DataDecoratorInterface;

class BasicDecorator extends DataDecorator implements DataDecoratorInterface
{
    public function __construct(array $input)
    {
        $this->data = $input;
    }
}

class ComplexDecorator extends DataDecorator implements DataDecoratorInterface
{
    public function __construct(array $input)
    {
        $this->data = $input;
    }

    public function complicate()
    {
        return array_map($this->data, function($value) {
            if (is_int($value)) {
                return $value * 2;
            }
        });
    }
}
```

Common usage would be to filter and pick apart the user input for create/update:

```php
$input = [
    'name' => 'Push Operations',
    'desks' => 50,
    'employees' => [
        'John', 'Jane',
    ],
];

$basic = new BasicDecorator($input);

// Check if value for key exists
echo $basic->has('desks');                      // true
echo $basic->has('chairs');                     // false

// Provide a default value if it doesn't exist
echo $basic->get('name');                       // 'Push Operations'
echo $basic->get('chairs', 10);                 // 10

// Get some of the data
var_dump($basic->only('name', 'desks'));        // ['name' => 'Push Operations', 'desks' => 50]
var_dump($basic->only(['name', 'desks']));      // ['name' => 'Push Operations', 'desks' => 50]
var_dump($basic->except('name'));               // ['desks' => 50, 'employees' => ['John', 'Jane']]

// Get all of the data
var_dump($basic->all());                        // The $input array

// Add data
$add = [
    'interns' => [
        'Billy', 'Derrick'
    ],
];
$basic->merge($add);
var_dump($basic->get('interns'));               // ['Billy', 'Derrick']

// You can redecorate the results of the decorator (with itself or another decorator) to do more manipulation.

$complex = new ComplexDecorator($basic->all());
var_dump($complex->complicate());               // [..., 'desks' => 100, ...];
```
