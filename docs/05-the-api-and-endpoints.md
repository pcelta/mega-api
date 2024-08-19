# Endpoints

When the application is initiatialized It creates an administrator user with the following credentials:
|  |  |
|----------|----------|
|   <b>Username</b>  |  admin@mega.co.nz  |
|   <b>Password</b>  |   pass123@456!  |

<br/>
All endpoints that require either a User or Admin role to be accessed uses an a simple authentication mechanism via bearer token.
<br/><br/>

After hitting the POST /auth endpoint with valid credentials, an access and refresh tokens will be provided. Use the access token as bearer token
<br/><br/>
For example
```
Authorization: Bearer: 5cb280ed-097c-00e9-10f3-675d79395d6b
```

### Postman collection
There is a Postman collection import file avaialable in docs/postman_collection.json.
When the authentication is done using the Postman Collection, the access and refresh token are stored as environment variables. So, all the following requests that require a bearer token will automatically be added.


## Health Check
|  |  |
|----------|----------|
|   <b>URI</b>  |   /health-check |
|   <b>HTTP Method</b>  |   GET  |
|   <b>Which roles have access?</b>  |   All including Anonymous. It means it is a public resource  |
|   <b>Response</b>  |    |
```json
{
    "message": "Mega API is up and running!"
}
```

## Authentication

|  |  |
|----------|----------|
|   <b>URI</b>  |   /auth |
|   <b>HTTP Method</b>  |   POST  |
|   <b>Which roles have access?</b>  |   All  |
|   <b>Request body</b>  |     |

```json
{
    "username": "admin@mega.co.nz",
    "password": "pass123@456!"
}
```

|  |  |
|----------|----------|
|   <b>Response</b>  |    |
```json
[
    {
        "token": "5cb280ed-097c-00e9-10f3-675d79395d6b",
        "type": "access",
        "expires_at": "2024-08-18UTC04:24:26",
        "created_at": "2024-08-18UTC10:24:26"
    },
    {
        "token": "2741ae10-040b-0bb2-34e1-856a5890c722",
        "type": "refresh",
        "expires_at": "2024-08-21UTC10:24:26",
        "created_at": "2024-08-18UTC10:24:26"
    }
]
```
Once valid credentials are given the above response will be the result. Two tokens are available. The access token is to access resources, and the refresh token is to update the access token once is expired.
<br/>
<br/>
Access tokens are valid for 24 hours
Refresh tokens are valid for 5 days

<br/>

## Authentication | Refresh token
Once the access token gets expired, a request should be done to this endpoint to get a new access token.

|  |  |
|----------|----------|
|   <b>URI</b>  |   /auth/refresh-token |
|   <b>HTTP Method</b>  |   POST  |
|   <b>Which roles have access?</b>  |   All  |

In this case, the bearer token should contain the refresh token

<br/>

## Role | Fetch one
|  |  |
|----------|----------|
|   <b>URI</b>  |   /role/:role-slug |
|   <b>HTTP Method</b>  |   POST  |
|   <b>Which roles have access?</b>  |   Administrator  |
|   <b>Request body</b>  |     |

```json
{
    "username": "admin@mega.co.nz",
    "password": "pass123@456!"
}
```

|  |  |
|----------|----------|
|   <b>Response</b>  |    |
```json
[
    {
        "token": "5cb280ed-097c-00e9-10f3-675d79395d6b",
        "type": "access",
        "expires_at": "2024-08-18UTC04:24:26",
        "created_at": "2024-08-18UTC10:24:26"
    },
    {
        "token": "2741ae10-040b-0bb2-34e1-856a5890c722",
        "type": "refresh",
        "expires_at": "2024-08-21UTC10:24:26",
        "created_at": "2024-08-18UTC10:24:26"
    }
]
```

## User | Fetch all
|  |  |
|----------|----------|
|   <b>URI</b>  |   /user |
|   <b>HTTP Method</b>  |   GET  |
|   <b>Which roles have access?</b>  |   Administrator  |
|   <b>HTTP Response</b>  |     |

```json
[
    {
        "created_at": "2024-08-17UTC18:57:39",
        "updated_at": "2024-08-17UTC18:57:39",
        "uid": "550e8400-e29b-41d4-a716-446655440011",
        "username": "admin@mega.co.nz",
        "is_active": true
    },
    {
        "created_at": "2024-08-17UTC19:24:57",
        "updated_at": "2024-08-17UTC19:24:57",
        "uid": "8bf2cca3-0183-013c-0de7-47c5c754662e",
        "username": "regular@admin-mega.com",
        "is_active": true
    },
    {
        "created_at": "2024-08-17UTC19:27:59",
        "updated_at": "2024-08-17UTC19:27:59",
        "uid": "56e641d7-e9bd-03ef-3dc1-2d9150ebc48a",
        "username": "regulara@admin-mega.com",
        "is_active": true
    }
]
```

