<?php

namespace App\Models;

use CodeIgniter\Model;

class Cantante extends Model
{
    protected $table = 'cantantes';

    protected $primaryKey = 'id';

    protected $allowedFields = ['nombre', 'fechaNacimiento', 'biografia', 'foto', 'genero'];
}
