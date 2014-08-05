## User ##
---

User only wrapper of yii2-user, for detail informations You should read https://github.com/dektrium/yii2-user

### Configuration ###
---
In app config  @app\config\main.php
```php
return [
  'modules' => [
    ...
    'heart' => [
      ...
	  'features'=>[
			...
			'user'=>[
				'components' => [
					'manager' => [
						// Active record classes
						//'userClass'    => 'dektrium\user\models\User',
						//'profileClass' => 'dektrium\user\models\Profile',
						//'accountClass' => 'dektrium\user\models\Account',
						// Model that is used on resending confirmation messages
						//'resendFormClass' => 'dektrium\user\models\ResendForm',
						// Model that is used on logging in
						//'loginFormClass' => 'dektrium\user\models\LoginForm',
						// Model that is used on password recovery
						//'passwordRecoveryFormClass' => 'dektrium\user\models\RecoveryForm',
						// Model that is used on requesting password recovery
						//'passwordRecoveryRequestFormClass' => 'dektrium\user\models\RecoveryRequestForm',
					],
				],        			
				'confirmable' => false,
				'confirmWithin' =>  86400, 
				'allowUnconfirmedLogin' => true,
				'rememberFor' => 1209600,
				'recoverWithin' => 21600,
				'admins' => ['admin'],
				'cost' => 13,
			],	
			...
	  ]
    ]
    ...
  ],
  ...
];
```
