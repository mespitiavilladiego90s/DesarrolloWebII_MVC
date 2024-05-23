<nav class="navbar navbar-expand-sm bgnavbar navbar-dark d-flex justify-content-center">
    <!-- Brand -->
    <a class="navbar-brand" href="/index">MyMeet!</a>


    <?php if (checkPerm('Admin', true)) : ?>
        <!-- Links -->
        <ul class="navbar-nav">

            <li class="nav-item">
                <a class="nav-link" href="#">Reuniones</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#">Usuarios</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#">Actas</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#">Informes</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/logout">Cerrar Sesión</a>
            </li>



        </ul>
    <?php endif; ?>


</nav>

<main>
    <div class="container d-flex justify-content-center my-5">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row align-items-center mb-2">
                    <div class="col-sm-4">
                        <h2 style="color: white;">Asignar acta a reunión</h2>
                    </div>
                    <div class="col-sm-4">
                        <div class="input-group rounded">
                            <input type="search" class="form-control rounded" placeholder="Search" aria-label="Buscar..." aria-describedby="search-addon" />
                            <span class="input-group-text border-0" id="search-addon">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-4 text-end">
                        <button class="btn btn-success" onclick="openMeetingDialog()">
                            Agendar nueva reunión
                        </button>
                    </div>
                </div>
            </div>

            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Seleccionar</th>
                        <th>Asunto</th>
                        <th>Fecha reunión</th>
                        <th>Participantes</th>
                        <th>Lugar encuentro</th>
                        <th>Compromisos</th>
                        <th>Inicio</th>
                        <th>Finalización</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <span class="custom-checkbox">
                                <input type="checkbox" id="checkbox1" name="options[]" value="1">
                                <label for="checkbox1"></label>
                            </span>
                        </td>
                        <td>Reunión comité curricular</td>

                        <td>
                            <button type="button" class="btn btn-info">Asignar</button>
                        </td>

                        <td>
                            <button type="button" class="btn btn-primary"><span class="material-symbols-outlined d-flex justify-content-center">person_add</span></button>

                        </td>

                        <td>
                            <h1>-</h1>
                        </td>

                        <td>
                            <button type="button" class="btn btn-danger"><span class="material-symbols-outlined d-flex justify-content-center">description</span></button>
                        </td>

                        <td>
                            <h1>-</h1>
                        </td>

                        <td>
                            <h1>-</h1>
                        </td>


                        <td>
                            <a href="#editEmployeeModal" class="edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
                            <a href="#deleteEmployeeModal" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <span class="custom-checkbox">
                                <input type="checkbox" id="checkbox1" name="options[]" value="1">
                                <label for="checkbox1"></label>
                            </span>
                        </td>
                        <td>Reunión comité curricular</td>

                        <td>
                            <button type="button" class="btn btn-info">Asignar</button>
                        </td>

                        <td>
                            <button type="button" class="btn btn-primary"><span class="material-symbols-outlined d-flex justify-content-center">person_add</span></button>

                        </td>

                        <td>
                            <h1>-</h1>
                        </td>

                        <td>
                            <button type="button" class="btn btn-danger"><span class="material-symbols-outlined d-flex justify-content-center">description</span></button>
                        </td>

                        <td>
                            <h1>-</h1>
                        </td>

                        <td>
                            <h1>-</h1>
                        </td>


                        <td>
                            <a href="#editEmployeeModal" class="edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
                            <a href="#deleteEmployeeModal" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="hint-text">Mostrando <b>2</b> de <b>2</b> resultados</div>
                        <ul class="pagination">
                            <li class="page-item"><a href="#" class="page-link">&laquo;</a></li>
                            <li class="page-item"><a href="#" class="page-link">1</a></li>
                            <li class="page-item"><a href="#" class="page-link">2</a></li>
                            <li class="page-item active"><a href="#" class="page-link">3</a></li>
                            <li class="page-item"><a href="#" class="page-link">4</a></li>
                            <li class="page-item"><a href="#" class="page-link">5</a></li>
                            <li class="page-item"><a href="#" class="page-link">&raquo;</a></li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>

</main>

<script>
    function openMeetingDialog() {
    Swal.fire({
        title: "Nueva Reunión",
        input: "text",
        inputLabel: "Ingresa aquí el asunto de la nueva reunión",
        showCancelButton: true,
        inputValidator: (value) => {
            if (!value) {
                return "¡Necesitas escribir algo!";
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const meetingSubject = result.value;
            // Creamos objeto FormData con el asunto de la reunión
            const formData = new FormData();
            formData.append('meetingSubject', meetingSubject);
            
            // Realizar solicitud AJAX usando fetch
            fetch('/crear-acta', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    return response.json();
                }
                throw new Error('Error en la solicitud AJAX');
            })
            .then(data => {
                Swal.fire(`Se ha agregado correctamente la reunión con el asunto: ${result.value}`);
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    });
}

</script>
