<?php
/*
* (c) Netvlies Internetdiensten
*
* Richard van den Brand <richard@netvlies.net>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Netvlies\Bundle\BasecampBundle\OAuth\Factory;

use Netvlies\Bundle\BasecampBundle\OAuth\CredentialsProviderInterface;
use Basecamp\BasecampClient;

class ClientFactory
{
    private $provider;

    public function __construct(CredentialsProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    public function factory(array $options)
    {
        $options = array_merge(
            $options,
            array(
                'user_id' => $this->provider->getBasecampId(),
                'token' => $this->provider->getToken()
            )
        );
        return BasecampClient::factory($options);
    }
}