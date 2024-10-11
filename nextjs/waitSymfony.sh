#!/bin/sh

while ! nc -z symfony 9000; do
    echo "Waiting for Symfony to start"
    sleep 2
done

echo "Symfony is ready! Starting Next.js"
exec "$@"