## User | Fetch one
|  |  |
|----------|----------|
|   <b>URI</b>  |   /user/:uid: |
|   <b>HTTP Method</b>  |   GET  |
|   <b>Which roles have access?</b>  |   Administrator  |
|   <b>HTTP Response</b>  |     |

```json
{
    "created_at": "2024-08-17UTC18:57:39",
    "updated_at": "2024-08-17UTC18:57:39",
    "uid": "550e8400-e29b-41d4-a716-446655440011",
    "username": "admin@mega.co.nz",
    "is_active": true,
    "roles": [
        {
            "created_at": "2024-08-17UTC18:57:39",
            "updated_at": "2024-08-17UTC18:57:39",
            "uid": "550e8400-e29b-41d4-a716-446655440001",
            "name": "Admin",
            "slug": "role-admin"
        }
    ]
}
```

## User | Disable
|  |  |
|----------|----------|
|   <b>URI</b>  |   /user/:uid: |
|   <b>HTTP Method</b>  |   DELETE  |
|   <b>Which roles have access?</b>  |   Administrator  |
|   <b>HTTP Response</b>  |     |

```json
{
    "message": "User has successfully been disabled"
}
```

## User | Update
|  |  |
|----------|----------|
|   <b>URI</b>  |   /user/:uid: |
|   <b>HTTP Method</b>  |   PATCH  |
|   <b>Limitation</b>  |   Does not update username  |
|   <b>Which roles have access?</b>  |   Administrator  |
|   <b>Request Body</b>  |     |

```json
{
    "password": "12345678",
    "roles": [
        {
            "uid": "550e8400-e29b-41d4-a716-446655440000"
        }
    ]
}
```

|  |  |
|----------|----------|
|   <b>Response</b>  |    |
```json
{
    "created_at": "2024-08-17UTC19:24:57",
    "updated_at": "2024-08-17UTC19:24:57",
    "uid": "8bf2cca3-0183-013c-0de7-47c5c754662e",
    "username": "regular@admin-mega.com",
    "is_active": true,
    "roles": [
        {
            "created_at": "2024-08-17UTC18:57:39",
            "updated_at": "2024-08-17UTC18:57:39",
            "uid": "550e8400-e29b-41d4-a716-446655440000",
            "name": "User",
            "slug": "role-user"
        }
    ]
}
```

## File | Upload
|  |  |
|----------|----------|
|   <b>URI</b>  |   /file |
|   <b>HTTP Method</b>  |   POST  |
|   <b>Which roles have access?</b>  |   Administrator, User  |
|   <b>Request Data type</b>  |   Multipart/Form-Data  |
|   <b>Form</b>  |     |
|   <b>form.file</b>  |  file to be uploaded   |
|   <b>form.name</b>  |  desired file name   |

|  |  |
|----------|----------|
|   <b>Response</b>  |    |
```json
{
    "message": "File uploaded!",
    "file_data": {
        "created_at": "2024-08-19UTC09:19:37",
        "updated_at": "2024-08-19UTC09:19:37",
        "uid": "b75b819f-3978-014e-291c-841bfa1d2bfe",
        "name": "my small image",
        "size": 16491,
        "contentType": "image/png",
            "links": [
        {
            "type": "metadata",
            "link": "/file/b75b819f-3978-014e-291c-841bfa1d2bfe"
        },
        {
            "type": "download",
            "link": "/file/b75b819f-3978-014e-291c-841bfa1d2bfe?download=true"
        }
    ],
        "user": {
            "created_at": "2024-08-19UTC09:14:00",
            "updated_at": "2024-08-19UTC09:14:00",
            "uid": "84f80a21-6aea-0e46-2041-f028cae765b1",
            "username": "regular@admin-mega.com",
            "is_active": true
        }
    }
}
```

## File | Update
|  |  |
|----------|----------|
|   <b>URI</b>  |   /file/:uid: |
|   <b>HTTP Method</b>  |   POST  |
|   <b>Important</b>  | it should be a PUT but there was not enough time to develop a PUT parameter parser using form multipart/form-data |
|   <b>Which roles have access?</b>  |   Administrator, User  |
|   <b>Request Data type</b>  |   Multipart/Form-Data  |
|   <b>Form</b>  |     |
|   <b>form.file</b>  |  file to be uploaded   |
|   <b>form.name</b>  |  desired file name   |

