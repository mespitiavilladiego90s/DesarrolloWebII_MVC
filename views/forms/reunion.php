<div class="container">
    <div class="row g-4">
        <!-- Formulario de Reuniones -->
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
                " method="POST" action="/crear-reunion">
                    <h2 class="text-lg font-semibold mb-4">Crear Reunión</h2>

                    <input type="hidden" id="id_usuario" name="id_usuario" class="form-control" value="<?php echo isset($_SESSION['id']) ? s($_SESSION['id']) : ''; ?>">

                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha:</label>
                        <input type="date" id="fecha" name="fecha" class="form-control" value="<?php echo isset($form_data['fecha']) ? s($form_data['fecha']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="hora_inicio" class="form-label">Hora de Inicio:</label>
                        <input type="time" id="hora_inicio" name="hora_inicio" class="form-control" value="<?php echo isset($form_data['hora_inicio']) ? s($form_data['hora_inicio']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="hora_fin" class="form-label">Hora de Fin:</label>
                        <input type="time" id="hora_fin" name="hora_fin" class="form-control" value="<?php echo isset($form_data['hora_fin']) ? s($form_data['hora_fin']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="lugar" class="form-label">Lugar:</label>
                        <input type="text" id="lugar" name="lugar" class="form-control" value="<?php echo isset($form_data['lugar']) ? s($form_data['lugar']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="asunto" class="form-label">Asunto:</label>
                        <input type="text" id="asunto" name="asunto" class="form-control" value="<?php echo isset($form_data['asunto']) ? s($form_data['asunto']) : ''; ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado:</label>
                        <select id="estado" name="estado" class="form-control">
                            <option value="" disabled <?php echo !isset($form_data['estado']) ? 'selected' : ''; ?>>Seleccione el estado</option>
                            <option value="pública" <?php echo isset($form_data['estado']) && $form_data['estado'] == 'pública' ? 'selected' : ''; ?>>Pública</option>
                            <option value="privada" <?php echo isset($form_data['estado']) && $form_data['estado'] == 'privada' ? 'selected' : ''; ?>>Privada</option>
                        </select>
                    </div>

                    <div class="d-grid mb-2">
                        <input type="submit" value="Crear Reunión" class="btn btn-success">
                    </div>
                    <div class="d-grid">
                        <button id="actualizarReunion" class="btn btn-info">Actualizar Reunión</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tablas -->
        <div class="col-md-6 pt-4">

            <!-- Tabla usuarios -->
            <div class="table-responsive" style="max-height: 600px;">
            <h1 class="text-white">Tabla de Usuarios</h1>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Email</th>
                            <th>Telefono</th>
                            <th>Rol</th>
                        </tr>
                    </thead>
                    <tbody id="personas-tbody">
                        <!-- Los datos de las personas se insertarán aquí -->
                    </tbody>
                </table>
            </div>

            <!-- Tabla Reuniones-->
            <div class="table-responsive mt-2" style="max-height: 600px;">
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

        </div>
    </div>
</div>


