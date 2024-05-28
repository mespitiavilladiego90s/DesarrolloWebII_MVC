<div class="container">
    <div class="row g-4 d-flex justify-content-center">
        <!-- Formulario de Reunión -->
        <div class="col-md-6">
            <form class="bg-light p-4 rounded-lg m-3 bg-primary" style="
    background: linear-gradient(
        to right,
        #4c24ee,
        #4624ee,
        #245aee,
        #24a7ee
    );
" method="POST" action="/crear-reunion">
                <h2 class="text-lg font-semibold mb-4">Crear Reunión</h2>
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
</div>


<script>
    function setupSelect(selectId, currentState) {
        const selectElement = document.getElementById(selectId);
        selectElement.innerHTML = '';

        const optionSelected = document.createElement('option');
        optionSelected.value = currentState;
        optionSelected.selected = true;
        optionSelected.textContent = currentState.charAt(0) + currentState.slice(1);

        const alternativeState = currentState === 'pública' ? 'privada' : 'pública';
        const optionAlternative = document.createElement('option');
        optionAlternative.value = alternativeState;
        optionAlternative.textContent = alternativeState.charAt(0) + alternativeState.slice(1);

        selectElement.appendChild(optionSelected);
        selectElement.appendChild(optionAlternative);
    }

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
                const reunion = reuniones.find(reu => reu.id.toString() === searchQuery);

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