|  |  |
|----------|----------|
|   <b>Response</b>  |    |
```json
{
    "message": "File uploaded!",
    "file_data": {
        "created_at": "2024-08-19UTC09:19:37",
        "updated_at": "2024-08-19UTC09:19:37",
        "uid": "b75b819f-3978-014e-291c-841bfa1d2bfe",
        "name": "my small image",
        "size": 16491,
        "contentType": "image/png",
            "links": [
        {
            "type": "metadata",
            "link": "/file/b75b819f-3978-014e-291c-841bfa1d2bfe"
        },
        {
            "type": "download",
            "link": "/file/b75b819f-3978-014e-291c-841bfa1d2bfe?download=true"
        }
    ],
        "user": {
            "created_at": "2024-08-19UTC09:14:00",
            "updated_at": "2024-08-19UTC09:14:00",
            "uid": "84f80a21-6aea-0e46-2041-f028cae765b1",
            "username": "regular@admin-mega.com",
            "is_active": true
        }
    }
}
```

## File | Fetch One
|  |  |
|----------|----------|
|   <b>URI</b>  |   /file/:uid: |
|   <b>HTTP Method</b>  |   GET  |
|   <b>Which roles have access?</b>  |   Administrator, User  |
|   <b>Query String Parameters</b>  |     |
|   <b>download</b>  |   when given with true value, the response will force a download os the file. Example: /file/b75b819f-3978-014e-291c-841bfa1d2bfe?download=true   |


|  |  |
|----------|----------|
|   <b>Response</b>  |    |
```json
{
    "created_at": "2024-08-19UTC09:19:37",
    "updated_at": "2024-08-19UTC09:19:37",
    "uid": "b75b819f-3978-014e-291c-841bfa1d2bfe",
    "contentType": "image/png",
    "name": "my small image",
    "size": 16491,
    "links": [
        {
            "type": "metadata",
            "link": "/file/b75b819f-3978-014e-291c-841bfa1d2bfe"
        },
        {
            "type": "download",
            "link": "/file/b75b819f-3978-014e-291c-841bfa1d2bfe?download=true"
        }
    ],
    "user": {
        "created_at": "2024-08-19UTC09:14:00",
        "updated_at": "2024-08-19UTC09:14:00",
        "uid": "84f80a21-6aea-0e46-2041-f028cae765b1",
        "username": "regular@admin-mega.com",
        "is_active": true
    }
}
```

## File | Fetch all
|  |  |
|----------|----------|
|   <b>URI</b>  |   /file |
|   <b>HTTP Method</b>  |   GET  |
|   <b>Which roles have access?</b>  |   Administrator, User  |


|  |  |
|----------|----------|
|   <b>Response</b>  |    |
```json
[
    {
        "created_at": "2024-08-19UTC10:02:31",
        "updated_at": "2024-08-19UTC10:02:31",
        "uid": "23f1d88a-7d93-0f76-12aa-307603d60cc5",
        "contentType": "image/png",
        "name": "my small image",
        "size": 16491,
        "links": [
            {
                "type": "metadata",
                "link": "/file/23f1d88a-7d93-0f76-12aa-307603d60cc5"
            },
            {
                "type": "download",
                "link": "/file/23f1d88a-7d93-0f76-12aa-307603d60cc5?download=true"
            }
        ],
        "user": {
            "created_at": "2024-08-19UTC09:43:46",
            "updated_at": "2024-08-19UTC09:43:46",
            "uid": "d5090c43-1d46-0d89-2884-5c7b22db6418",
            "username": "regular@admin-mega.com",
            "is_active": true
        }
    },
    {
        "created_at": "2024-08-19UTC10:15:59",
        "updated_at": "2024-08-19UTC10:15:59",
        "uid": "7089716e-72f6-0abc-235b-3cc5407a04e6",
        "contentType": "image/png",
        "name": "my small image 2",
        "size": 16491,
        "links": [
            {
                "type": "metadata",
                "link": "/file/7089716e-72f6-0abc-235b-3cc5407a04e6"
            },
            {
                "type": "download",
                "link": "/file/7089716e-72f6-0abc-235b-3cc5407a04e6?download=true"
            }
        ],
        "user": {
            "created_at": "2024-08-19UTC09:43:46",
            "updated_at": "2024-08-19UTC09:43:46",
            "uid": "d5090c43-1d46-0d89-2884-5c7b22db6418",
            "username": "regular@admin-mega.com",
            "is_active": true
        }
    }
]
```

## File | Delete One
|  |  |
|----------|----------|
|   <b>URI</b>  |   /file/:uid: |
|   <b>HTTP Method</b>  |   DELETE  |
|   <b>Which roles have access?</b>  |   Administrator, User  |


|  |  |
|----------|----------|
|   <b>Response</b>  |    |
```json
{
    "message": "File has been deleted!"
}
```
