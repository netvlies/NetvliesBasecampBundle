<?php
/*
* (c) Netvlies Internetdiensten
*
* Richard van den Brand <richard@netvlies.net>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Netvlies\Bundle\BasecampBundle\OAuth;

interface CredentialsProviderInterface
{
    public function getBasecampId();

    public function getToken();
}