<script>
    async function fetchAndRenderData() {
        try {
            const [personasResponse, reunionesResponse] = await Promise.all([
                fetch('/obtenerpersonas', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                }),
                fetch('/obtenerreuniones', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
            ]);

            if (personasResponse.ok && reunionesResponse.ok) {
                const [personas, reuniones] = await Promise.all([
                    personasResponse.json(),
                    reunionesResponse.json()
                ]);

                // Limpiar los cuerpos de las tablas antes de renderizar nuevos datos
                const personasTbody = document.getElementById('personas-tbody');
                personasTbody.innerHTML = '';
                personas.forEach(persona => {
                    const row = document.createElement('tr');

                    const idCell = document.createElement('td');
                    idCell.textContent = persona.id;
                    row.appendChild(idCell);

                    const nombreCell = document.createElement('td');
                    nombreCell.textContent = persona.nombre;
                    row.appendChild(nombreCell);

                    const apellidoCell = document.createElement('td');
                    apellidoCell.textContent = persona.apellido;
                    row.appendChild(apellidoCell);

                    const emailCell = document.createElement('td');
                    emailCell.textContent = persona.email;
                    row.appendChild(emailCell);

                    const telefonoCell = document.createElement('td');
                    telefonoCell.textContent = persona.telefono;
                    row.appendChild(telefonoCell);

                    const rolCell = document.createElement('td');
                    rolCell.textContent = persona.rol;
                    row.appendChild(rolCell);

                    personasTbody.appendChild(row);
                });

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
            } else {
                if (!personasResponse.ok) {
                    console.error('Error fetching personas:', personasResponse.statusText);
                }
                if (!reunionesResponse.ok) {
                    console.error('Error fetching reuniones:', reunionesResponse.statusText);
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

    function setupSelect(selectId, currentState) {
        const selectElement = document.getElementById(selectId);
        selectElement.innerHTML = '';

        const states = ['pública', 'privada'];

        states.forEach(state => {
            const option = document.createElement('option');
            option.value = state;
            option.textContent = state;
            if (currentState === state) {
                option.selected = true;
            }
            selectElement.appendChild(option);
        });
    }

    // Llamar a la función setupSelect para el campo 'estado'
    setupSelect('estado', '<?php echo isset($form_data['estado']) ? s($form_data['estado']) : ''; ?>');

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

    document.getElementById('actualizarReunion').addEventListener('click', async function(event) {
        event.preventDefault();

        const {
            value: searchQuery
        } = await Swal.fire({
            title: 'Buscar Reunión',
            input: 'text',
            inputPlaceholder: 'Ingrese la ID de la reunión...',
            showCancelButton: true,
            inputValidator: (value) => {
                if (!value) {
                    return 'Por favor ingrese una ID';
                }
            }
        });

        if (searchQuery) {
            try {
                const response = await fetch('/obtenerreuniones', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                const reuniones = await response.json();
                const reunion = reuniones.find(re => re.id.toString() === searchQuery);

                if (reunion) {
                    const tableContent = `
                <div class="max-w-md mx-auto bg-white shadow-md rounded my-6 dark:bg-gray-800 d-flex justify-content-center">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-blue-100 dark:bg-blue-900 border border-blue-500 dark:border-blue-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">ID</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${reunion.id}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">ID Usuario</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${reunion.id_usuario}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">Fecha</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${reunion.fecha}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">Hora Inicio</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${reunion.hora_inicio}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">Hora Fin</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${reunion.hora_fin}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">Lugar</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${reunion.lugar}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">Asunto</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${reunion.asunto}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">Estado</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${reunion.estado}</td>
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
                                title: 'Modificar Reunión',
                                html: `
                            <input id="swal-id" class="swal2-input" placeholder="ID" value="${reunion.id}" readonly>
                            <input type="text" id="swal-id_usuario" class="swal2-input" placeholder="ID_usuario" value="${reunion.id_usuario}" readonly>
                            <input type="date" id="swal-fecha" class="swal2-input" placeholder="Fecha" value="${reunion.fecha}">
                            <input type="time" id="swal-hora_inicio" class="swal2-input" value="${reunion.hora_inicio}">
                            <input type="time" id="swal-hora_fin" class="swal2-input" value="${reunion.hora_fin}">
                            <input id="swal-lugar" class="swal2-input" placeholder="Lugar" value="${reunion.lugar}">
                            <input id="swal-asunto" class="swal2-input" placeholder="Asunto" value="${reunion.asunto}">
                            <select id="swal-estado" class="swal2-control mt-2">
                            </select>`,
                                focusConfirm: false,
                                didOpen: () => {
                                    setupSelect('swal-estado', reunion.estado);
                                },
                                preConfirm: () => {
                                    return {
                                        id: document.getElementById('swal-id').value,
                                        id_usuario: document.getElementById('swal-id_usuario').value,
                                        fecha: document.getElementById('swal-fecha').value,
                                        hora_inicio: document.getElementById('swal-hora_inicio').value,
                                        hora_fin: document.getElementById('swal-hora_fin').value,
                                        lugar: document.getElementById('swal-lugar').value,
                                        asunto: document.getElementById('swal-asunto').value,
                                        estado: document.getElementById('swal-estado').value

                                    };
                                },
                                showCancelButton: true,
                                confirmButtonText: 'Guardar Cambios',
                                cancelButtonText: 'Cancelar',
                                showLoaderOnConfirm: true,
                                allowOutsideClick: () => !Swal.isLoading(),
                            }).then(async (result) => {
                                if (result.isConfirmed) {
                                    const updatedReunion = result.value;
                                    try {
                                        const response = await fetch('/actualizarreunion', {
                                            method: 'PUT',
                                            headers: {
                                                'Content-Type': 'application/json'
                                            },
                                            body: JSON.stringify(updatedReunion)
                                        });

                                        const data = await response.json();

                                        if (data.success) {
                                            showToast(data.success, 'success');
                                        } else if (data.error) {
                                            showToast(data.error, 'error');
                                        }
                                    } catch (error) {
                                        showToast('Error al actualizar la reunión', 'error');
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
                    text: "Hubo un problema al buscar la reunión.",
                    icon: "error"
                });
            }
        }
    });

    <?php if (!empty($message)) : ?>
        showToast("<?php echo $message; ?>", "<?php echo (empty($alertas['error'])) ? 'success' : 'error'; ?>");
    <?php endif; ?>
</script>