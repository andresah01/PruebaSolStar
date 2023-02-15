<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var string[]
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------

    public $cantante = [
        'nombre' => 'required|is_unique[cantantes.nombre]',
        'biografia' => 'required|min_length[10]',
        'fechaNacimiento' => 'required',
        'genero'    => 'required',
        'foto' => 'uploaded[foto]|is_image[foto]'
    ];

    public $cantante_errors = [
        'nombre' => [
            'is_unique' => 'Ya existe un cantante con ese nombre, por favor ingrese otro nombre.',
        ],
        'fechaNacimiento' => [
            'required' => 'El campo Fecha de Nacimiento es obligatorio.',
        ],
        'foto' => [
            'uploaded' => 'Debe subir una imagen al campo Foto.',
            'is_image' => 'El archivo cargado en el campo Foto no es una imagen, por favor seleccione una imagen.'
        ]
    ];

    public $cantanteActualizar = [
        'nombre' => 'required',
        'biografia' => 'required|min_length[10]',
        'fechaNacimiento' => 'required',
        'genero'    => 'required'
    ];

    public $imagen = [
        'foto' => 'is_image[foto]'
    ];

    public $imagen_errors = [
        'foto' => [
            'is_image' => 'El archivo cargado en el campo Foto no es una imagen, por favor seleccione una imagen.'
        ]
    ];
}
