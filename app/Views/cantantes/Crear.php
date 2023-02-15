<? ?>
<main class="pt-5">

    <div class="container col-5">
        <a class="btn btn-secondary" href="<?= base_url('/') ?>" role="button">Regresar</a>
        <h1>Ingresar datos del nuevo cantante</h1>
        <form action="" id="formulario">
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?= old('nombre') ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Fecha de nacimiento</label>
                <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento" value="<?= old('fechaNacimiento') ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Biografia</label>
                <textarea class="form-control" rows="3" id="biografia" name="biografia" value="<?= old('biografia') ?>"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Genero</label>
                <input type="text" class="form-control" id="genero" name="genero" value="<?= old('genero') ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Foto</label>
                <input type="file" class="form-control" id="foto" name="foto">
            </div>
            <div class="mb-3" style="display: grid; place-items: center;">
                <button type="button" class="btn btn-success" onclick="nuevoCantante()">Ingresar</button>
            </div>
        </form>
    </div>
</main>
<script>
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


    const nuevoCantante = () => {
        Swal.fire({
            title: `¿Esta seguro que desea agregar este cantante?`,
            text: "",
            showDenyButton: true,
            icon: 'warning',
            confirmButtonText: 'Si, estoy seguro',
            denyButtonText: `No, no deseo agregar a este cantante`,
        }).then((result) => {
            result.isConfirmed ? guardarCantante() : Swal.fire('El cantante no ha sido agregadao', '', 'info')
        })
    }

    const guardarCantante = async () => {
        const formulario = document.querySelector('#formulario')
        const formularioCantante = new FormData(formulario)
        await axios.post('<?= base_url('/cantante/nuevo/guardar') ?>', formularioCantante)
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
                        title: 'Cantante registrado con exito.'
                    })
                }
            }).catch(error => {
                console.log(error)
            })

    }
</script>
<? ?>