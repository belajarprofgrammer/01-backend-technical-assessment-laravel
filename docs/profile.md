# Api Documentation for Profile

## Get Profile

Request:

- Method: GET
- Endpoint: `/api/profile`
- Header:
    - Accept: application/json
    - Authorization: Bearer `<token>`

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
        "email_verified_at": "string",
        "created_at": "string",
        "updated_at": "string"
    }
}
```

## Update Profile

Request:

- Method: PATCH
- Endpoint: `/api/profile`
- Header:
    - Content-Type: application/json
    - Accept: application/json
    - Authorization: Bearer `<token>`

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
        "email_verified_at": "string",
        "created_at": "string",
        "updated_at": "string"
    }
}
```

## Delete Profile

Request:

- Method: DELETE
- Endpoint: `/api/profile`
- Header:
    - Accept: application/json
    - Authorization: Bearer `<token>`

Response:

No Content (204)
