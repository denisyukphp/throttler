init:
	docker build -t throttler:8.2 ./

exec:
	docker run --name throttler --rm --interactive --tty --volume ${PWD}:/usr/local/packages/throttler/ throttler:8.2 /bin/bash
