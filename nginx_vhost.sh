#!/bin/bash

OPERATION=$1 # add, remove
DOMAIN=$2 # domain name

if [ "$#" -ne 2 ]
then
    echo "Number of arguments doest match, aborting operation."
    exit 1
fi

SSL_EMAIL="vinaysudani9@gmail.com"
NGINX_AVAILABLE_SITES='/etc/nginx/sites-available'
NGINX_ENABLED_SITES='/etc/nginx/sites-enabled'

write_nginx_file (){
    NGINX_FILE=$NGINX_AVAILABLE_SITES/$DOMAIN
    if [ -f "$NGINX_FILE" ]; then
        echo "$NGINX_FILE already exists, aborting operation."
       exit 1
    fi

cat > $NGINX_FILE <<EOF
server {
    listen 80;
    server_name $DOMAIN;

    include snippets/uteach-common.conf;
    include snippets/uteach-http-common.conf;
}

server {
    listen 443 ssl;
    server_name $DOMAIN;

    ssl_certificate /etc/letsencrypt/live/$DOMAIN/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/$DOMAIN/privkey.pem;

    include snippets/uteach-common.conf;
    include snippets/uteach-https-common.conf;
}
EOF
}

if [ $OPERATION == 'add' ]
then
    certbot certonly --webroot --webroot-path=/var/www/html  -d $DOMAIN --non-interactive --agree-tos -m vinaysudani9@gmail.com &&
    write_nginx_file &&
    ln -s $NGINX_AVAILABLE_SITES/$DOMAIN $NGINX_ENABLED_SITES/$DOMAIN &&
    service nginx reload
elif [ $OPERATION == 'remove' ]
then
    rm $NGINX_ENABLED_SITES/$DOMAIN &&
    rm $NGINX_AVAILABLE_SITES/$DOMAIN &&
    service nginx reload &&
    certbot delete --cert-name $DOMAIN
else
    echo "Invalid operation $OPERATION"
    exit 1
fi