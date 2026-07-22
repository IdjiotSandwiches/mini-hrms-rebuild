group "default" {
  targets = ["php-fpm", "scheduler", "web"]
}

variable "REGISTRY" { default = "" }
variable "IMAGE_OWNER" { default = "" }
variable "APP_NAME" { default = "Mini HRMS Rebuild" }

target "base" {
  dockerfile = "docker/Dockerfile"
  context    = "."
  args = {
    APP_NAME      = APP_NAME
    VITE_APP_NAME = APP_NAME
  }
}

target "php-fpm" {
  inherits = ["base"]
  target   = "php-fpm"
  tags     = ["${REGISTRY}/${IMAGE_OWNER}/mini-hrms-php:latest"]
}

target "scheduler" {
  inherits = ["base"]
  target   = "scheduler"
  tags     = ["${REGISTRY}/${IMAGE_OWNER}/mini-hrms-scheduler:latest"]
}

target "web" {
  inherits = ["base"]
  target   = "web"
  tags     = ["${REGISTRY}/${IMAGE_OWNER}/mini-hrms-web:latest"]
}
