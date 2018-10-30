# Workbench CLI

[![Build Status](https://img.shields.io/travis/asylgrp/workbench-cli/master.svg?style=flat-square)](https://travis-ci.org/asylgrp/workbench-cli)
[![Quality Score](https://img.shields.io/scrutinizer/g/asylgrp/workbench-cli.svg?style=flat-square)](https://scrutinizer-ci.com/g/asylgrp/workbench-cli)

CLI frontend for administration of a workbench server.

## Installation

1. Clone this repository
1. Install dependencies using `composer install`
1. Initialize the database using `bin/workbench init`

## Specifying working directory

Workbench will by default create files in `~/.workbench/`. You may override this
behaviour by

1. Setting the `--config` option
1. Defining the environment variable `WORKBENCH_PATH`

## TODO ##

### Transition 30/10-18

* Inject all in ImportSieAction, se TODO
* Command namespace rename => Console
* Symfony DI istället för aura...
* Mer inspiration från giroapp setup??
* Bumb symfony dependencies till 4.0

### autoblock

```php
namespace asylgrp\matchmaker\Filter;

use byrokrat\accounting\Sie4\Parser\Sie4ParserFactory;
use asylgrp\matchmaker\AccountingMatchableFactory;
use asylgrp\matchmaker\MatchMaker;
use asylgrp\matchmaker\Matcher\SingleMatcher;
use byrokrat\amount\Currency\SEK;

$parser = (new Sie4ParserFactory)->createParser();

$accounting = $parser->parse(file_get_contents('.se'));

$factory = AccountingMatchableFactory::createFactoryForYear(2017);

$matchables = $factory->createMatchablesForAccount(
    $accounting->select()->getAccount('1503'),
    $accounting
);

$matchMaker = new MatchMaker(new SingleMatcher);

$matches = $matchMaker->match(...$matchables);

$blockingFilter = new LogicalOrFilter(
    new UnaccountedPreviousYearFilter,
    new UnaccountedDateFilter(new \DateTimeImmutable('20180303')),
    new UnaccountedAmountFilter(new SEK('1000'))
);

$result = $blockingFilter->evaluate($matches);

echo $result->getMessage() . "\n";
```

### datamodel
Använd workbenchapp/zip-reader för att kunna läsa decision-filer från workbench.
    - gör att vi exempelvis kan se vilka kp:s som ej har aktivt konto i SIE...
    - om arrayizers även skrivs till datamodel kan vi läsa `contacts.json`
      och `claims.json` från workbench och analysera exempelvis vilka kp:s
      som ska tas bort därför att de har varit inaktiva en viss tid...

### contacts
> workbench contacts
> workbench contacts --no-account
> workbench contacts --banned
> workbench contacts --inactive
> workbench contacts --active

1. Liknar `book` men listar kontaktpersoner istället
1. --no-account listar kp:s från contacts.json som inte finns i bokföringen som konto..
1. Behöver kunna importera contacts.json med import (ImportContactsAction så att samma kan användas vid pull)
1. Sidfot där det kan stå hur många kontaktpersoner vi har, hur många spärrade osv..
1. --inactive listar personer som ej beviljats en utbetalning på 6 månader (eller annan tid...)
1. --active listar tvärt om personer som HAR beviljats utbetalning senaste 6 mån (eller annan tid..)
1. Dessa sista två kräver att även beslut kan importeras och sparas lokalt...

### analyze
> workbench analyze
> workbench analyze 1503
> workbench analyze 1503 --duplicates

1. undersök om en eller flera kp ska spärras (eller alla om inget finns angivet)
1. ska kunna ställa in hur mycket ett datum får variera för att betraktas som match (i dagar)
1. samt hur mycket en summa får variera för att betraktas som en match (i procent)
1. ska presentera både omatchade kvitton och omatchade utbetalningar
