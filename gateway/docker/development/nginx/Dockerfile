FROM nginx:1.17-alpine

COPY ../../../../api/docker/common /etc/nginx/snippets
COPY ./development/nginx/conf.d /etc/nginx/conf.d

WORKDIR /app