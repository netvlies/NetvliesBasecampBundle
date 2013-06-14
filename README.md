NetvliesBasecampBundle
----------------------

Symfony2 bundle around the basecamp-php client.

Installation
------------
Use Composer to install the bundle:

    $ composer.phar require netvlies/basecamp-bundle

Enable the bunble in your kernel:

```php
// app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Netvlies\Bundle\BasecampBundle\NetvliesBasecampBundle(),
    );
}
```

Configuration
-------------

This bundle supports authentication via HTTP authentication (easy and quick) or OAuth (a little bit more difficult).

#### HTTP authentication

Below is an example of the minimal configuration using HTTP authentication. Only thing you need to do is supply your own credentials.

```yaml
# app/config/config.yml
netvlies_basecamp:
    authentication: http
    app_name: My Funky Application
    app_contact: http://www.myfunkyapplication.com
    identification:
        user_id: 1234
        username: your@username.com
        password: secret
```

### OAuth authentication

If you have a more advanced use case you probably want to use OAuth. To implement OAuth in your Symfony2 project we recommend the [HWIOAuthBundle](https://github.com/hwi/HWIOAuthBundle).

#### Create an OAuth credentials provider

First start by implementing the <code>Netvlies\Bundle\BasecampBundle\OAuth\CredentialsProviderInterface</code>. Below is an simple example assuming you store the OAuth data in your <code>User</code> entity:


```php

namespace Acme\Bundle\MainBundle\OAuth;

use Netvlies\Bundle\BasecampBundle\OAuth\CredentialsProviderInterface;
use Symfony\Component\Security\Core\SecurityContext;

class BasecampCredentialsProvider implements CredentialsProviderInterface
{
    protected $context;

    public function __construct(SecurityContext $context)
    {
        $this->context = $context;
    }

    public function getBasecampId()
    {
        if (! $this->context->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new \RuntimeException('Please login before using Basecamp');
        }

        return $this->context->getToken()->getUser()->getBasecampId();
    }

    public function getToken()
    {
        if (! $this->context->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new \RuntimeException('Please login before using Basecamp');
        }

        return $this->context->getToken()->getUser()->getToken();
    }
}
```

Now register this as a service:

```yaml
services:
    acme.basecamp.oauth_credentials_provider:
        class: Acme\Bundle\MainBundle\OAuth\BasecampCredentialsProvider
        arguments: @security.context
```

#### Configure the bundle to use your provider

Finally supply the service id into the bundle configuration like this:

```yaml
netvlies_basecamp:
    authentication: oauth
    app_name: My Funky Application
    app_contact: http://www.myfunkyapplication.com
    oauth:
        credentials_provider: acme.basecamp.oauth_credentials_provider
```

### Usage

You can now get the client from the container and use it:

```php
$client = $this->get('basecamp');
$project = $client->getProject(array(
    'projectId' => 1
));
```
