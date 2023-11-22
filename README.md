Opauth-Authentik
=============
[Opauth][1] strategy for Authentik authentication.

Implemented based on https://developers.loginCidadao.com/accounts/docs/OAuth2 using OAuth 2.0.

Opauth is a multi-provider authentication framework for PHP.

Getting started
----------------
1. Install Opauth-Authentik:
   ```bash
   cd path_to_opauth/Strategy
   git clone git://github.com/uzyn/opauth-loginCidadao.git Authentik
   ```

2. Install a Authentik
   - Create a OAuth 2.0 Provider, save the client Id and secret
   - Create a Application using the OAuth 2.0 Provider


3. Configure Opauth-Authentik strategy.

4. Direct user to `http://path_to_opauth/authentik` to authenticate


Strategy configuration
----------------------

Required parameters:

```php
<?php
'Authentik' => array(
	'client_id' => 'YOUR CLIENT ID',
	'client_secret' => 'YOUR CLIENT SECRET',
	''
)
```

Optional parameters:
`auth_endpoint`, `token_endpoint`, `user_info_endpoint`, `redirect_uri`, `scope`, `state`, `access_type`, `approval_prompt`


References
----------
- [Authentik Docs](https://goauthentik.io/developer-docs/)
- [Using OAuth 2.0 to Access Login Cidadão APIs](https://developers.loginCidadao.com/accounts/docs/OAuth2)
- [Using OAuth 2.0 for Login](https://developers.loginCidadao.com/accounts/docs/OAuth2Login#scopeparameter)
- [Using OAuth 2.0 for Web Server Applications](https://developers.loginCidadao.com/accounts/docs/OAuth2WebServer)

License
---------
Opauth-Authentik is MIT Licensed
Copyright © 2012 U-Zyn Chua (http://uzyn.com)

[1]: https://github.com/uzyn/opauth
