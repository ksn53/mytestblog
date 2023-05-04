#!/usr/bin/env sh

#Генерируем site.conf и запускаем nginx
envsubst '\$COMPOSE_PROJECT_NAME \$APP_ASSETS_VERSION \$APP_CLOUT_S3_ENDPOINT \$APP_CLOUD_BUCKET \$APP_NGINX_PROXY_CACHE_DIRECTORY \$APP_NGINX_PROXY_CACHE_VALID_TIME \$APP_ENTRY_POINT \$APP_ROOT_DIRECTORY \$APP_ENV_NAME \$APP_SITEMAP_DIR' < "/etc/nginx/conf.d/site.conf.dist" > "/etc/nginx/conf.d/site.conf"
chown -R nginx:nginx /var/cache/nginx/proxy_cache
nginx -g 'daemon off;'
