# Workbench CLI

[![Build Status](https://img.shields.io/travis/asylgrp/workbench-cli/master.svg?style=flat-square)](https://travis-ci.org/asylgrp/workbench-cli)
[![Quality Score](https://img.shields.io/scrutinizer/g/asylgrp/workbench-cli.svg?style=flat-square)](https://scrutinizer-ci.com/g/asylgrp/workbench-cli)

CLI frontend for administration of a workbench server.

## Installation

1. Build using `bob`
1. Initialize the database using `./workbench.phar init`

### Specifying working directory

Workbench will by default create files in a directory called `workbench` in the
current working directory. You may override this behaviour by defining the
environment variable `WORKBENCH_PATH`.

## TODO ##

1. Någon form av bugg i matchmaker, loopar. Se 1506.
1. Accounting `whereDescription` finns tydligen inte längre... till accounting_macros.php
1. Progress bar till analyze, det kan bli fint =)
1. Analyze kan trimmas genom att hoppa över de med saldo 0
1. Presentation av matchis i analyze. Exempelvis kunna skapa lista på saknade kvitton till mail..

### datamodel
Använd workbenchapp/zip-reader för att kunna läsa decision-filer från workbench.
    - gör att vi exempelvis kan se vilka kp:s som ej har aktivt konto i SIE...
    - om arrayizers även skrivs till datamodel kan vi läsa `contacts.json`
      och `claims.json` från workbench och analysera exempelvis vilka kp:s
      som ska tas bort därför att de har varit inaktiva en viss tid...

### contacts
```
workbench contacts
workbench contacts --no-account
workbench contacts --banned
workbench contacts --inactive
workbench contacts --active
```

1. Liknar `book` men listar kontaktpersoner istället
1. --no-account listar kp:s från contacts.json som inte finns i bokföringen som konto..
1. Behöver kunna importera contacts.json med import (ImportContactsAction så att samma kan användas vid pull)
1. Sidfot där det kan stå hur många kontaktpersoner vi har, hur många spärrade osv..
1. --inactive listar personer som ej beviljats en utbetalning på 6 månader (eller annan tid...)
1. --active listar tvärt om personer som HAR beviljats utbetalning senaste 6 mån (eller annan tid..)
1. Dessa sista två kräver att även beslut kan importeras och sparas lokalt...
