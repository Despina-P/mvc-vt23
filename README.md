# mvc-vt23
My repository for the course 'mvc'.
====================================


This is my report page for the course 'mvc' read at the second term in the program 'Webbprogrammering' at BTH.
It consists of all the assignments made during the course and the final examination. 

<h1>How to clone the repository and get going</h1>

Firstly, open up a terminal, like Ubuntu, on your local machine.
Secondly, after deciding where you want to clone the repository, navigate there.
Lastly, to clone the repository run the following commands:
```
https://github.com/Despina-P/mvc-vt23.git
cd mvc-vt23
```

<h1>How to install dependencies needed</h1>

```
composer install
npm install
npm run build
```

<h1>Via the ORM Symfony pack install Doctrin</h1>

```
# Go to the root of your Symfony directory
composer require symfony/orm-pack
composer require --dev symfony/maker-bundle
```

To run the code use:

```
php -S localhost:8888 -t public
```

<h2>PHPMetrics</h2>

```
# Go to the root of your Symfony directory
mkdir --parents tools/phpmetrics
composer require --working-dir=tools/phpmetrics phpmetrics/phpmetrics
```

<h2>PHP Mess Detector</h2>

```
# Go to the root of your Symfony directory
mkdir --parents tools/phpmd
composer require --working-dir=tools/phpmd phpmd/phpmd
```

<h2>PHPStan</h2>

```
# Go to the root of your Symfony directory
mkdir --parents tools/phpstan
composer require --working-dir=tools/phpstan phpstan/phpstan
```

<h2>PHPUnit</h2>

```
composer require phpunit/phpunit --dev
```

<h2>phpDocumentor</h2>

```
# Go to the root of your Symfony directory
mkdir --parents tools/phpdoc
wget https://phpdoc.org/phpDocumentor.phar -O tools/phpdoc/phpdoc
chmod 755 tools/phpdoc/phpdoc
```

<p>

[![Code Coverage](https://scrutinizer-ci.com/g/Despina-P/mvc-vt23/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/Despina-P/mvc-vt23/badges/quality-score.png?b=main)

[![Build Status](https://scrutinizer-ci.com/g/Despina-P/mvc-vt23/badges/build.png?b=main)](https://scrutinizer-ci.com/g/Despina-P/mvc-vt23/badges/coverage.png?b=main)

[![Code Intelligence Status](https://scrutinizer-ci.com/g/Despina-P/mvc-vt23/badges/code-intelligence.svg?b=main)](https://scrutinizer-ci.com/g/Despina-P/mvc-vt23/badges/build.png?b=main)

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Despina-P/mvc-vt23/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/Despina-P/mvc-vt23/badges/code-intelligence.svg?b=main)

</p>