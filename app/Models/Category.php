<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Útil para crear datos de prueba (factories)
use Illuminate\Database\Eloquent\Model;

// Este modelo representa la tabla 'categories' en tu base de datos.
class Category extends Model
{
    use HasFactory;

    // Los campos 'name' y 'description' pueden ser asignados masivamente.
    protected $fillable = [
        'name',
        'description'
    ];

    // Por defecto, Laravel maneja automáticamente 'created_at' y 'updated_at'.
    // No necesitas definir 'protected $casts' si solo tienes los timestamps básicos.
    public function products(){
        return $this->hasMany(Product::class);
    }

}
