<div class="container mt-0">
    <div class="row g-4">
        <!-- Formulario de Acta -->
        <div class="col-md-6">
            <div class="scale-down">
                <form class="bg-light p-4 rounded-lg m-3 bg-primary text-white" style="
                    background: linear-gradient(
                        to right,
                        #4c24ee,
                        #4624ee,
                        #245aee,
                        #24a7ee
                    );
                " method="POST" action="/crear-acta">
                    <h2 class="text-lg font-semibold mb-4">Crear Acta de reunión</h2>

                    <div class="mb-3">
                        <label for="reunion_id" class="form-label">ID Reunión:</label>
                        <input type="number" id="reunion_id" name="reunion_id" class="form-control" value="<?php echo isset($form_data['reunion_id']) ? s($form_data['reunion_id']) : ''; ?>">
                    </div>

                    <div class="mb-3">
                        <label for="contenido" class="form-label">Contenido:</label>
                        <input type="text" id="contenido" name="contenido" class="form-control" value="<?php echo isset($form_data['contenido']) ? s($form_data['contenido']) : ''; ?>">
                    </div>


                    <div class="d-grid mb-2">
                        <input type="submit" value="Crear Acta" class="btn btn-success">
                    </div>
                    <div class="d-grid">
                        <button id="actualizarActa" class="btn btn-info">Actualizar Acta</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tablas -->
        <div class="col-md-6 pt-4">

            <!-- Tabla Reuniones-->
            <div class="table-responsive" style="max-height: 600px;">
            <h1 class="text-white">Tabla de Reuniones</h1>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ID Usuario</th>
                            <th>Fecha</th>
                            <th>Hora Inicio</th>
                            <th>Hora Fin</th>
                            <th>Lugar</th>
                            <th>Asunto</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody id="reuniones-tbody">
                        <!-- Los datos de las reuniones se insertarán aquí -->
                    </tbody>
                </table>
            </div>

            <!-- Tabla Actas por Reunión-->
            <div class="table-responsive mt-2" style="max-height: 600px;">
            <h1 class="text-white">Actas por Reunión</h1>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ID Reunión</th>
                            <th>Contenido</th>
                        </tr>
                    </thead>
                    <tbody id="actas-tbody">
                        <!-- Los datos de las actas se insertarán aquí -->
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>


