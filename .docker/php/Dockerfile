FROM yiisoftware/yii2-php:8.5-apache-latest

ENV YII_ENV=prod \
    YII_DEBUG=0

# Install Supervisor
RUN apt-get update && apt-get install -y \
    supervisor \
    && rm -rf /var/lib/apt/lists/*

# Copy konfigurasi supervisor ke dalam container
COPY .docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set working directory
WORKDIR /app

# Jalankan supervisord sebagai ENTRYPOINT / CMD utama
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
