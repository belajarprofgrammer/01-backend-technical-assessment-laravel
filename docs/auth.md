# Api Documentation for Authentication

## Register

Request:

- Method: POST
- Endpoint: `/api/auth/register`
- Header:
    - Content-Type: application/json
    - Accept: application/json

Body:

```json
{
    "name": "string",
    "email": "string",
    "password": "string",
    "password_confirmation": "string"
}
```

Response:

```json
{
    "timestamp": "string",
    "code": "number",
    "status": "string",
    "message": "string",
    "data": {
        "id": "number",
        "name": "string",
        "email": "string",
        "created_at": "string",
        "updated_at": "string"
    }
}
```

## Login

Request:

- Method: POST
- Endpoint: `/api/auth/login`
- Header:
    - Content-Type: application/json
    - Accept: application/json

Body:

```json
{
    "email": "string",
    "password": "string"
}
```

Response:

```json
{
    "timestamp": "string",
    "code": "number",
    "status": "string",
    "message": "string",
    "data": {
        "access_token": "string",
        "expired_at": "number"
    }
}
```

## Logout

Request:

- Method: DELETE
- Endpoint: `/api/auth/logout`
- Header:
    - Accept: application/json
    - Authorization: Bearer `<token>`

Response:

No Content (204)
