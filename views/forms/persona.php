<div class="container">
    <div class="row g-4">
        <!-- Formulario de Personas -->
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
                " method="POST" action="/crear-persona">
                    <h2 class="text-lg font-semibold mb-4">Crear Usuario</h2>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo isset($form_data['nombre']) ? s($form_data['nombre']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="apellido" class="form-label">Apellido:</label>
                        <input type="text" id="apellido" name="apellido" class="form-control" value="<?php echo isset($form_data['apellido']) ? s($form_data['apellido']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?php echo isset($form_data['email']) ? s($form_data['email']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección:</label>
                        <input type="text" id="direccion" name="direccion" class="form-control" value="<?php echo isset($form_data['direccion']) ? s($form_data['direccion']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" id="password" name="password" class="form-control" value="<?php echo isset($form_data['password']) ? s($form_data['password']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Telefono:</label>
                        <input type="tel" id="telefono" name="telefono" class="form-control" value="<?php echo isset($form_data['telefono']) ? s($form_data['telefono']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="rol" class="form-label">Rol:</label>
                        <select id="rol" name="rol" class="form-control">
                            <option value="" disabled <?php echo !isset($form_data['rol']) ? 'selected' : ''; ?>>Seleccione el rol</option>
                            <option value="Default" <?php echo isset($form_data['rol']) && $form_data['rol'] == 'Default' ? 'selected' : ''; ?>>Default</option>
                            <option value="Admin" <?php echo isset($form_data['rol']) && $form_data['rol'] == 'Admin' ? 'selected' : ''; ?>>Admin</option>
                            <option value="Inform Manager" <?php echo isset($form_data['rol']) && $form_data['rol'] == 'Inform Manager' ? 'selected' : ''; ?>>Inform Manager</option>
                        </select>
                    </div>
                    <div class="d-grid mb-2">
                        <input type="submit" value="Crear Usuario" class="btn btn-success">
                    </div>
                    <div class="d-grid">
                        <button id="actualizarPersona" class="btn btn-info">Actualizar Usuario</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Tabla -->
        
        <div class="col-md-6 pt-4">
            <h1 class="text-white">Tabla de Usuarios</h1>
            <div class="table-responsive" style="max-height: 600px;">
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
        </div>
    </div>
</div>

<script>
    async function fetchAndRenderPersonas() {
        try {
            const response = await fetch('/obtenerpersonas', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                const personas = await response.json();
                const tbody = document.getElementById('personas-tbody');

                // Limpiar el tbody antes de agregar nuevos datos
                tbody.innerHTML = '';

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

                    tbody.appendChild(row);
                });
            } else {
                console.error('Error fetching personas:', response.statusText);
            }
        } catch (error) {
            console.error('Error during fetch operation:', error);
        }
    }

    // Llamar a la función fetchAndRenderPersonas cuando el DOM esté cargado
    document.addEventListener('DOMContentLoaded', fetchAndRenderPersonas);

    // Configurar un intervalo para actualizar los datos cada 10 segundos
    setInterval(fetchAndRenderPersonas, 10000);

    function setupSelect(selectId, currentState) {
        const selectElement = document.getElementById(selectId);
        selectElement.innerHTML = '';

        const states = ['Default', 'Admin', 'Inform Manager'];

        states.forEach(state => {
            const option = document.createElement('option');
            option.value = state;
            option.textContent = state;
            if (state === currentState) {
                option.selected = true;
            }
            selectElement.appendChild(option);
        });
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

    document.getElementById('actualizarPersona').addEventListener('click', async function(event) {
        event.preventDefault();

        const {
            value: searchQuery
        } = await Swal.fire({
            title: 'Buscar Persona',
            input: 'text',
            inputPlaceholder: 'Ingrese la ID de la persona...',
            showCancelButton: true,
            inputValidator: (value) => {
                if (!value) {
                    return 'Por favor ingrese una ID';
                }
            }
        });

        if (searchQuery) {
            try {
                const response = await fetch('/obtenerpersonas', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                const personas = await response.json();
                const persona = personas.find(per => per.id.toString() === searchQuery);

                if (persona) {
                    const tableContent = `
                <div class="max-w-md mx-auto bg-white shadow-md rounded my-6 dark:bg-gray-800 d-flex justify-content-center">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-blue-100 dark:bg-blue-900 border border-blue-500 dark:border-blue-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">ID</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${persona.id}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">Nombre</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${persona.nombre}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">Apellido</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${persona.apellido}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">Email</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${persona.email}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">Telefono</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${persona.telefono}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">Rol</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${persona.rol}</td>
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
                                title: 'Modificar Persona',
                                html: `
                            <input id="swal-id" class="swal2-input" placeholder="ID" value="${persona.id}" readonly>

                            <input type="text" id="swal-nombre" class="swal2-input" placeholder="Nombre" value="${persona.nombre}">

                            <input type="text" id="swal-apellido" class="swal2-input" placeholder="Apellido" value="${persona.apellido}">

                            <input type="email" id="swal-email" class="swal2-input" placeholder="Email" value="${persona.email}">

                            <input type="text" id="swal-telefono" class="swal2-input" placeholder="Telefono" value="${persona.telefono}">

                            <input type="password" id="swal-password" class="swal2-input" placeholder="password" value="${persona.password}">

                            <select id="swal-rol" class="swal2-control">`,
                                focusConfirm: false,
                                didOpen: () => {
                                    setupSelect('swal-rol', persona.rol);
                                },
                                preConfirm: () => {
                                    return {
                                        id: document.getElementById('swal-id').value,
                                        nombre: document.getElementById('swal-nombre').value,
                                        apellido: document.getElementById('swal-apellido').value,
                                        email: document.getElementById('swal-email').value,
                                        password: document.getElementById('swal-password').value,
                                        telefono: document.getElementById('swal-telefono').value,
                                        rol: document.getElementById('swal-rol').value

                                    };
                                },
                                showCancelButton: true,
                                confirmButtonText: 'Guardar Cambios',
                                cancelButtonText: 'Cancelar',
                                showLoaderOnConfirm: true,
                                allowOutsideClick: () => !Swal.isLoading(),
                            }).then(async (result) => {
                                if (result.isConfirmed) {
                                    const updatedPersona = result.value;
                                    try {
                                        const response = await fetch('/actualizarpersonas', {
                                            method: 'PUT',
                                            headers: {
                                                'Content-Type': 'application/json'
                                            },
                                            body: JSON.stringify(updatedPersona)
                                        });

                                        const data = await response.json();

                                        if (data.success) {
                                            showToast(data.success, 'success');
                                        } else if (data.error) {
                                            showToast(data.error, 'error');
                                        }
                                    } catch (error) {
                                        showToast('Error al actualizar la persona', 'error');
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
                    text: "Hubo un problema al buscar la persona.",
                    icon: "error"
                });
            }
        }
    });

    <?php if (!empty($message)) : ?>
        showToast("<?php echo $message; ?>", "<?php echo (empty($alertas['error'])) ? 'success' : 'error'; ?>");
        <?php endif; ?>1
</script>