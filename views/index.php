<div class="container d-flex justify-content-center my-5">
    <div class="table-wrapper">
        <div class="table-title">
            <!-- Searchbar -->
            <div class="row align-items-center mb-2">
                <div class="col-sm-4">
                    <h2 style="color: white;">Visualizador de reuniones</h2>
                </div>
                <div class="col-sm-4">
                    <div class="input-group rounded">
                        <input type="search" class="form-control rounded" placeholder="Buscar reunión..." aria-label="Buscar..." aria-describedby="search-addon" />
                        <span class="input-group-text border-0" id="search-addon">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                </div>
                <div class="col-sm-4 text-end">
                    <button class="btn btn-success" onclick="window.location.href='/crear-reunion'">
                        Crear nueva reunión
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabla -->
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Asunto</th>
                    <th>Fecha reunión</th>
                    <th>Inicio</th>
                    <th>Finalización</th>
                    <th>Lugar encuentro</th>
                    <th>Asistentes</th>
                    <th>Actas</th>
                    <th>Compromisos</th>
                </tr>
            </thead>
            <tbody id="reuniones-tbody">
                <!-- Los datos de las reuniones se insertarán aquí -->
            </tbody>
        </table>
    </div>
</div>

<script>
    async function fetchAndRenderData() {
        try {
            const [reunionesResponse] = await Promise.all([
                fetch('/obtenerinforeuniones', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
            ]);

            if (reunionesResponse.ok) {
                const reuniones = await reunionesResponse.json();

                // Limpiar los cuerpos de las tablas antes de renderizar nuevos datos
                const reunionesTbody = document.getElementById('reuniones-tbody');
                reunionesTbody.innerHTML = '';
                reuniones.forEach(reunion => {
                    const row = document.createElement('tr');

                    const asuntoCell = document.createElement('td');
                    asuntoCell.textContent = reunion.asunto;
                    row.appendChild(asuntoCell);

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

                    const asistentesCell = document.createElement('td');
                    const asistentesIcon = document.createElement('i');
                    asistentesIcon.classList.add('fas', 'fa-users');
                    asistentesIcon.addEventListener('click', () => mostrarAsistentes(reunion.asistentes));
                    asistentesCell.appendChild(asistentesIcon);
                    row.appendChild(asistentesCell);

                    const actasArray = Object.values(reunion.actas); // Convertir el objeto actas a un array

                    const actasCell = document.createElement('td');
                    const actasIcon = document.createElement('i');
                    actasIcon.classList.add('fas', 'fa-file-alt');
                    actasIcon.addEventListener('click', () => mostrarActas(actasArray));
                    actasCell.appendChild(actasIcon);
                    row.appendChild(actasCell);

                    const compromisosCell = document.createElement('td');
                    const compromisosIcon = document.createElement('i');
                    compromisosIcon.classList.add('fas', 'fa-tasks');
                    compromisosIcon.addEventListener('click', () => mostrarCompromisos(actasArray.flatMap(acta => acta.compromisos)));
                    compromisosCell.appendChild(compromisosIcon);
                    row.appendChild(compromisosCell);

                    reunionesTbody.appendChild(row);
                });
            } else {
                console.error('Error fetching reuniones:', reunionesResponse.statusText);
            }
        } catch (error) {
            console.error('Error during fetch operations:', error);
        }
    }

    // Llamar a la función fetchAndRenderData cuando el DOM esté cargado
    document.addEventListener('DOMContentLoaded', fetchAndRenderData);

    // Configurar un intervalo para actualizar los datos cada 1 segundos
    setInterval(fetchAndRenderData, 1000);

    async function mostrarAsistentes(asistentes) {
        if (Array.isArray(asistentes) && asistentes.length > 0) {
            const asistentesContent = asistentes.map(asistente => `
                <div>ID: ${asistente.id}</div>
                <div>Nombre: ${asistente.nombre}</div>
                <div>Apellido: ${asistente.apellido}</div>
                <hr>
            `).join('');

            await Swal.fire({
                title: 'Asistentes',
                html: asistentesContent,
                showCloseButton: true
            });
        } else {
            await Swal.fire({
                title: 'Asistentes',
                text: 'No hay asistentes para mostrar aún.',
                showCloseButton: true
            });
        }
    }

    async function mostrarActas(actas) {
        if (Array.isArray(actas) && actas.length > 0) {
            const actasContent = actas.map(acta => `
                <div>ID: ${acta.id}</div>
                <div>Contenido: ${acta.contenido}</div>
                <hr>
            `).join('');

            await Swal.fire({
                title: 'Actas',
                html: actasContent,
                showCloseButton: true
            });
        } else {
            await Swal.fire({
                title: 'Actas',
                text: 'No hay actas para mostrar aún.',
                showCloseButton: true
            });
        }
    }

    async function mostrarCompromisos(compromisos) {
        if (Array.isArray(compromisos) && compromisos.length > 0) {
            const compromisosContent = compromisos.map(compromiso => `
                <div>ID: ${compromiso.id}</div>
                <div>Descripción: ${compromiso.descripcion}</div>
                <div>Fecha Entrega: ${compromiso.fecha_entrega}</div>
                <div>Estado: ${compromiso.estado}</div>
                <div>Responsable: ${compromiso.responsable.nombre} ${compromiso.responsable.apellido}</div>
                <hr>
            `).join('');

            await Swal.fire({
                title: 'Compromisos',
                html: compromisosContent,
                showCloseButton: true
            });
        } else {
            await Swal.fire({
                title: 'Compromisos',
                text: 'No hay compromisos para mostrar aún.',
                showCloseButton: true
            });
        }
    }
</script>