#!/bin/bash

echo "=================================="
echo "  INSTALADOR - LICORERAS SYSTEM"
echo "=================================="
echo ""

# Colores
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Verificar PHP
if ! command -v php &> /dev/null; then
    echo -e "${RED}Error: PHP no está instalado${NC}"
    exit 1
fi

PHP_VERSION=$(php -v | head -n 1 | cut -d " " -f 2 | cut -d "." -f 1,2)
echo -e "${GREEN}✓ PHP versión: $PHP_VERSION${NC}"

# Verificar Composer
if ! command -v composer &> /dev/null; then
    echo -e "${RED}Error: Composer no está instalado${NC}"
    echo "Instala Composer desde: https://getcomposer.org/download/"
    exit 1
fi

echo -e "${GREEN}✓ Composer instalado${NC}"

# Verificar MySQL
if ! command -v mysql &> /dev/null; then
    echo -e "${YELLOW}⚠ MySQL no está instalado o no está en el PATH${NC}"
fi

echo ""
echo "=================================="
echo "  Instalando dependencias..."
echo "=================================="
echo ""

# Instalar dependencias de Composer
composer install --no-dev --optimize-autoloader

if [ $? -ne 0 ]; then
    echo -e "${RED}Error al instalar dependencias${NC}"
    exit 1
fi

echo -e "${GREEN}✓ Dependencias instaladas${NC}"

# Copiar archivo de entorno
if [ ! -f .env ]; then
    cp .env.example .env
    echo -e "${GREEN}✓ Archivo .env creado${NC}"
fi

# Generar clave de aplicación
php artisan key:generate

echo ""
echo "=================================="
echo "  Configuración de Base de Datos"
echo "=================================="
echo ""

echo -e "${YELLOW}Por favor, configura tu base de datos en el archivo .env${NC}"
echo ""
echo "DB_CONNECTION=mysql"
echo "DB_HOST=127.0.0.1"
echo "DB_PORT=3306"
echo "DB_DATABASE=licoreras"
echo "DB_USERNAME=root"
echo "DB_PASSWORD=tu_password"
echo ""

read -p "¿Has configurado la base de datos en el archivo .env? (s/n): " db_configured

if [ "$db_configured" = "s" ] || [ "$db_configured" = "S" ]; then
    echo ""
    echo "=================================="
    echo "  Ejecutando migraciones..."
    echo "=================================="
    echo ""
    
    php artisan migrate --force
    
    if [ $? -ne 0 ]; then
        echo -e "${RED}Error al ejecutar migraciones${NC}"
        echo "Verifica tu configuración de base de datos"
        exit 1
    fi
    
    echo -e "${GREEN}✓ Migraciones ejecutadas${NC}"
    
    echo ""
    echo "=================================="
    echo "  Creando datos iniciales..."
    echo "=================================="
    echo ""
    
    php artisan db:seed --force
    
    echo -e "${GREEN}✓ Datos iniciales creados${NC}"
fi

# Crear enlaces simbólicos
echo ""
echo "=================================="
echo "  Creando enlaces simbólicos..."
echo "=================================="
echo ""

php artisan storage:link

echo -e "${GREEN}✓ Enlaces simbólicos creados${NC}"

# Optimizar
echo ""
echo "=================================="
echo "  Optimizando aplicación..."
echo "=================================="
echo ""

php artisan config:cache
php artisan route:cache
php artisan view:cache

echo -e "${GREEN}✓ Aplicación optimizada${NC}"

echo ""
echo "=================================="
echo "  INSTALACIÓN COMPLETADA"
echo "=================================="
echo ""
echo -e "${GREEN}✓ Licoreras System instalado correctamente${NC}"
echo ""
echo "Credenciales de acceso:"
echo "  Email: admin@licoreras.com"
echo "  Password: password"
echo ""
echo "Recuerda cambiar la contraseña después del primer inicio de sesión."
echo ""
echo "Para iniciar el servidor de desarrollo:"
echo "  php artisan serve"
echo ""
