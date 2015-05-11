Opauth-LoginCidadao
=============
[Opauth][1] strategy for LoginCidadao authentication.

Implemented based on https://developers.loginCidadao.com/accounts/docs/OAuth2 using OAuth 2.0.

Opauth is a multi-provider authentication framework for PHP.

Getting started
----------------
1. Install Opauth-LoginCidadao:
   ```bash
   cd path_to_opauth/Strategy
   git clone git://github.com/uzyn/opauth-loginCidadao.git LoginCidadao
   ```

2. Create a LoginCidadao APIs project at https://code.loginCidadao.com/apis/console/
   - You do not have to enable any services from the Services tab.
   - Make sure to go to **API Access** tab and **Create an OAuth 2.0 client ID**.
   - Choose **Web application** for *Application type*
   - Make sure that redirect URI is set to actual OAuth 2.0 callback URL, usually `http://path_to_opauth/loginCidadao/oauth2callback`


3. Configure Opauth-LoginCidadao strategy.

4. Direct user to `http://path_to_opauth/loginCidadao` to authenticate


Strategy configuration
----------------------

Required parameters:

```php
<?php
'LoginCidadao' => array(
	'client_id' => 'YOUR CLIENT ID',
	'client_secret' => 'YOUR CLIENT SECRET'
)
```

Optional parameters:
`auth_endpoint`, `token_endpoint`, `user_info_endpoint`, `redirect_uri`, `scope`, `state`, `access_type`, `approval_prompt`


References
----------
- [Using OAuth 2.0 to Access LoginCidadao APIs](https://developers.loginCidadao.com/accounts/docs/OAuth2)
- [Using OAuth 2.0 for Login](https://developers.loginCidadao.com/accounts/docs/OAuth2Login#scopeparameter)
- [Using OAuth 2.0 for Web Server Applications](https://developers.loginCidadao.com/accounts/docs/OAuth2WebServer)

License
---------
Opauth-LoginCidadao is MIT Licensed
Copyright © 2012 U-Zyn Chua (http://uzyn.com)

[1]: https://github.com/uzyn/opauth