<? ?>
<main class="pt-5">
    <div class="container">
        <a class="btn btn-success" href="<?= base_url('cantante/nuevo') ?>" role="button">Nuevo</a>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Fecha de nacimiento</th>
                    <th>Genero</th>
                    <th>Biografia</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="infoCantantes">
            </tbody>
        </table>
    </div>
    <div class="modal fade " id="infoModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"> Información </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formulario">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="habilitarEdicion">
                            <label class="form-check-label"> Habilitar edición </label>
                        </div>
                        <figure class="figure">
                            <img class="figure-img img-fluid rounded" id="imagen">
                        </figure>
                        <div class="mb-3">
                            <input type="hidden" class="form-control-plaintext" id="id" name="id">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control-plaintext" id="nombre" name="nombre">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fecha de nacimiento</label>
                            <input type="date" class="form-control-plaintext" id="fechaNacimiento" name="fechaNacimiento">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Biografia</label>
                            <textarea class="form-control-plaintext" rows="3" id="biografia" name="biografia"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Genero</label>
                            <input type="text" class="form-control-plaintext" id="genero" name="genero">
                        </div>
                        <div class="mb-3" id="campoFoto" style="display: none;">
                            <label class="form-label">Foto</label>
                            <input type="file" class="form-control" id="foto" name="foto">
                        </div>
                        <div class="mb-3" style="display: none;" id="campoBoton">
                            <button type="button" class="btn btn-success" onclick="confirmarActualizacion()">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    const campoFoto = document.getElementById('campoFoto')
    const campoBoton = document.getElementById('campoBoton')
    const campos = ['nombre', 'biografia', 'fechaNacimiento', 'genero']
    const table = document.getElementById('infoCantantes')

    const Toast = Swal.mixin({
        toast: true,
        position: 'bottom-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })

    document.addEventListener('DOMContentLoaded', () => {
        llenarTabla()
    })

    const llenarTabla = async () => {
        const table = document.getElementById('infoCantantes')
        await axios.get(`<?= base_url('/all') ?>`)
            .then(({
                data
            }) => {
                const cantantes = data.infoCantantes
                cantantes.map(cantante => {
                    const tr = document.createElement('tr')
                    tr.innerHTML = `<td>${cantante.nombre}</td>
                                    <td>${cantante.fechaNacimiento}</td>
                                    <td>${cantante.genero}</td>                            
                                    <td>${cantante.biografia}</td>
                                    <td><a role="button" onclick="infoCantante(${cantante.id})"><i class="bi bi-pencil-fill" style="color: green;"></i></a></td>
                                    <td><a role="button" onclick="confirmarBorrado(${cantante.id},'${cantante.nombre}')"><i class="bi bi-trash-fill" style="color: red;"></i></a></td>`
                    table.appendChild(tr);
                })
            }).catch(error => {})

    }

    const habilitarEdicion = document.getElementById('habilitarEdicion')
    habilitarEdicion.addEventListener('change', (e) => {
        if (e.srcElement.checked) {
            campos.map(campo => {
                habilitarCampo(campo)
            })
            campoFoto.style.display = 'block'
            campoBoton.style.display = 'grid'
            campoBoton.style.placeItems = 'center'

        } else {
            campos.map(campo => {
                deshabilitarCampo(campo)
            })
            campoFoto.style.display = 'none'
            campoBoton.style.display = 'none'
        }
    })

    const habilitarCampo = (id) => {
        let elemento = document.getElementById(id)
        elemento.removeAttribute('readonly', 'readonly')
        elemento.classList.remove('form-control-plaintext')
        elemento.classList.add('form-control')
    }

    const deshabilitarCampo = (id) => {
        let elemento = document.getElementById(id)
        elemento.setAttribute('readonly', 'readonly')
        elemento.classList.remove('form-control')
        elemento.classList.add('form-control-plaintext')
    }

    const mostrarModal = () => {
        const myModal = new bootstrap.Modal('#infoModal')
        myModal.show()
    }

    const infoCantante = async (id) => {
        await axios.get(`<?= base_url('/cantante/info/') ?>/${id}`)
            .then(({
                data
            }) => {
                let info = data.infoCantante
                document.getElementById('id').value = info.id
                document.getElementById('nombre').value = info.nombre
                document.getElementById('fechaNacimiento').value = info.fechaNacimiento
                document.getElementById('biografia').value = info.biografia
                document.getElementById('genero').value = info.genero
                document.getElementById('imagen').src = `<?= base_url('/uploads') ?>/${info.foto}`
                mostrarModal()
            }).catch(error => {
                Toast.fire({
                    icon: 'error',
                    title: 'Ha ocurrido un error durante la consulta.'
                })
            })
    }

    const confirmarActualizacion = () => {
        let nombre = document.getElementById('nombre').value
        Swal.fire({
            title: `¿Esta seguro que desea actualizar al cantante ${nombre}?`,
            text: "",
            showDenyButton: true,
            icon: 'warning',
            confirmButtonText: 'Si, estoy seguro',
            denyButtonText: `No, no deseo actualizarlo`,
        }).then((result) => {
            result.isConfirmed ? actualizarCantante() : Swal.fire(`Se ha cancelado el proceso de actualización.`, '', 'info')
        })
    }

    const actualizarCantante = async () => {
        const formulario = document.querySelector('#formulario')
        const formularioCantante = new FormData(formulario)
        console.log(formulario.foto.value)
        await axios.post(`<?= base_url('/cantante/actualizar') ?>/${formulario.id.value}`, formularioCantante)
            .then(response => {
                if (response.data.errors) {
                    let errores = response.data.errors
                    let mensajeError = '<ul class="list-group">'
                    for (let x in errores) {
                        mensajeError += '<li class="list-group-item">' + errores[x] + "</li>"
                    }
                    mensajeError += "</ul>"
                    Swal.fire({
                        title: `Ha ocurrido un problema durante el ingreso`,
                        html: mensajeError,
                        icon: 'error'
                    })
                } else {
                    Toast.fire({
                        icon: 'success',
                        title: 'Cantante actualizado con exito'
                    })
                    llenarTabla()
                    const tabla = document.getElementById('infoCantantes')
                    while (tabla.firstChild) {
                        tabla.removeChild(tabla.firstChild)
                    }
                }
            }).catch(error => {
                console.log(error)
            })
    }


    const confirmarBorrado = (id, nombre) => {
        Swal.fire({
            title: `¿Esta seguro que desea eliminar a el/la cantante ${nombre}? `,
            text: "",
            showDenyButton: true,
            icon: 'warning',
            confirmButtonText: 'Si, estoy seguro',
            denyButtonText: `No, no deseo eliminarlo/a`,
        }).then((result) => {
            result.isConfirmed ? borrarCantante(id) : Swal.fire(`${nombre} no ha sido eliminado`, '', 'info')
        })
    }

    const borrarCantante = async (id) => {
        await axios.get(`<?= base_url('/cantante/borrar/') ?>/${id}`)
            .then(response => {
                Toast.fire({
                    icon: 'info',
                    title: 'Cantante borrado con exito.'
                })
            }).catch(error => {
                Toast.fire({
                    icon: 'error',
                    title: 'Ha ocurrido un error en el borrado.'
                })
            })
    }
</script>
<? ?>