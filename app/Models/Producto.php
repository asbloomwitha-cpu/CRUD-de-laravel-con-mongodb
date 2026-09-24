<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';

    protected $collection = 'productos';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'categoria',
        'atributos',
        'estado',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'precio' => 'float',
        'stock' => 'integer',
        'atributos' => 'array',
        'estado' => 'boolean',
    ];

    /**
     * Scope a query to search products by name or description.
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
     * Scope a query to filter products by category.
     */
    public function scopeCategoria($query, ?string $categoria)
    {
        if (! empty($categoria)) {
            return $query->where('categoria', $categoria);
        }

        return $query;
    }

    /**
     * Accessor for formatted price.
     */
    public function getPrecioFormateadoAttribute(): string
    {
        return '$'.number_format((float) ($this->precio ?? 0), 2, '.', ',');
    }

    /**
     * Accessor for stock label and badge styling.
     */
    public function getEstadoStockAttribute(): array
    {
        $stock = (int) ($this->stock ?? 0);

        if ($stock <= 0) {
            return [
                'texto' => 'Agotado',
                'color' => 'red',
                'badge' => 'bg-red-500/10 text-red-400 border-red-500/20',
            ];
        }

        if ($stock < 5) {
            return [
                'texto' => 'Stock Bajo ('.$stock.')',
                'color' => 'amber',
                'badge' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
            ];
        }

        return [
            'texto' => 'Disponible ('.$stock.')',
            'color' => 'emerald',
            'badge' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
        ];
    }
}
