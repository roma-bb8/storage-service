# Storage service

Task on raise qualification.

### Install

Requirements:

- [*nix](https://en.wikipedia.org/wiki/Unix-like) any version
- [Git](https://git-scm.com/downloads) any version
- [Docker](https://docker.com/get-started/) (version ~20.10+ developed)
- [Docker compose](https://docs.docker.com/compose/install/) (version ~1.29+ developed)


1. need to download or clone the repository: (run terminal next commands)

```bash
git clone git@github.com:roma-bb8/storage-service.git && \
cd storage-service && \
make
```

2. command `make` output next message:

```bash
----------------------------------------------------------------------
This is a help message.
Shows all available commands and their description.
----------------------------------------------------------------------
bin: Run application service command. Example: make bin c="help"
login-php: Login shell php container.
login-mongodb: Login shell MongoSH.
ps: List services containers.
containers-logs: View output from containers.
clean-file-logs: Clean file logs.
dev-build: Build or rebuild (for development) services.
dev-up: create-folder-tree Create and start (for development) services.
dev-start: Start (for development) services.
dev-stop: Stop (for development) services.
dev-destroy: Destroy (for development) services.
prod-build: Build or rebuild (for production) services.
prod-up: create-folder-tree Create and start (for production) services.
```

and after completion previous command, run:

```bash
make dev-build && make dev-up
```

### The simplest example of use

```bash
make bin c="help"
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

### another example:

```bash
make bin c="trash storage -f 24"
```

### Docs

See the complete description in [here](https://github.com/roma-bb8/storage-service/wiki).
