<div class="container">
    <div class="row g-4">
        <!-- Formulario de Compromisos -->
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
                " method="POST" action="/crear-compromiso">
                    <h2 class="text-lg font-semibold mb-4">Crear Compromiso</h2>

                    <div class="mb-3">
                        <label for="acta_id" class="form-label">ID Acta:</label>
                        <input type="number" id="acta_id" name="acta_id" class="form-control" value="<?php echo isset($form_data['acta_id']) ? s($form_data['acta_id']) : ''; ?>">
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción:</label>
                        <input type="text" id="descripcion" name="descripcion" class="form-control" value="<?php echo isset($form_data['descripcion']) ? s($form_data['descripcion']) : ''; ?>">
                    </div>

                    <div class="mb-3">
                        <label for="responsable_id" class="form-label">ID Responsable:</label>
                        <input type="number" id="responsable_id" name="responsable_id" class="form-control" value="<?php echo isset($form_data['responsable_id']) ? s($form_data['responsable_id']) : ''; ?>">
                    </div>

                    <div class="mb-3">
                        <label for="fecha_entrega" class="form-label">Fecha Entrega:</label>
                        <input type="date" id="fecha_entrega" name="fecha_entrega" class="form-control" value="<?php echo isset($form_data['fecha_entrega']) ? s($form_data['fecha_entrega']) : ''; ?>">
                    </div>


                    <div class="d-grid mb-2">
                        <input type="submit" value="Crear Compromiso" class="btn btn-success">
                    </div>
                    <div class="d-grid">
                        <button id="actualizarCompromiso" class="btn btn-info">Actualizar Compromiso</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tablas -->
        <div class="col-md-6 pt-4">

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

            <!-- Tabla Asistentes por Reunión-->
            <div class="table-responsive mt-2" style="max-height: 600px;">
            <h1 class="text-white">Tabla Asistentes por Reunión</h1>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ID Reunión</th>
                            <th>ID Usuario</th>
                        </tr>
                    </thead>
                    <tbody id="asistentes-tbody">
                        <!-- Los datos de las asistentes se insertarán aquí -->
                    </tbody>
                </table>
            </div>

            <!-- Tabla de Compromisos por usuario-->
            <div class="table-responsive mt-2" style="max-height: 600px;">
                <h1 class="text-white">Tabla de Compromisos</h1>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ID Acta</th>
                            <th>Descripcion</th>
                            <th>ID Responsable</th>
                            <th>Fecha Entrega</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody id="compromisos-tbody">
                        <!-- Los datos de los compromisos se insertarán aquí -->
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>


