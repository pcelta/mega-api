# Foundation
One of the requirements for this Mega API is not to use any existing library or framework. Therefore, some foundation was necessary to be put in place before implementing the features. This doc covers all libraries and structures that have been created to facilitate to development.

## Autoload
In a regular scenario, the autoload that is usually used is the Composer's. However, a new autoload mechanism was implemented to respect the non library or framework usage. <br/><br/>
It is located in bootstrap.php. <br/>
Everytime a class is instantiated and PHP does not found it in the internal class path, It will call the function megaAutoload passing the full class name including the namespace. This function parses the namespace and transform it into a directory + file name<br/>


## Router
This Router component reads a config file from config/routes.php which defines all http routes availables and what combination of controller + action that will handle them. It get much  easier to create new routes after this.<br/><br/>

Example
```php
[
    'route' => '/health-check',
    'method' => 'GET',
    'controller' => HealthCheckController::class,
    'action' => 'index',
    'allowed' => [Role::ROLE_ADMIN, Role::ROLE_USER, Role::ROLE_ANONYMOUS],
],
```

|  |  |
|----------|----------|
|   <b>route</b>  |   URI  |
|   <b>method</b>  |   HTTP Method. Possible values are GET, PUT, POST, PATCH or DELETE  |
|   <b>controller</b>  |   A controller class  |
|   <b>action</b>  |   A controller method that will handle the request  |
|   <b>allowed</b>  |   The user roles that are allowed to access this action  |


## Controllers and HTTP components
The controllers facilitate the application to receive externals calls. In this case, HTTP calls. <br/>
Alongside the Controllers other components are also needed

|  |  |
|----------|----------|
|   <b>Request</b>  |   Encapsulate all PHP global variableas such as $_SERVER, $_REQUEST, $_GET AND $_POST. An instance of Request is automatically given to controller action as an argument|
|   <b>Response</b>  |   All controller actions must return a Response which will manage http headers, status codes and body  |
|   <b>JsonResponse</b>  |   It is a subclass of Response to facilitate responses with application/json headers and content  |


## Service Locator
Service Locator is a well known pattern to abstract instantiation with dependencies. By implementing it, it eliminates all manual instantiation within the code. It help a lot with developing a cleaner code and reducing code duplication. <br/><br/>

The Service Locator component reads a config file located in: config/services.php which defines all available classes to be used as dependencies across the application.<br/><br/>

Example
```php
[
    UserAccessRepository::class => [
        'name' => UserAccessRepository::class,
        'args' => [
            PDO::class
        ]
    ],
]
```

|  |  |
|----------|----------|
|   <b>name</b>  | Class name including namespace |
|   <b>args</b>  | List of class names this service depends on. They will be instantiated and given as constructor arguments |


## Authorization
Users have roles that give them specific access to the endpoints. To acess certain endpoints the user must have more priviliged role. <br/>

Available Roles:
|  |  |
|----------|----------|
|   <b>Anonymous</b>  | Anonymous is the default role for a unauthenticated user. |
|   <b>User</b>  | User is the role that regular users will have. That gives access to the upload features |
|   <b>Admin</b>  | The admin role give special access to administration operations such as: user creation |


<br/>
Routes are tagged with one or more roles to define which role should be able to access it. <br/>
Example


```php
[
    'route' => '/health-check',
    'method' => 'GET',
    'controller' => HealthCheckController::class,
    'action' => 'index',
    'allowed' => [Role::ROLE_ADMIN, Role::ROLE_USER, Role::ROLE_ANONYMOUS],
],
```
In this example, all 3 roles have access to this endpoint.

## Uid Factory
A Uid Factory was implemented to generate uids for the entities such User. A Uid has 36 characters long and use a specific logic to respect it.
<br/>
It is located in: lib/Uid.php


## Schema Validator
The Schema Validator component was implemented to facilitate request body validation where a specific json format must be respected.

Example

```php
$schema = [
    [
        'field_name' => 'slug',
        'validation' => SchemaValidator::FIELD_TYPE_STRING,
    ],
    [
        'field_name' => 'password',
        'validation' => SchemaValidator::FIELD_TYPE_STRING,
        'optional' => true,
    ],
    [
        'field_name' => 'number',
        'validation' => SchemaValidator::FIELD_TYPE_INT,
    ],
    [
        'field_name' => 'role',
        'validation' => SchemaValidator::FIELD_TYPE_OBJECT,
        'schema' => [
            [
                'field_name' => 'uid',
                'validation' => SchemaValidator::FIELD_TYPE_STRING,
            ]
        ],
    ],
    [
        'field_name' => 'roles',
        'validation' => SchemaValidator::FIELD_TYPE_LIST_OF_OBJECTS,
        'schema' => [
            [
                'field_name' => 'uid',
                'validation' => SchemaValidator::FIELD_TYPE_STRING,
            ]
        ],
    ],
];

$postData = [
    'slug' => 'role-user',
    'number' => 10,
    'role' => [
        'uid' => '550e8400-e29b-41d4-a716-446655440000',
    ],
    'roles' => [[
            'uid' => '550e8400-e29b-41d4-a716-446655440000',
        ]
    ]
];

$schemaValidator = new SchemaValidator();
$result = $schemaValidator->validate($schema, $postData);
```
It makes much easier to validate request data.
