activapi
======
**activapi** is a home automation administration WebApp/API 
included in my home made home automation solution ActivHome.
<br> 

The front-end is made with

![MaterializeCSS logo](https://raw.githubusercontent.com/Dogfalo/materialize/v1-dev/images/m-logo-salmon.png)
<br>
[materializecss](https://materializecss.com/)

A modern responsive front-end framework based on Material Design


## Installation
```bash
composer install
npm install
php bin/sqlSetup.php
npm run babelAll
npm run sass-compile
cp .env.sample .env 
```

## Fill the .env file with your own configuration

### Enable Gmail OAUTH 2 for PhpMailer
[Using Gmail with XOAUTH2](https://github.com/PHPMailer/PHPMailer/wiki/Using-Gmail-with-XOAUTH2)


## License 
* see [LICENSE](https://github.com/sldevand/activapi.fr/blob/master/LICENSE.md) file

