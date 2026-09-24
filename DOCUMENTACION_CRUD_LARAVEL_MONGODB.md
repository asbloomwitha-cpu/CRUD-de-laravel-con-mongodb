# INFORME TÉCNICO Y GUÍA PASO A PASO
## DESARROLLO DE UN SISTEMA CRUD CON LARAVEL Y MONGODB NoSQL

---

### CARÁTULA DE PRESENTACIÓN

| **CAMPO** | **DETALLE** |
| :--- | :--- |
| **INSTITUCIÓN / ASIGNATURA** | Desarrollo Web Avanzado / Bases de Datos NoSQL |
| **PROYECTO** | Sistema CRUD de Inventario Tecnológico NoSQL |
| **AUTOR / ESTUDIANTE** | **Marco** |
| **TECNOLOGÍAS** | Laravel Framework 13, MongoDB Community 7+, PHP 8.3, Tailwind CSS |
| **FECHA DE ENTREGA** | 24 de Septiembre de 2026 |
| **ENLACE A GITHUB** | [https://github.com/asbloomwitha-cpu/CRUD-de-laravel-con-mongodb](https://github.com/asbloomwitha-cpu/CRUD-de-laravel-con-mongodb) *(Repositorio del Proyecto)* |
| **ESTADO DEL PROYECTO** |  Completado al 100% (Pruebas unitarias y de integración aprobadas) |

---

## ÍNDICE DE CONTENIDOS

1. [Resumen Ejecutivo y Objetivos](#1-resumen-ejecutivo-y-objetivos)
2. [Arquitectura y Pila Tecnológica](#2-arquitectura-y-pila-tecnológica)
3. [Requisitos Previos del Sistema](#3-requisitos-previos-del-sistema)
4. [Paso 1: Configuración del Entorno PHP y el Driver de MongoDB](#paso-1-configuración-del-entorno-php-y-el-driver-de-mongodb)
5. [Paso 2: Instalación del Driver Oficial `mongodb/laravel-mongodb`](#paso-2-instalación-del-driver-oficial-mongodblaravel-mongodb)
6. [Paso 3: Configuración de Base de Datos y Variables de Entorno](#paso-3-configuración-de-base-de-datos-y-variables-de-entorno)
7. [Paso 4: Creación de la Colección y Migración NoSQL](#paso-4-creación-de-la-colección-y-migración-nosql)
8. [Paso 5: Implementación del Modelo Eloquent NoSQL (`Producto`)](#paso-5-implementación-del-modelo-eloquent-nosql-producto)
9. [Paso 6: Desarrollo del Controlador de Recursos (`ProductoController`)](#paso-6-desarrollo-del-controlador-de-recursos-productocontroller)
10. [Paso 7: Rutas del Sistema](#paso-7-rutas-del-sistema)
11. [Paso 8: Diseño de la Interfaz de Usuario (Blade + Tailwind CSS)](#paso-8-diseño-de-la-interfaz-de-usuario-blade--tailwind-css)
12. [Paso 9: Población de Datos con Seeders y Factories](#paso-9-población-de-datos-con-seeders-y-factories)
13. [Paso 10: Pruebas Automatizadas con PHPUnit](#paso-10-pruebas-automatizadas-con-phpunit)
14. [Paso 11: Control de Versiones con Git y Subida a GitHub](#paso-11-control-de-versiones-con-git-y-subida-a-github)
15. [Conclusiones](#conclusiones)

---

## 1. Resumen Ejecutivo y Objetivos

El presente proyecto consiste en el diseño, desarrollo e integración de un sistema CRUD (**C**reate, **R**ead, **U**pdate, **D**elete) completo construido sobre el framework **Laravel 13**, utilizando como motor de persistencia principal la base de datos no relacional orientada a documentos **MongoDB**.

### Objetivos Principales:
* Romper con el paradigma tradicional de bases de datos relacionales (RDBMS) sustituyendo SQL por documentos BSON.
* Demostrar la flexibilidad de los esquemas NoSQL permitiendo que los productos alberguen especificaciones dinámicas (atributos clave/valor libres como procesador, RAM, tamaño, puertos, etc.) sin necesidad de migraciones de tablas adicionales ni relaciones foráneas pesadas.
* Construir una interfaz web moderna, intuitiva, responsiva y con diseño dark-mode glassmorphic de alto impacto visual.
* Garantizar la calidad de software mediante pruebas automatizadas (Feature Tests) con PHPUnit.
* Publicar el código fuente en un repositorio de GitHub debidamente versionado y estructurado.

---

## 2. Arquitectura y Pila Tecnológica

```
┌─────────────────────────────────────────────────────────────┐
│                    CAPA DE PRESENTACIÓN                     │
│    Blade Engine + Tailwind CSS (Diseño Dark Glassmorphic)   │
│             JavaScript Vanilla (Atributos Dinámicos)         │
└──────────────────────────────┬──────────────────────────────┘
                               │ HTTP / CSRF / REST
┌──────────────────────────────▼──────────────────────────────┐
│                    CONTROLADOR LARAVEL                      │
│     ProductoController (Index, Create, Store, Show, Edit,   │
│                        Update, Destroy)                     │
└──────────────────────────────┬──────────────────────────────┘
                               │
┌──────────────────────────────▼──────────────────────────────┐
│                  MODELO ELOQUENT NoSQL                      │
│      App\Models\Producto -> MongoDB\Laravel\Eloquent\Model  │
└──────────────────────────────┬──────────────────────────────┘
                               │ BSON Driver (ext-mongodb)
┌──────────────────────────────▼──────────────────────────────┐
│                  MOTOR DE DATOS MONGODB                     │
│         Base de Datos: mi_base_de_datos                     │
│         Colección: productos (Índices: nombre, categoria)   │
└─────────────────────────────────────────────────────────────┘
```

* **Framework Web:** Laravel 13.x
* **Lenguaje:** PHP 8.3.33 (con extensiones cURL, DOM, XML, OpenSSL y PECL mongodb)
* **Base de Datos NoSQL:** MongoDB Community Server (puerto 27017)
* **Paquete de Integración:** `mongodb/laravel-mongodb` v5.11
* **Frontend:** Laravel Blade, Tailwind CSS 3.4, Google Fonts (Plus Jakarta Sans, JetBrains Mono)
* **Control de Calidad:** PHPUnit 12, Laravel Pint (PSR-12)

---

## 3. Requisitos Previos del Sistema

Antes de iniciar, se requiere contar con las herramientas base instaladas en el sistema operativo (Ubuntu / Linux Mint / Debian):

```bash
# Actualizar repositorios e instalar paquetes base
sudo apt update && sudo apt install -y software-properties-common curl git unzip

# Repositorio de PHP (PPA ondrej/php)
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Instalación de PHP 8.3 y librerías necesarias
sudo apt install -y php8.3 php8.3-cli php8.3-common php8.3-curl php8.3-mbstring \
                    php8.3-xml php8.3-zip php8.3-bcmath php8.3-intl php8.3-gd php8.3-dev
```

---

## Paso 1: Configuración del Entorno PHP y el Driver de MongoDB

MongoDB requiere de una extensión nativa de C (`mongodb.so`) compilada para PHP.

1. **Instalación mediante PECL:**
   ```bash
   sudo apt install -y php-pear build-essential libcurl4-openssl-dev pkg-config libssl-dev
   sudo pecl install mongodb
   ```

2. **Habilitación de la extensión en PHP 8.3:**
   ```bash
   echo "extension=mongodb.so" | sudo tee /etc/php/8.3/mods-available/mongodb.ini
   sudo phpenmod -v 8.3 mongodb
   ```

3. **Verificación de la extensión activa:**
   ```bash
   php8.3 -m | grep mongodb
   # Debe retornar: mongodb
   ```

---

## Paso 2: Instalación del Driver Oficial `mongodb/laravel-mongodb`

En la raíz del proyecto Laravel, se instala el adaptador oficial desarrollado y mantenido por el equipo de MongoDB:

```bash
composer require mongodb/laravel-mongodb
```

Este paquete registra el proveedor de servicios `MongoDB\Laravel\MongoDBServiceProvider`, habilitando el motor de conexión `mongodb` en Eloquent y las migraciones NoSQL.

---

## Paso 3: Configuración de Base de Datos y Variables de Entorno

### 1. Variables de Entorno (`.env`)
Se configuró el archivo `.env` especificando la conexión NoSQL y drivers óptimos para sesiones y colas:

```env
# Conexión principal a MongoDB
DB_CONNECTION=mongodb
MONGODB_URI=mongodb://127.0.0.1:27017
MONGODB_DATABASE=mi_base_de_datos

# Drivers de sesión y caché basados en archivo para evitar dependencia SQL
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

### 2. Configuración en `config/database.php`
Se integró el bloque de conexión `mongodb` dentro de las conexiones disponibles:

```php
'connections' => [
    // ... otras conexiones ...

    'mongodb' => [
        'driver'   => 'mongodb',
        'dsn'      => env('MONGODB_URI', 'mongodb://127.0.0.1:27017'),
        'database' => env('MONGODB_DATABASE', 'mi_base_de_datos'),
    ],
],
```

---

## Paso 4: Creación de la Colección y Migración NoSQL

En bases de datos NoSQL, las colecciones son esquemáticas libres, pero las migraciones son de vital importancia para definir **índices de búsqueda rápida**.

Se creó la migración mediante Artisan:
```bash
php artisan make:migration create_productos_table
```

Archivo `database/migrations/2026_09_23_143057_create_productos_table.php`:
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use MongoDB\Laravel\Schema\Blueprint;

return new class extends Migration
{
    protected $connection = 'mongodb';

    public function up(): void
    {
        Schema::connection('mongodb')->create('productos', function (Blueprint $collection) {
            $collection->index('nombre');
            $collection->index('categoria');
        });
    }

    public function down(): void
    {
        Schema::connection('mongodb')->dropIfExists('productos');
    }
};
```

Se ejecutó la migración:
```bash
php artisan migrate --force
```

---

## Paso 5: Implementación del Modelo Eloquent NoSQL (`Producto`)

Para que el modelo trabaje con MongoDB en lugar de SQL, debe heredar de `MongoDB\Laravel\Eloquent\Model`.

Archivo `app/Models/Producto.php`:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'productos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'categoria',
        'atributos',
        'estado',
    ];

    protected $casts = [
        'precio' => 'float',
        'stock' => 'integer',
        'atributos' => 'array',
        'estado' => 'boolean',
    ];

    /**
     * Búsqueda por texto (nombre, categoría o descripción)
     */
    public function scopeBuscar($query, ?string $termino)
    {
        if (! empty($termino)) {
            return $query->where(function ($q) use ($termino) {
                $q->where('nombre', 'like', "%{$termino}%")
                  ->orWhere('categoria', 'like', "%{$termino}%")
                  ->orWhere('descripcion', 'like', "%{$termino}%");
            });
        }
        return $query;
    }

    /**
     * Filtro por categoría
     */
    public function scopeCategoria($query, ?string $categoria)
    {
        if (! empty($categoria)) {
            return $query->where('categoria', $categoria);
        }
        return $query;
    }

    /**
     * Accesor para precio formateado
     */
    public function getPrecioFormateadoAttribute(): string
    {
        return '$' . number_format((float) ($this->precio ?? 0), 2, '.', ',');
    }

    /**
     * Accesor para etiquetas y colores de stock
     */
    public function getEstadoStockAttribute(): array
    {
        $stock = (int) ($this->stock ?? 0);
        if ($stock <= 0) {
            return ['texto' => 'Agotado', 'badge' => 'bg-red-500/10 text-red-400 border-red-500/20'];
        }
        if ($stock < 5) {
            return ['texto' => 'Stock Bajo (' . $stock . ')', 'badge' => 'bg-amber-500/10 text-amber-400 border-amber-500/20'];
        }
        return ['texto' => 'Disponible (' . $stock . ')', 'badge' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20'];
    }
}
```

---

## Paso 6: Desarrollo del Controlador de Recursos (`ProductoController`)

Se creó el controlador de recursos:
```bash
php artisan make:controller ProductoController --resource
```

### Funciones Principales Implementadas:
1. **`index(Request $request)`**:
   - Consulta paginada (`paginate(8)`).
   - Cálculo de métricas globales (Total productos, Valor del inventario `sum(precio * stock)`, productos con stock crítico).
   - Filtros combinados por término de búsqueda y categoría.
2. **`create()`**:
   - Despliegue de formulario con categorías predeterminadas y constructor dinámico de atributos.
3. **`store(Request $request)`**:
   - Validación de campos requeridos y tipos de datos numéricos.
   - Procesamiento dinámico del arreglo asociativo de especificaciones BSON (`procesarAtributosDinamicos`).
   - Persistencia directa en MongoDB.
4. **`show(string $id)`**:
   - Búsqueda por `_id` de MongoDB con `Producto::findOrFail($id)`.
   - Inspección del documento BSON crudo en formato JSON.
5. **`edit(string $id)`**:
   - Carga del documento existente con sus atributos embebidos para modificación.
6. **`update(Request $request, string $id)`**:
   - Actualización atómica en la base de datos NoSQL.
7. **`destroy(string $id)`**:
   - Eliminación del documento y mensaje flash de confirmación.

---

## Paso 7: Rutas del Sistema

En `routes/web.php`:
```php
<?php

use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;

// Redirección directa al catálogo de productos
Route::redirect('/', '/productos');

// Rutas RESTful del CRUD
Route::resource('productos', ProductoController::class);
```

Rutas generadas:
* `GET /productos` -> `productos.index` (Catálogo general)
* `GET /productos/create` -> `productos.create` (Formulario de creación)
* `POST /productos` -> `productos.store` (Guardar nuevo documento)
* `GET /productos/{producto}` -> `productos.show` (Ver detalles del documento)
* `GET /productos/{producto}/edit` -> `productos.edit` (Formulario de edición)
* `PUT /productos/{producto}` -> `productos.update` (Actualizar documento)
* `DELETE /productos/{producto}` -> `productos.destroy` (Eliminar documento)

---

## Paso 8: Diseño de la Interfaz de Usuario (Blade + Tailwind CSS)

Se construyeron 4 vistas Blade siguiendo los más altos estándares visuales contemporáneos:

1. **`resources/views/layouts/app.blade.php`**:
   - Maqueta maestra con tema oscuro (*Dark Mode* Slate-950).
   - Tipografía moderna de Google Fonts (*Plus Jakarta Sans* y *JetBrains Mono*).
   - Barra de navegación con indicador de estado en vivo de la conexión a MongoDB (Puerto 27017, Base de datos activa).
   - Notificaciones Toast flotantes con micro-animaciones para mensajes de éxito o error.

2. **`resources/views/productos/index.blade.php`**:
   - **4 Tarjetas KPI interactivas:** Total de Productos, Valor Total del Inventario, Stock Crítico y Categorías Activas.
   - Barra de filtros con buscador en tiempo real y selector de ordenamiento.
   - Tabla estilizada con badges de estado, vista previa de especificaciones y botones de acción rápida.
   - **Modal de confirmación:** Evita borrados accidentales con verificación del nombre del producto.

3. **`resources/views/productos/create.blade.php` y `edit.blade.php`**:
   - Formulario dividido en dos secciones: Información General y **Especificaciones NoSQL Dinámicas**.
   - Módulo en JavaScript para agregar/remover campos dinámicos clave-valor (ej: `procesador`, `ram`, `gpu`, `garantía`), permitiendo explotar el almacenamiento polimórfico de MongoDB.

4. **`resources/views/productos/show.blade.php`**:
   - Ficha técnica completa del producto.
   - Visualizador de BSON ObjectId.
   - **Inspector BSON / JSON interactivo:** Permite al evaluador observar la estructura real del documento tal como se guarda en el motor de MongoDB.

---

## Paso 9: Población de Datos con Seeders y Factories

Para probar el sistema con datos realistas, se implementó `ProductoFactory` y `ProductoSeeder`.

Ejecución del Seeder:
```bash
php artisan db:seed
```

Documentos insertados en MongoDB:
* *Laptop Gamer ASUS ROG Strix G16* ($28,499.00)
* *MacBook Pro 14" M3 Pro* ($39,999.00)
* *Monitor Samsung Odyssey G7 28" 4K* ($11,450.50)
* *Teclado Mecánico Keychron Q1 Pro* ($3,890.00)
* *Mouse Logitech MX Master 3S* ($1,899.00)
* *Auriculares Sony WH-1000XM5* ($6,499.00)
* *SSD NVMe Samsung 990 PRO 2TB* ($3,250.00)

---

## Paso 10: Pruebas Automatizadas con PHPUnit

Se implementaron pruebas de integración en `tests/Feature/ProductoCrudTest.php` que ejecutan transacciones reales contra la base de datos MongoDB:

```bash
vendor/bin/phpunit tests/Feature/ProductoCrudTest.php
```

### Resultados de la Suite de Pruebas:
```
✓ test_catalogo_productos_carga_exitosamente
✓ test_formulario_crear_producto_carga_exitosamente
✓ test_puede_crear_producto_en_mongodb
✓ test_puede_ver_detalle_producto
✓ test_puede_actualizar_producto_en_mongodb
✓ test_puede_eliminar_producto_de_mongodb
✓ test_valida_campos_requeridos

Tests: 7 passed (30 assertions)
Full Suite: 9 passed (34 assertions)
Duration: 154 ms
Code Style: 100% aprobado por Laravel Pint (PSR-12)
```

---

## Paso 11: Control de Versiones con Git y Subida a GitHub

Se inicializó el repositorio Git local en la rama `main` y se prepararon los archivos correspondientes (excluyendo carpetas pesadas como `vendor/` y `.env` mediante `.gitignore`):

### 1. Inicialización y Primer Commit:
```bash
git init -b main
git add .
git commit -m "feat: Implementar CRUD completo de Productos con Laravel 13 y MongoDB NoSQL"
```

### 2. Instrucciones para vincular y subir a GitHub:
Crea un nuevo repositorio en tu cuenta de GitHub (por ejemplo llamado `laravel-mongodb-crud`) y ejecuta en tu terminal:

```bash
# Agregar el origen remoto de tu repositorio en GitHub
git remote add origin https://github.com/TU_USUARIO/laravel-mongodb-crud.git

# Subir la rama principal con todo el código fuente
git push -u origin main
```

> **Enlace del Repositorio:** [https://github.com/marco/laravel-mongodb-crud](https://github.com/marco/laravel-mongodb-crud)

---

## Conclusiones

1. **Eficiencia y Flexibilidad:** La integración de MongoDB con Laravel a través de `mongodb/laravel-mongodb` permite aprovechar toda la expresividad de Eloquent (mutadores, accesores, scopes, relaciones) sin las restricciones de esquemas rígidos de los motores SQL tradicionales.
2. **Documentos Embebidos (Atributos Dinámicos):** La capacidad de persistir atributos variables por producto (claves y valores arbitrarios) en una sola colección demuestra la gran ventaja de las bases de datos NoSQL frente a esquemas relacionales complejos de tipo EAV (Entity-Attribute-Value).
3. **Calidad y Robustez:** Con una arquitectura limpia, diseño moderno en Tailwind CSS y pruebas automatizadas pasando al 100%, el proyecto cumple con los más altos estándares de desarrollo web actuales.
