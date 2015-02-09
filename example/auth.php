<?php

/**
* Copyright 2014 François Kooman <fkooman@tuxed.net>
*
* Licensed under the Apache License, Version 2.0 (the "License");
* you may not use this file except in compliance with the License.
* You may obtain a copy of the License at
*
* http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS,
* WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
* See the License for the specific language governing permissions and
* limitations under the License.
*/

require_once dirname(__DIR__).'/vendor/autoload.php';

use fkooman\Http\Exception\HttpException;
use fkooman\Http\Exception\InternalServerErrorException;
use fkooman\Rest\Service;
use fkooman\Rest\Plugin\SimpleSaml\SimpleSamlAuthentication;
use fkooman\Rest\Plugin\UserInfo;

try {
    $service = new Service();

    // use persistent NameID value to determine the user ID
    $service->registerBeforeEachMatchPlugin(
        new SimpleSamlAuthentication(
            '/var/www/simplesaml',
            'default'
        )
    );

#    // use an attribute to determine the user ID
#    $service->registerBeforeEachMatchPlugin(
#        new SimpleSamlAuthentication(
#            '/var/www/simplesaml',
#            'default',
#            'eduPersonPrincipalName'
#        )
#    );

    $service->get(
        '/getMyUserId',
        function (UserInfo $u) {
            return sprintf('Hello %s', $u->getUserId());
        }
    );

    $service->run()->sendResponse();
} catch (Exception $e) {
    if ($e instanceof HttpException) {
        $response = $e->getHtmlResponse();
    } else {
        // we catch all other (unexpected) exceptions and return a 500
        $e = new InternalServerErrorException($e->getMessage());
        $response = $e->getHtmlResponse();
    }
    $response->sendResponse();
}
