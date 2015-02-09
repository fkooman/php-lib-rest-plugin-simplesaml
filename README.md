[![Build Status](https://travis-ci.org/fkooman/php-lib-rest-plugin-simplesaml.svg?branch=master)](https://travis-ci.org/fkooman/php-lib-rest-plugin-simplesaml)

# Introduction
This plugin enables one to use SAML authentication with `fkooman/rest` using
[simpleSAMLphp](https://simplesamlphp.org). 

# Configuation
You need to configure simpleSAMLphp to work with your IdP and determine whether 
you want to use the persistent NameID value or an attribute value to determine
the user ID.

The following information is needed:

* path to simpleSAMLphp, e.g.: `/var/www/simplesaml`;
* the name of the authentication source, e.g.: `default`;
* the name of the attribute (if any) to be used to determine the user ID, e.g.: 
  `eduPersonPrincipalName`, or `null` if you want to use the NameID value

See the `example` directory for an example on how to use this authentication 
plugin.
