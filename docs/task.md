# Api Documentation for Task

## Get Tasks

Request:

- Method: GET
- Endpoint: `/api/tasks`
- Header:
    - Accept: application/json
    - Authorization: Bearer `<token>`
- Query:
    - keyword: null
    - status: null
    - is_recurring: null
    - recurring_interval: null
    - due_date_from: null
    - due_date_to: null
    - page: 1
    - per_page: 15

Response:

```json
{
    "timestamp": "string",
    "code": "number",
    "status": "string",
    "message": "string",
    "data": [
        {
            "id": "number",
            "title": "string",
            "description": "string",
            "status": "string",
            "due_date": "string",
            "is_recurring": "boolean",
            "recurring_interval": "string",
            "created_at": "string",
            "updated_at": "string"
        }
    ]
}
```

## Create Task

Request:

- Method: POST
- Endpoint: `/api/tasks`
- Header:
    - Content-Type: application/json
    - Accept: application/json
    - Authorization: Bearer `<token>`

Body:

```json
{
    "title": "string",
    "description": "string",
    "due_date": "string",
    "is_recurring": "boolean",
    "recurring_interval": "string"
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
        "title": "string",
        "description": "string",
        "status": "string",
        "due_date": "string",
        "is_recurring": "boolean",
        "recurring_interval": "string",
        "created_at": "string",
        "updated_at": "string"
    }
}
```

## Get Task

Request:

- Method: GET
- Endpoint: `/api/tasks/:id`
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
        "title": "string",
        "description": "string",
        "status": "string",
        "due_date": "string",
        "is_recurring": "boolean",
        "recurring_interval": "string",
        "created_at": "string",
        "updated_at": "string"
    }
}
```

## Update Task

Request:

- Method: PATCH
- Endpoint: `/api/tasks/:id`
- Header:
    - Content-Type: application/json
    - Accept: application/json
    - Authorization: Bearer `<token>`

Body:

```json
{
    "title": "string",
    "description": "string",
    "due_date": "string",
    "is_recurring": "boolean",
    "recurring_interval": "string"
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
        "title": "string",
        "description": "string",
        "status": "string",
        "due_date": "string",
        "is_recurring": "boolean",
        "recurring_interval": "string",
        "created_at": "string",
        "updated_at": "string"
    }
}
```

## Delete Task

Request:

- Method: DELETE
- Endpoint: `/api/tasks/:id`
- Header:
    - Accept: application/json
    - Authorization: Bearer `<token>`

Response:

No Content (204)

## Update Status of Task

Request:

- Method: PATCH
- Endpoint: `/api/tasks/:id/status`
- Header:
    - Content-Type: application/json
    - Accept: application/json
    - Authorization: Bearer `<token>`

Body:

```json
{
    "status": "string"
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
        "title": "string",
        "description": "string",
        "status": "string",
        "due_date": "string",
        "is_recurring": "boolean",
        "recurring_interval": "string",
        "created_at": "string",
        "updated_at": "string"
    }
}
```
