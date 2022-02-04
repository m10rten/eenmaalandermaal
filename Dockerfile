FROM php:7.4.6-cli-alpine3.11
VOLUME /srv/webserver
COPY ./provision.sh /root/provision.sh
RUN /root/provision.sh
WORKDIR /srv/webserver/
