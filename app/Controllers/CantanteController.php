<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Cantante;


class CantanteController extends BaseController
{

    protected $request;
    protected $response;


    public function index()
    {
        $data['titulo'] = "Inicio";
        return view('template/Main', $data) . view('cantantes/Listar', $data) . view('template/Footer');
    }

    public function getAll()
    {
        $cantantes = new Cantante();
        $response['infoCantantes'] = $cantantes->findAll();
        return $this->response->setJSON($response);
    }

    public function create()
    {

        $data['titulo'] = "Crear nuevo cantante";
        return view('template/Main', $data) . view('cantantes/Crear', $data) . view('template/Footer');
    }

    public function store()
    {
        $validation =  \Config\Services::validation();
        $data = [
            'nombre' => $this->request->getVar('nombre'),
            'fechaNacimiento' => $this->request->getVar('fechaNacimiento'),
            'biografia' => $this->request->getVar('biografia'),
            'foto' => $this->request->getFile('foto'),
            'genero' => $this->request->getVar('genero')
        ];
        if ($validation->run($data, 'cantante')) {
            $cantante = new Cantante();
            $nombreFoto = $data['foto']->getRandomName();
            $data['foto']->move('../public/uploads/', $nombreFoto);
            $data['foto'] = $nombreFoto;
            $cantante->insert($data);
        } else {
            $response['errors'] = $validation->getErrors();
            return $this->response->setJSON($response);
        }
    }

    public function delete($id)
    {
        $cantante = new Cantante();
        $infoCantante = $cantante->where('id', $id)->first();
        $imagen = ('../public/uploads/' . $infoCantante['foto']);
        unlink($imagen);
        $cantante->where('id', $id)->delete();
        $response['success'] = 'Cantante eliminado con exito.';
        return $this->response->setJSON($response);
    }

    public function info($id)
    {
        $cantante = new Cantante();
        $infoCantante = $cantante->where('id', $id)->first();
        $data['infoCantante'] = $infoCantante;
        return $this->response->setJSON($data);
    }

    public function update($id)
    {
        $validation =  \Config\Services::validation();
        $data = [
            'nombre' => $this->request->getVar('nombre'),
            'fechaNacimiento' => $this->request->getVar('fechaNacimiento'),
            'biografia' => $this->request->getVar('biografia'),
            'foto' => $this->request->getFile('foto'),
            'genero' => $this->request->getVar('genero')
        ];
        if ($validation->run($data, 'cantanteActualizar')) {
            $cantante = new Cantante();
            $infoCantante = $cantante->where('id', $id)->first();
            if (strpos($data['foto']->getRandomName(), '.')) {
                if (!$validation->run($data, 'imagen')) {
                    $response['errors'] = $validation->getErrors();
                    return $this->response->setJSON($response);
                }
                $ruta = ('../public/uploads/' . $infoCantante['foto']);
                unlink($ruta);
                $nuevoNombre = $data['foto']->getRandomName();
                $data['foto']->move('../public/uploads/', $nuevoNombre);
                $data['foto'] = $nuevoNombre;
            } else {
                $data['foto'] = $infoCantante['foto'];
            }
            $cantante->update($id, $data);
        } else {
            $response['errors'] = $validation->getErrors();
            return $this->response->setJSON($response);
        }
    }
}