<script>
    async function fetchAndRenderData() {
        try {
            const [asistentesResponse, actasResponse, compromisosResponse] = await Promise.all([
                fetch('/obtenerasistentes', {
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
                fetch('/obtenercompromisos', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                }),
            ]);

            if (asistentesResponse.ok && actasResponse.ok && compromisosResponse) {
                const [asistentes, actas, compromisos] = await Promise.all([
                    asistentesResponse.json(),
                    actasResponse.json(),
                    compromisosResponse.json()
                ]);

                const actasTbody = document.getElementById('actas-tbody');
                actasTbody.innerHTML = '';
                actas.forEach(acta => {
                    const row = document.createElement('tr');

                    const idCell = document.createElement('td');
                    idCell.textContent = acta.id;
                    row.appendChild(idCell);

                    const idReunionCell = document.createElement('td');
                    idReunionCell.textContent = acta.reunion_id;
                    row.appendChild(idReunionCell);

                    const contenidoCell = document.createElement('td');
                    contenidoCell.textContent = acta.contenido;
                    row.appendChild(contenidoCell);

                   
                    actasTbody.appendChild(row);
                });

                const asistentesTbody = document.getElementById('asistentes-tbody');
                asistentesTbody.innerHTML = '';
                asistentes.forEach(asistente => {
                    const row = document.createElement('tr');

                    const idCell = document.createElement('td');
                    idCell.textContent = asistente.id;
                    row.appendChild(idCell);

                    const idreunonCcell = document.createElement('td');
                    idreunonCcell.textContent = asistente.reunion_id;
                    row.appendChild(idreunonCcell);

                    const idusuarioCell = document.createElement('td');
                    idusuarioCell.textContent = asistente.usuario_id;
                    row.appendChild(idusuarioCell);

                    asistentesTbody.appendChild(row);
                });

                const compromisosTbody = document.getElementById('compromisos-tbody');
                compromisosTbody.innerHTML = '';
                compromisos.forEach(compromiso => {
                    const row = document.createElement('tr');

                    const idCell = document.createElement('td');
                    idCell.textContent = compromiso.id;
                    row.appendChild(idCell);

                    const idactaCell = document.createElement('td');
                    idactaCell.textContent = compromiso.acta_id;
                    row.appendChild(idactaCell);

                    const descripcionCell = document.createElement('td');
                    descripcionCell.textContent = compromiso.descripcion;
                    row.appendChild(descripcionCell);

                    const responsableCell = document.createElement('td');
                    responsableCell.textContent = compromiso.responsable_id;
                    row.appendChild(responsableCell);

                    const fechaCell = document.createElement('td');
                    fechaCell.textContent = compromiso.fecha_entrega;
                    row.appendChild(fechaCell);

                    const estadoCell = document.createElement('td');
                    estadoCell.textContent = compromiso.estado;
                    row.appendChild(estadoCell);


                    compromisosTbody.appendChild(row);
                });
            } else {
                if (!asistentesResponse.ok) {
                    console.error('Error fetching asistentes:', asistentesResponse.statusText);
                }
                if (!actasResponse.ok) {
                    console.error('Error fetching actas:', actasResponse.statusText);
                }
                if (!compromisosResponse.ok) {
                    console.error('Error fetching compromisos:', compromisosResponse.statusText);
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

    document.getElementById('actualizarCompromiso').addEventListener('click', async function(event) {
        event.preventDefault();

        const {
            value: searchQuery
        } = await Swal.fire({
            title: 'Buscar Compromiso',
            input: 'text',
            inputPlaceholder: 'Ingrese la ID del compromiso...',
            showCancelButton: true,
            inputValidator: (value) => {
                if (!value) {
                    return 'Por favor ingrese una ID';
                }
            }
        });

        if (searchQuery) {
            try {
                const response = await fetch('/obtenercompromisos', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                const compromisos = await response.json();
                const compromiso = compromisos.find(comp => comp.id.toString() === searchQuery);

                if (compromiso) {
                    const tableContent = `
                <div class="max-w-md mx-auto bg-white shadow-md rounded my-6 dark:bg-gray-800 d-flex justify-content-center">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-blue-100 dark:bg-blue-900 border border-blue-500 dark:border-blue-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">ID</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${compromiso.id}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">ID Acta</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${compromiso.acta_id}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">Descripción</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${compromiso.descripcion}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">Responsable</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${compromiso.responsable_id}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">Fecha Entrega</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${compromiso.fecha_entrega}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">Estado</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${compromiso.estado}</td>
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
                                title: 'Modificar Compromiso',
                                html: `
                            <input id="swal-id" class="swal2-input" placeholder="ID" value="${compromiso.id}" readonly>
                            <input type="number" id="swal-acta_id" class="swal2-input" placeholder="Acta_id" value="${compromiso.acta_id}">
                            <input type="text" id="swal-descripcion" class="swal2-input" placeholder="Descripcion" value="${compromiso.descripcion}">
                            <input type="number" id="swal-responsable_id" class="swal2-input" placeholder="Responsable_id" value="${compromiso.responsable_id}">
                            <input type="date" id="swal-fecha_entrega" class="swal2-input" placeholder="Fecha_entrega" value="${compromiso.fecha_entrega}">
                             <input id="swal-estado" class="swal2-input" placeholder="Estado" value="${compromiso.estado}" readonly>`,
                                focusConfirm: false,
                                preConfirm: () => {
                                    return {
                                        id: document.getElementById('swal-id').value,
                                        acta_id: document.getElementById('swal-acta_id').value,
                                        descripcion: document.getElementById('swal-descripcion').value,
                                        responsable_id: document.getElementById('swal-responsable_id').value,
                                        fecha_entrega: document.getElementById('swal-fecha_entrega').value,
                                        estado: document.getElementById('swal-estado').value,

                                    };
                                },
                                showCancelButton: true,
                                confirmButtonText: 'Guardar Cambios',
                                cancelButtonText: 'Cancelar',
                                showLoaderOnConfirm: true,
                                allowOutsideClick: () => !Swal.isLoading(),
                            }).then(async (result) => {
                                if (result.isConfirmed) {
                                    const updatedCompromiso = result.value;
                                    try {
                                        const response = await fetch('/actualizarcompromiso', {
                                            method: 'PUT',
                                            headers: {
                                                'Content-Type': 'application/json'
                                            },
                                            body: JSON.stringify(updatedCompromiso)
                                        });

                                        const data = await response.json();

                                        if (data.success) {
                                            showToast(data.success, 'success');
                                        } else if (data.error) {
                                            showToast(data.error, 'error');
                                        }
                                    } catch (error) {
                                        showToast('Error al actualizar el compromiso', 'error');
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
                    text: "Hubo un problema al buscar el compromiso.",
                    icon: "error"
                });
            }
        }
    });

    <?php if (!empty($message)) : ?>
        showToast("<?php echo $message; ?>", "<?php echo (empty($alertas['error'])) ? 'success' : 'error'; ?>");
    <?php endif; ?>
</script>