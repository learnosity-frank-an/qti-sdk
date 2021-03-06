<p align="center"><img src="https://cloud.githubusercontent.com/assets/4217431/6734391/3b3ef4e8-ce58-11e4-83a1-d6e39a91400f.png" alt="QTI-SDK" /></p>

[![Latest Version](https://img.shields.io/github/tag/oat-sa/qti-sdk.svg?style=flat&label=release)](https://github.com/oat-sa/qti-sdk/tags)
[![Build Status](https://travis-ci.org/oat-sa/qti-sdk.svg?branch=master)](https://travis-ci.org/oat-sa/qti-sdk)
[![Coverage Status](https://coveralls.io/repos/oat-sa/qti-sdk/badge.png)](https://coveralls.io/r/oat-sa/qti-sdk)
[![License GPL2](http://img.shields.io/badge/licence-gpl2-blue.svg)](http://www.gnu.org/licenses/gpl-2.0.html)
[![Packagist Downloads](http://img.shields.io/packagist/dt/qtism/qtism.svg)](https://packagist.org/packages/qtism/qtism)

# QTI Software Development Kit for PHP

An IMS QTI (Question &amp; Test Interoperability) Software Development Kit for PHP 5.3 and higher supporting a wide 
range of features described by the [IMS QTI specification family](http://www.imsglobal.org/question).

__This implementation of QTI is under constant enhancement. The API of the master branch might change at any time. See release 0.9.1 for the last stable version.__

## Features

* Targets QTI 2.0, 2.1 and 2.2
* Complete QTI Information Model
* Complete QTI Rule Engine Support
* Custom Operator Hooks through PSR-0
* [Wilbert Kraan's](http://blogs.cetis.ac.uk/wilbert/2013/11/06/using-standards-to-make-assessment-in-e-textbooks-scalable-engaging-but-robust) / [Steve Lay's](http://swl10.blogspot.co.uk/2013/09/transforming-qti-v2-into-xhtml-5.html) Goldilocks Rendering
* CSS Parser for direct QTI Information Model mapping at rendering time
* Item Sessions (with lightning fast binary persistence)
* Test Sessions (with lightning fast binary persistence)
* Nice and Clean API for QTI Document manipulation/traversal
* PreConditions and Branching
* Response/Outcome Processing
* Unit Testing Driven from PHP 5.3 to 5.6

## Installation (developers)

1. Clone the repository.
2. Make sure you know how [Composer](https://getcomposer.org/download/) works and it is installed on your system.
3. php composer.phar install
4. You are ready!

## Unit Tests (developers)

Run Unit Tests by invoking the following shell command:

```shell
cp phpunit.xml.dist phpunit.xml
./vendor/bin/phpunit test
```

## Contribute

We are always looking for people to feed the project with:

* Bug reports
* Unit tests
* New features

[Please make yourself known](https://github.com/bugalot)!

## QTI Rendering

The QTI Software Development Kit enables you to transform XML serialized QTI files
into their (X)HTML5 Goldilocks equivalent. The following shell command renders the
`path/to/qti.xml` QTI file into an HTML5 document using the (X)HTML5 Golidlocks rendering 
flavour with indentation formatting. The rendering output (stdout) is redirected to the
`/home/jerome/qti.html` file.

```shell
./vendor/bin/qtisdk render -df --source path/to/qti.xml --flavour goldilocks > /home/jerome/qti.html
```

For additionnal help and information, just call the help screen to know about the features provided by the rendering binaries!

```shell
./vendor/bin/qtisdk render --help
```