<script>
    async function fetchAndRenderData() {
        try {
            const [reunionesResponse, actasResponse] = await Promise.all([
                fetch('/obtenerreuniones', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                }),
                fetch('/obteneractas', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                }),
            ]);

            if (reunionesResponse.ok && actasResponse) {
                const [reuniones, actas] = await Promise.all([
                    reunionesResponse.json(),
                    actasResponse.json()
                ]);
                const reunionesTbody = document.getElementById('reuniones-tbody');
                reunionesTbody.innerHTML = '';
                reuniones.forEach(reunion => {
                    const row = document.createElement('tr');

                    const idCell = document.createElement('td');
                    idCell.textContent = reunion.id;
                    row.appendChild(idCell);

                    const idUsuarioCell = document.createElement('td');
                    idUsuarioCell.textContent = reunion.id_usuario;
                    row.appendChild(idUsuarioCell);

                    const fechaCell = document.createElement('td');
                    fechaCell.textContent = reunion.fecha;
                    row.appendChild(fechaCell);

                    const horaInicioCell = document.createElement('td');
                    horaInicioCell.textContent = reunion.hora_inicio;
                    row.appendChild(horaInicioCell);

                    const horaFinCell = document.createElement('td');
                    horaFinCell.textContent = reunion.hora_fin;
                    row.appendChild(horaFinCell);

                    const lugarCell = document.createElement('td');
                    lugarCell.textContent = reunion.lugar;
                    row.appendChild(lugarCell);

                    const asuntoCell = document.createElement('td');
                    asuntoCell.textContent = reunion.asunto;
                    row.appendChild(asuntoCell);

                    const estadoCell = document.createElement('td');
                    estadoCell.textContent = reunion.estado;
                    row.appendChild(estadoCell);

                    reunionesTbody.appendChild(row);
                });
                const actasTbody = document.getElementById('actas-tbody');
                actasTbody.innerHTML = '';
                actas.forEach(acta => {
                    const row = document.createElement('tr');

                    const idCell = document.createElement('td');
                    idCell.textContent = acta.id;
                    row.appendChild(idCell);

                    const reunionCell = document.createElement('td');
                    reunionCell.textContent = acta.reunion_id;
                    row.appendChild(reunionCell);

                    const contenidoCell = document.createElement('td');
                    contenidoCell.textContent = acta.contenido;
                    row.appendChild(contenidoCell);

                    actasTbody.appendChild(row);
                });
            } else {
                if (!reunionesResponse.ok) {
                    console.error('Error fetching reuniones:', reunionesResponse.statusText);
                }
                if (!actasResponse.ok) {
                    console.error('Error fetching actas:', actasResponse.statusText);
                }
            }
        } catch (error) {
            console.error('Error during fetch operations:', error);
        }
    }

    // Llamar a la función fetchAndRenderData cuando el DOM esté cargado
    document.addEventListener('DOMContentLoaded', fetchAndRenderData);

    // Configurar un intervalo para actualizar los datos cada 1 segundos
    setInterval(fetchAndRenderData, 1000);

    function showToast(message, icon) {
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
        Toast.fire({
            icon: icon,
            title: message
        });
    }

    document.getElementById('actualizarActa').addEventListener('click', async function(event) {
        event.preventDefault();

        const {
            value: searchQuery
        } = await Swal.fire({
            title: 'Buscar Acta',
            input: 'text',
            inputPlaceholder: 'Ingrese la ID del acta...',
            showCancelButton: true,
            inputValidator: (value) => {
                if (!value) {
                    return 'Por favor ingrese una ID';
                }
            }
        });

        if (searchQuery) {
            try {
                const response = await fetch('/obteneractas', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                const actas = await response.json();
                const acta = actas.find(ac => ac.id.toString() === searchQuery);

                if (acta) {
                    const tableContent = `
                <div class="max-w-md mx-auto bg-white shadow-md rounded my-6 dark:bg-gray-800 d-flex justify-content-center">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-blue-100 dark:bg-blue-900 border border-blue-500 dark:border-blue-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">ID</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${acta.id}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">ID Reunión</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${acta.reunion_id}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">Contenido</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${acta.contenido}</td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                `;

                    Swal.fire({
                        title: 'Coincidencia Encontrada',
                        html: tableContent,
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Sí, deseo modificar!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Modificar Acta',
                                html: `
                            <input id="swal-id" class="swal2-input" placeholder="ID" value="${acta.id}" readonly>
                            <input type="number" id="swal-reunion_id" class="swal2-input" placeholder="Reunion_id" value="${acta.reunion_id}">
                            <input type="text" id="swal-contenido" class="swal2-input" placeholder="Contenido" value="${acta.contenido}">`,
                                focusConfirm: false,
                                preConfirm: () => {
                                    return {
                                        id: document.getElementById('swal-id').value,
                                        reunion_id: document.getElementById('swal-reunion_id').value,
                                        contenido: document.getElementById('swal-contenido').value
                                    };
                                },
                                showCancelButton: true,
                                confirmButtonText: 'Guardar Cambios',
                                cancelButtonText: 'Cancelar',
                                showLoaderOnConfirm: true,
                                allowOutsideClick: () => !Swal.isLoading(),
                            }).then(async (result) => {
                                if (result.isConfirmed) {
                                    const updatedActa = result.value;
                                    try {
                                        const response = await fetch('/actualizaracta', {
                                            method: 'PUT',
                                            headers: {
                                                'Content-Type': 'application/json'
                                            },
                                            body: JSON.stringify(updatedActa)
                                        });

                                        const data = await response.json();

                                        if (data.success) {
                                            showToast(data.success, 'success');
                                        } else if (data.error) {
                                            showToast(data.error, 'error');
                                        }
                                    } catch (error) {
                                        showToast('Error al actualizar el acta', 'error');
                                    }
                                }
                            });
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'No se encontró coincidencia',
                        text: 'Intente con otra ID',
                        icon: 'error'
                    });
                }
            } catch (error) {
                Swal.fire({
                    title: "Error!",
                    text: "Hubo un problema al buscar el acta.",
                    icon: "error"
                });
            }
        }
    });

    <?php if (!empty($message)) : ?>
        showToast("<?php echo $message; ?>", "<?php echo (empty($alertas['error'])) ? 'success' : 'error'; ?>");
    <?php endif; ?>
</script>