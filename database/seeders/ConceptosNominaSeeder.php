<?php

namespace Database\Seeders;

use App\Models\ConceptoNomina;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConceptosNominaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ConceptoNomina::create(['tipo' => 'percepcion', 'nombre' => 'comidas']);
        ConceptoNomina::create(['tipo' => 'percepcion', 'nombre' => 'compensación']);
        ConceptoNomina::create(['tipo' => 'percepcion', 'nombre' => 'apoyo pasajes y estímulos']);
        ConceptoNomina::create(['tipo' => 'deduccion', 'nombre' => 'anticipo']);
        ConceptoNomina::create(['tipo' => 'deduccion', 'nombre' => 'por pagar']);
    }
}
