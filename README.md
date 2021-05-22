# Kupaliska (test project)

## Starting the server
Run `docker-compose up` to bring up the server. It'll be reachable at http://localhost:8000.

This is a standard Laravel project, so it can also be run using PHP & Composer (see [Dockerfile](./Dockerfile)).

## Running tests
While the server is running, enter 

```
docker-compose exec web php artisan test
```
    
to run its feature tests. 

## Example requests (endpoints)

### Purchase (create) a ticket
```
curl -L -X POST 'http://localhost:8000/api/purchase' \
    -H 'Accept: application/json' \
    -H 'Content-Type: application/json' \
    -d '{"type": "1_entry"}'
```

Available ticket `type`s are: `1_entry`, `10_entries` and `season`.

Please note the `Accept: application/json` header.

The server will respond with a `ticket_uuid`:
```
{"ticket_uuid":"2eda1c6f-b73d-49ad-bab1-acfffd9d6281"}
```

You can use the `ticket_uuid` to make further calls.

### Enter a pool
```
curl -L -X POST 'http://localhost:8000/api/enter' \
    -H 'Accept: application/json' \
    -H 'Content-Type: application/json' \
    -d '{
        "ticket_uuid": "2eda1c6f-b73d-49ad-bab1-acfffd9d6281",
        "pool": "rosnicka"
    }'
```

Available `pool`s are: `delfin`, `rosnicka`, `zlate_piesky`, `tehelne_pole`.

You will get a response, such as:
```
{
    "admit": true,
}
```

or (in case of an error)

```
{
    "admit": false,
    "message": "This ticket has no more entries left"
}
```


### Exit a pool
```
curl -L -X POST 'http://localhost:8000/api/exit' \
    -H 'Accept: application/json' \
    -H 'Content-Type: application/json' \
    -d '{
        "ticket_uuid": "2eda1c6f-b73d-49ad-bab1-acfffd9d6281",
        "pool": "rosnicka"
    }'
```

This will return an empty HTTP 200 response.
