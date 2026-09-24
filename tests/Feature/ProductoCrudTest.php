<?php

namespace Tests\Feature;

use App\Models\Producto;
use Tests\TestCase;

class ProductoCrudTest extends TestCase
{
    /**
     * Verifica que la página de listado (index) carga correctamente con status 200.
     */
    public function test_catalogo_productos_carga_exitosamente(): void
    {
        $response = $this->get(route('productos.index'));

        $response->assertStatus(200);
        $response->assertSee('Catálogo de Productos');
    }

    /**
     * Verifica que la vista de formulario para crear producto carga con status 200.
     */
    public function test_formulario_crear_producto_carga_exitosamente(): void
    {
        $response = $this->get(route('productos.create'));

        $response->assertStatus(200);
        $response->assertSee('Registrar Nuevo Producto');
    }

    /**
     * Verifica que se puede almacenar un producto con atributos dinámicos en MongoDB.
     */
    public function test_puede_crear_producto_en_mongodb(): void
    {
        $datos = [
            'nombre' => 'Laptop Prueba Unit Test '.uniqid(),
            'descripcion' => 'Descripción de prueba automatizada',
            'precio' => 19999.50,
            'stock' => 10,
            'categoria' => 'Laptops',
            'estado' => '1',
            'attr_claves' => ['procesador', 'ram'],
            'attr_valores' => ['Intel Core i7', '32GB'],
        ];

        $response = $this->post(route('productos.store'), $datos);

        $response->assertRedirect(route('productos.index'));
        $response->assertSessionHas('exito');

        // Verificar que el documento existe en MongoDB
        $producto = Producto::where('nombre', $datos['nombre'])->first();
        $this->assertNotNull($producto);
        $this->assertEquals(19999.50, $producto->precio);
        $this->assertEquals(10, $producto->stock);
        $this->assertEquals('Intel Core i7', $producto->atributos['procesador'] ?? null);
    }

    /**
     * Verifica que la vista de detalle (show) muestra la información del producto.
     */
    public function test_puede_ver_detalle_producto(): void
    {
        $producto = Producto::create([
            'nombre' => 'Monitor Test '.uniqid(),
            'descripcion' => 'Monitor de prueba',
            'precio' => 4500.00,
            'stock' => 7,
            'categoria' => 'Monitores',
            'estado' => true,
            'atributos' => ['tamano' => '27 pulgadas'],
        ]);

        $response = $this->get(route('productos.show', $producto->id));

        $response->assertStatus(200);
        $response->assertSee($producto->nombre);
        $response->assertSee('27 pulgadas');
    }

    /**
     * Verifica que se puede actualizar un producto existente en MongoDB.
     */
    public function test_puede_actualizar_producto_en_mongodb(): void
    {
        $producto = Producto::create([
            'nombre' => 'Teclado Original '.uniqid(),
            'descripcion' => 'Teclado original',
            'precio' => 800.00,
            'stock' => 5,
            'categoria' => 'Periféricos',
            'estado' => true,
            'atributos' => ['switches' => 'Azules'],
        ]);

        $datosActualizados = [
            'nombre' => 'Teclado Editado '.uniqid(),
            'descripcion' => 'Nueva descripción editada',
            'precio' => 1250.00,
            'stock' => 15,
            'categoria' => 'Periféricos',
            'estado' => '1',
            'attr_claves' => ['switches', 'rgb'],
            'attr_valores' => ['Rojos Silenciosos', 'Sí'],
        ];

        $response = $this->put(route('productos.update', $producto->id), $datosActualizados);

        $response->assertRedirect(route('productos.index'));
        $response->assertSessionHas('exito');

        $producto->refresh();
        $this->assertEquals($datosActualizados['nombre'], $producto->nombre);
        $this->assertEquals(1250.00, $producto->precio);
        $this->assertEquals(15, $producto->stock);
        $this->assertEquals('Rojos Silenciosos', $producto->atributos['switches'] ?? null);
    }

    /**
     * Verifica que se puede eliminar un producto de MongoDB.
     */
    public function test_puede_eliminar_producto_de_mongodb(): void
    {
        $producto = Producto::create([
            'nombre' => 'Para Eliminar '.uniqid(),
            'precio' => 100.00,
            'stock' => 1,
            'categoria' => 'Accesorios',
        ]);

        $id = $producto->id;

        $response = $this->delete(route('productos.destroy', $id));

        $response->assertRedirect(route('productos.index'));
        $response->assertSessionHas('exito');

        $this->assertNull(Producto::find($id));
    }

    /**
     * Verifica validación de campos obligatorios al crear producto.
     */
    public function test_valida_campos_requeridos(): void
    {
        $response = $this->post(route('productos.store'), [
            'nombre' => '',
            'precio' => 'invalido',
            'stock' => -5,
        ]);

        $response->assertSessionHasErrors(['nombre', 'precio', 'stock', 'categoria']);
    }
}
