# 🍾 LICORERAS - Sistema de Control de Inventario

Sistema completo de gestión de inventario, ventas, gastos y reportes para licoreras y negocios similares. Desarrollado con **Laravel 12**, **PHP 8.2+** y **MySQL**.

## ✨ Características Principales

### 📦 Gestión de Productos
- Registro completo de productos con código de barras
- Control de stock y alertas de stock bajo
- Categorización de productos
- Gestión de proveedores
- Generación e impresión de códigos de barras

### 🛒 Punto de Venta (POS)
- Interfaz rápida para ventas
- Escáner de código de barras integrado
- Múltiples métodos de pago (Efectivo, Tarjeta, Transferencia)
- Cálculo automático de cambio
- Generación de tickets y facturas

### 📊 Reportes y Estadísticas
- Ventas por período
- Productos más vendidos
- Inventario valorizado
- Reporte de gastos
- Utilidades y ganancias
- Clientes frecuentes

### 💰 Control de Gastos
- Registro de gastos por categoría
- Seguimiento de egresos
- Reportes de gastos

### 📦 Control de Inventario
- Entradas de inventario
- Salidas de inventario
- Ajustes de stock
- Kardex de productos
- Historial de movimientos

### 👥 Gestión de Usuarios
- Roles: Admin, Cajero, Almacén
- Control de permisos
- Perfiles de usuario

## 🚀 Requisitos del Sistema

- PHP >= 8.2
- MySQL >= 5.7 o MariaDB >= 10.3
- Composer >= 2.0
- Extensiones PHP: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, GD

## 📥 Instalación

### 1. Clonar o descargar el proyecto

```bash
cd /var/www/html
git clone https://github.com/tu-usuario/licoreras.git
cd licoreras
```

### 2. Instalar dependencias

```bash
composer install
```

### 3. Configurar el archivo de entorno

```bash
cp .env.example .env
```

Editar el archivo `.env` con tus configuraciones:

```env
APP_NAME="Licoreras - Sistema de Inventario"
APP_URL=http://localhost/licoreras

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=licoreras
DB_USERNAME=root
DB_PASSWORD=tu_password
```

### 4. Generar la clave de aplicación

```bash
php artisan key:generate
```

### 5. Crear la base de datos

```bash
mysql -u root -p
CREATE DATABASE licoreras CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### 6. Ejecutar migraciones y seeders

```bash
php artisan migrate
php artisan db:seed
```

### 7. Crear enlace simbólico para archivos

```bash
php artisan storage:link
```

### 8. Configurar el servidor web

#### Apache
Asegúrate de que el DocumentRoot apunte a la carpeta `public`:

```apache
DocumentRoot /var/www/html/licoreras/public
<Directory /var/www/html/licoreras/public>
    AllowOverride All
    Require all granted
</Directory>
```

Habilitar mod_rewrite:
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

#### Nginx
```nginx
server {
    listen 80;
    server_name tu-dominio.com;
    root /var/www/html/licoreras/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 9. Permisos de carpetas

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## 🎯 Uso del Sistema

### Acceso inicial

- **URL:** http://localhost/licoreras
- **Email:** admin@licoreras.com
- **Password:** password

> ⚠️ **IMPORTANTE:** Cambia la contraseña por defecto después del primer inicio de sesión.

### Configuración del Escáner de Código de Barras

1. Conecta tu lector de código de barras USB
2. El sistema detecta automáticamente la entrada del escáner
3. En el punto de venta, el cursor se enfoca automáticamente en el campo de código de barras
4. También puedes usar **Ctrl+B** para enfocar el campo manualmente

### Atajos de Teclado

| Atajo | Acción |
|-------|--------|
| `Ctrl + B` | Enfocar campo de código de barras |
| `Enter` | Buscar producto / Confirmar acción |
| `Esc` | Cerrar modales / Cancelar |

## 📁 Estructura del Proyecto

```
licoreras/
├── app/
│   ├── Http/
│   │   ├── Controllers/      # Controladores MVC
│   │   └── Middleware/       # Middleware personalizado
│   ├── Models/               # Modelos Eloquent
│   └── Providers/            # Service Providers
├── bootstrap/                # Bootstrap de la aplicación
├── config/                   # Archivos de configuración
├── database/
│   ├── migrations/           # Migraciones de base de datos
│   └── seeders/              # Seeders de datos
├── public/                   # Document root
│   ├── index.php
│   ├── css/
│   ├── js/
│   └── images/
├── resources/
│   └── views/                # Vistas Blade
├── routes/
│   └── web.php               # Rutas web
├── storage/                  # Logs, caché, archivos
└── vendor/                   # Dependencias Composer
```

## 🔧 Comandos Útiles

```bash
# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Crear backup de la base de datos
php artisan backup:run

# Ejecutar migraciones
php artisan migrate

# Rollback de migraciones
php artisan migrate:rollback

# Crear nuevo usuario administrador
php artisan tinker
>>> \App\Models\User::create(['name' => 'Admin', 'email' => 'nuevo@admin.com', 'password' => bcrypt('password'), 'role' => 'admin']);
```

## 🛡️ Seguridad

- Autenticación de usuarios con Laravel Sanctum
- Protección contra CSRF en todos los formularios
- Validación de datos en servidor
- Roles y permisos de usuarios
- Contraseñas hasheadas con Bcrypt

## 📱 Compatibilidad

- ✅ Chrome / Edge / Firefox / Safari
- ✅ Tablets y dispositivos móviles
- ✅ Escáneres de código de barras USB (modo teclado)
- ✅ Impresoras térmicas para tickets

## 📝 Licencia

Este proyecto es de código abierto bajo la licencia [MIT](LICENSE).

## 🤝 Soporte

Para reportar problemas o solicitar nuevas características, por favor crea un issue en el repositorio.

## 👨‍💻 Desarrollador

Desarrollado con ❤️ usando Laravel 12 + PHP + MySQL

---

**Versión:** 1.0.0  
**Última actualización:** Febrero 2025
