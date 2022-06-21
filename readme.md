# Storage service

Task on raise qualification.

### Install

Requirements:

- [Git](https://git-scm.com/downloads) any version
- [Docker](https://docker.com/get-started/) (version ~20.10+ developed)
- [Docker compose](https://docs.docker.com/compose/install/) (version ~1.29+ developed)


1. need to download or clone the repository: (run terminal next command)

```bash
git clone git@github.com:roma-bb8/storage-service.git
```

2. go to directory `storage-service` next command:

```bash
docker-compose up --build -d
```

and after completion previous command, run:

```bash
docker exec -ti php sh -c "composer update"
```

### The simplest example of use

```bash
docker exec -ti php sh -c "php ./bin/cli help"
```

out:

```
Xdebug: [Step Debug] Could not connect to debugging client. Tried: host.docker.internal:9003 (through xdebug.client_host/xdebug.client_port) :-(

Usage:
php ./bin/cli command [ method ] [ args ]

Commands:
help [ main ] (Will display this message)
trash storage [-f] [days] (Remove entries from the collection)
```

### Docs

See the complete description in [here](https://github.com/roma-bb8/storage-service/wiki).
