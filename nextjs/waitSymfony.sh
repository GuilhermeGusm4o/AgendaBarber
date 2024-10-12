#!/bin/sh

URL="http://nginx"

until $(curl --output /dev/null --silent --head --fail "$URL"); do
    echo "Waiting for Symfony (via Nginx) to start"
    sleep 5
done

echo "Symfony (via Nginx) is up!"
exec npm run dev
