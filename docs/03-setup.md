### Pre-requirements

- Make
- Docker
- Docker Compose

### Installation

Add the following line to your /etc/hosts file
```sh
127.0.0.1 local-mega-api
```

In the root directory, run the build command. This will build the necessary docker images

```sh
make build
```

Start and run the docker containers

```sh
make start
```

You should be able to access this url: http://local-mega-api/health-check in your browser. You should see the response below

```json
{
  "message": "Mega API is up and running!"
}
```

<br/>
Once the application is running you can see that there are 3 Docker containers up by running: <b>docker-compose ps<b/>
<br/>
<br/>

Containers
|  |  |
|----------|----------|
|   <b>mega-mysql-db</b>  |   MySQL version 8  |
|   <b>mega-api-php-fpm</b>  |   PHP FPM to interpret the php code |
|   <b>mega-nginx</b>  |   Nginx as a reverse proxy to handle HTTP request and forward to PHP FPM  |


<br/>

### Other useful available commands

Show all available commands

```sh
make help
```

Stop the containers

```sh
make stop
```

Stop and remove all containers

```sh
make reset
```

Run the unit tests

```sh
make phpunit
```
