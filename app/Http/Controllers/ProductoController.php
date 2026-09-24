<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductoController extends Controller
{
    /**
     * Lista de categorías predeterminadas para productos tecnológicos.
     */
    private const CATEGORIAS = [
        'Laptops',
        'Smartphones',
        'Monitores',
        'Periféricos',
        'Audio',
        'Componentes',
        'Almacenamiento',
        'Redes',
        'Accesorios',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $buscar = $request->query('buscar');
        $categoria = $request->query('categoria');
        $orden = $request->query('orden', 'created_at');
        $direccion = $request->query('dir', 'desc');

        $columnasPermitidas = ['nombre', 'precio', 'stock', 'categoria', 'created_at'];
        if (! in_array($orden, $columnasPermitidas, true)) {
            $orden = 'created_at';
        }
        if (! in_array(strtolower($direccion), ['asc', 'desc'], true)) {
            $direccion = 'desc';
        }

        $query = Producto::query()
            ->buscar($buscar)
            ->categoria($categoria)
            ->orderBy($orden, $direccion);

        $productos = $query->paginate(8)->withQueryString();

        // Estadísticas para las tarjetas superiores
        $todos = Producto::all();
        $totalProductos = $todos->count();
        $valorInventario = $todos->reduce(function (float $carry, Producto $item): float {
            return $carry + (((float) $item->precio) * ((int) $item->stock));
        }, 0.0);
        $stockBajo = $todos->filter(function (Producto $item): bool {
            return (int) $item->stock < 5;
        })->count();
        $categoriasUnicas = $todos->pluck('categoria')->unique()->filter()->values();

        return view('productos.index', [
            'productos' => $productos,
            'totalProductos' => $totalProductos,
            'valorInventario' => $valorInventario,
            'stockBajo' => $stockBajo,
            'categorias' => self::CATEGORIAS,
            'categoriasExistentes' => $categoriasUnicas,
            'buscar' => $buscar,
            'categoriaSeleccionada' => $categoria,
            'orden' => $orden,
            'dir' => $direccion,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('productos.create', [
            'categorias' => self::CATEGORIAS,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:1500'],
            'precio' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'categoria' => ['required', 'string', 'max:100'],
            'estado' => ['nullable', 'boolean'],
            'attr_claves' => ['nullable', 'array'],
            'attr_claves.*' => ['nullable', 'string', 'max:100'],
            'attr_valores' => ['nullable', 'array'],
            'attr_valores.*' => ['nullable', 'string', 'max:255'],
        ]);

        $atributos = $this->procesarAtributosDinamicos(
            $request->input('attr_claves', []),
            $request->input('attr_valores', [])
        );

        Producto::create([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'] ?? null,
            'precio' => (float) $validated['precio'],
            'stock' => (int) $validated['stock'],
            'categoria' => $validated['categoria'],
            'estado' => $request->boolean('estado', true),
            'atributos' => $atributos,
        ]);

        return redirect()
            ->route('productos.index')
            ->with('exito', '¡Producto "'.$validated['nombre'].'" registrado con éxito en MongoDB!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $producto = Producto::findOrFail($id);

        return view('productos.show', [
            'producto' => $producto,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $producto = Producto::findOrFail($id);

        return view('productos.edit', [
            'producto' => $producto,
            'categorias' => self::CATEGORIAS,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $producto = Producto::findOrFail($id);

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:1500'],
            'precio' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'categoria' => ['required', 'string', 'max:100'],
            'estado' => ['nullable', 'boolean'],
            'attr_claves' => ['nullable', 'array'],
            'attr_claves.*' => ['nullable', 'string', 'max:100'],
            'attr_valores' => ['nullable', 'array'],
            'attr_valores.*' => ['nullable', 'string', 'max:255'],
        ]);

        $atributos = $this->procesarAtributosDinamicos(
            $request->input('attr_claves', []),
            $request->input('attr_valores', [])
        );

        $producto->update([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'] ?? null,
            'precio' => (float) $validated['precio'],
            'stock' => (int) $validated['stock'],
            'categoria' => $validated['categoria'],
            'estado' => $request->boolean('estado', true),
            'atributos' => $atributos,
        ]);

        return redirect()
            ->route('productos.index')
            ->with('exito', '¡Producto "'.$producto->nombre.'" actualizado con éxito en MongoDB!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $producto = Producto::findOrFail($id);
        $nombre = $producto->nombre;
        $producto->delete();

        return redirect()
            ->route('productos.index')
            ->with('exito', '¡Producto "'.$nombre.'" eliminado satisfactoriamente de MongoDB!');
    }

    /**
     * Convierte los pares clave-valor recibidos en un arreglo asociativo BSON para MongoDB.
     *
     * @param  array<int, string|null>  $claves
     * @param  array<int, string|null>  $valores
     * @return array<string, string>
     */
    private function procesarAtributosDinamicos(array $claves, array $valores): array
    {
        $atributos = [];

        foreach ($claves as $indice => $clave) {
            $claveLimpia = trim((string) $clave);
            $valorLimpio = trim((string) ($valores[$indice] ?? ''));

            if ($claveLimpia !== '' && $valorLimpio !== '') {
                $atributos[$claveLimpia] = $valorLimpio;
            }
        }

        return $atributos;
    }
}
