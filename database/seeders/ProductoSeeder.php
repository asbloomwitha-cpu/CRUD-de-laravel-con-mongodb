<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos = [
            [
                'nombre' => 'Laptop Gamer ASUS ROG Strix G16',
                'descripcion' => 'Potente laptop para gaming y desarrollo de alto rendimiento con gráficos RTX 4070.',
                'precio' => 28499.00,
                'stock' => 8,
                'categoria' => 'Laptops',
                'estado' => true,
                'atributos' => [
                    'procesador' => 'Intel Core i9-13980HX',
                    'ram' => '32 GB DDR5',
                    'almacenamiento' => '1 TB NVMe SSD',
                    'pantalla' => '16" QHD+ 240Hz',
                    'gpu' => 'NVIDIA GeForce RTX 4070 8GB',
                ],
            ],
            [
                'nombre' => 'MacBook Pro 14" M3 Pro',
                'descripcion' => 'Diseñada para desarrolladores y creadores exigentes. Batería de hasta 18 horas.',
                'precio' => 39999.00,
                'stock' => 4,
                'categoria' => 'Laptops',
                'estado' => true,
                'atributos' => [
                    'chip' => 'Apple M3 Pro (CPU 12 núcleos, GPU 18 núcleos)',
                    'memoria_unificada' => '18 GB',
                    'almacenamiento' => '512 GB SSD',
                    'pantalla' => 'Liquid Retina XDR 14.2"',
                    'color' => 'Negro Espacial',
                ],
            ],
            [
                'nombre' => 'Monitor Samsung Odyssey G7 28" 4K 144Hz',
                'descripcion' => 'Monitor gaming IPS UHD con tiempo de respuesta de 1ms y compatibilidad G-Sync.',
                'precio' => 11450.50,
                'stock' => 12,
                'categoria' => 'Monitores',
                'estado' => true,
                'atributos' => [
                    'tamano' => '28 pulgadas',
                    'resolucion' => '3840 x 2160 (4K UHD)',
                    'tasa_refresco' => '144 Hz',
                    'puertos' => '2x HDMI 2.1, 1x DisplayPort 1.4',
                    'hdr' => 'HDR400',
                ],
            ],
            [
                'nombre' => 'Teclado Mecánico Keychron Q1 Pro Wireless',
                'descripcion' => 'Teclado inalámbrico custom de aluminio CNC con switches mecánicos lubricados y montaje gasket.',
                'precio' => 3890.00,
                'stock' => 15,
                'categoria' => 'Periféricos',
                'estado' => true,
                'atributos' => [
                    'layout' => '75% ANSI',
                    'switches' => 'Keychron K Pro Red (Lineales)',
                    'conectividad' => 'Bluetooth 5.1 / Cable USB-C',
                    'rgb' => 'RGB programable con QMK/VIA',
                ],
            ],
            [
                'nombre' => 'Mouse Logitech MX Master 3S',
                'descripcion' => 'El ratón de productividad definitivo con scroll electromagnético MagSpeed y clics silenciosos.',
                'precio' => 1899.00,
                'stock' => 20,
                'categoria' => 'Periféricos',
                'estado' => true,
                'atributos' => [
                    'sensor' => 'Darkfield 8000 DPI (funciona sobre cristal)',
                    'bateria' => 'Hasta 70 días de duración',
                    'conectividad' => 'Logi Bolt / Bluetooth',
                    'peso' => '141 g',
                ],
            ],
            [
                'nombre' => 'Auriculares Sony WH-1000XM5',
                'descripcion' => 'Cancelación de ruido líder en la industria con audio de alta resolución y 30 horas de autonomía.',
                'precio' => 6499.00,
                'stock' => 2,
                'categoria' => 'Audio',
                'estado' => true,
                'atributos' => [
                    'driver' => '30 mm con cúpula de fibra de carbono',
                    'cancelacion_ruido' => 'Procesador V1 + QN1 integrado',
                    'codecs' => 'LDAC, AAC, SBC',
                    'microfonos' => '8 micrófonos con tecnología de formación de haz',
                ],
            ],
            [
                'nombre' => 'SSD NVMe Samsung 990 PRO 2TB',
                'descripcion' => 'Unidad de estado sólido PCIe 4.0 de máxima velocidad para cargas de trabajo extremas.',
                'precio' => 3250.00,
                'stock' => 0,
                'categoria' => 'Componentes',
                'estado' => false,
                'atributos' => [
                    'interfaz' => 'PCIe Gen 4.0 x4, NVMe 2.0',
                    'lectura_secuencial' => 'Hasta 7,450 MB/s',
                    'escritura_secuencial' => 'Hasta 6,900 MB/s',
                    'factor_forma' => 'M.2 2280',
                ],
            ],
        ];

        foreach ($productos as $data) {
            // Usamos updateOrCreate para evitar duplicados si se corre varias veces
            Producto::updateOrCreate(
                ['nombre' => $data['nombre']],
                $data
            );
        }
    }
}
