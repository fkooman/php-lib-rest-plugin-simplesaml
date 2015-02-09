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

namespace fkooman\OAuth\Server;

use fkooman\Http\Request;
use fkooman\Rest\ServicePluginInterface;
use SimpleSAML_Auth_Simple;
use RuntimeException;
use fkooman\Rest\Plugin\UserInfo;

class SimpleSamlAuthentication implements ServicePluginInterface
{
    /** @var SimpleSAML_Auth_Simple */
    private $simpleSaml;

    /** @var string|null */
    private $userIdAttribute;

    public function __construct($simpleSamlPath, $authSource = 'default', $userIdAttribute = null)
    {
        $simpleSamlRequire = sprintf('%s/lib/_autoload.php', $simpleSamlPath);
        if (!file_exists($simpleSamlRequire) || !is_file($simpleSamlRequire) || !is_readable($simpleSamlRequire)) {
            throw new RuntimeException('invalid path to simpleSAMLphp');
        }
        require_once $simpleSamlRequire;
        $this->simpleSaml = new SimpleSAML_Auth_Simple($authSource);
        $this->userIdAttribute = $userIdAttribute;
    }

    public function execute(Request $request)
    {
        if (null === $this->userIdAttribute) {
            // use persistent NameID as user ID
            $this->simpleSaml->requireAuth(
                array(
                    "saml:NameIDPolicy" => "urn:oasis:names:tc:SAML:2.0:nameid-format:persistent",
                )
            );
            $nameId = $this->simpleSaml->getAuthData("saml:sp:NameID");
            if ("urn:oasis:names:tc:SAML:2.0:nameid-format:persistent" !== $nameId['Format']) {
                throw new RuntimeException(
                    sprintf(
                        "NameID format MUST be 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent', but is '%s'",
                        $nameId['Format']
                    )
                );
            }
            return new UserInfo($nameId['Value']);
        } else {
            // use attribute value as user ID
            $this->simpleSaml->requireAuth();
            $attr = $this->simpleSaml->getAttributes();
            if (!isset($attr[$this->userIdAttribute]) || !is_array($attr[$this->userIdAttribute])) {
                throw new RuntimeException(
                    sprintf(
                        "attribute '%s' for resource owner identifier is not available",
                        $userIdAttribute
                    )
                );
            }
            return new UserInfo($attr[$this->userIdAttribute][0]);
        }
    }
}
