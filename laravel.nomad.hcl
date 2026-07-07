job "gastos-app" {
  region      = "global"
  datacenters = ["dc1"]
  type        = "service"

  group "locales" {
    count = 1

    network {
      port "http" {
        to = 80
      }
    }

    service {
      name = "gastos"
      port = "http"
      tags = [
        "traefik.enable=true",
        "traefik.http.routers.gastos.rule=Host(`gastos.jcastellano.com.ar`)",
        "traefik.http.routers.gastos.entrypoints=https",
        "traefik.http.routers.gastos.tls=true",
        
        # Redirección HTTP→HTTPS
        "traefik.http.routers.gastos-http.rule=Host(`gastos.jcastellano.com.ar`)",
        "traefik.http.routers.gastos-http.entrypoints=http",
        "traefik.http.routers.gastos-http.middlewares=redirect-to-https",
        "traefik.http.middlewares.redirect-to-https.redirectscheme.scheme=https"
      ]
    }

    task "gastos" {
      driver = "docker"

     

      config {
        image = "sirhomer/php-arm:8.4"
        volumes = [
          "/mnt/usb/dmz/deploy/laravel-gastos/:/sitio"
        ]
        ports = ["http"]
      }
   # 🔐 INYECTAMOS LAS VARIABLES DIRECTAMENTE EN LA MEMORIA DEL CONTENEDOR
      template {
        destination = "/secret/.env"
        env         = true # <-- Clave: Hace que las líneas de abajo actúen como variables del OS

        data = <<EOH
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:aoGfeTEb1TUUkGRagm5OfohoPqT+EEcwc205laK/hyM=
APP_DEBUG="{{ with nomadVar "nomad/jobs/gastos-app" }}{{ .APP_DEBUG }}{{ end }}"
APP_URL=gastos.jcastellano.com.ar

APP_LOCALE=es
APP_FALLBACK_LOCALE=es
APP_FAKER_LOCALE=es_ES

APP_MAINTENANCE_DRIVER=file
# APP_MAINTENANCE_STORE=database

PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST="{{ with nomadVar "nomad/jobs/gastos-app" }}{{ .DB_HOST }}{{ end }}"
DB_PORT=3306
DB_DATABASE="{{ with nomadVar "nomad/jobs/gastos-app" }}{{ .DB_DATABASE }}{{ end }}"
DB_USERNAME="{{ with nomadVar "nomad/jobs/gastos-app" }}{{ .DB_USERNAME }}{{ end }}"
DB_PASSWORD="{{ with nomadVar "nomad/jobs/gastos-app" }}{{ .DB_PASSWORD }}{{ end }}"

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
# CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="192.168.1.4:5188"
APP_PORT=8585
VITE_PORT=5188
GOOGLE_CLIENT_ID="{{ with nomadVar "nomad/jobs/gastos-app" }}{{ .GOOGLE_CLIENT_ID }}{{ end }}"
GOOGLE_CLIENT_SECRET="{{ with nomadVar "nomad/jobs/gastos-app" }}{{ .GOOGLE_CLIENT_SECRET }}{{ end }}"
GOOGLE_REDIRECT_URI=https://gastos.jcastellano.com.ar/auth/google/callback
EOH
      }
     

      resources {
        cpu    = 500 
        memory = 128 
      }
    }
  }
}
