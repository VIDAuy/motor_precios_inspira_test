<div class="modal fade mt-5" id="modal_validar_cedula" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Datos de la venta</h1>
            </div>
            <div class="modal-body">

                <h6 class="mt-3">Validar cédula del beneficiario en el padrón</h6>

                <div class="form-floating mt-4 mb-3">
                    <input type="text" class="form-control" id="txt_cedula" placeholder="Ingrese una cédula" maxlength="8">
                    <label for="txt_cedula">Ingrese una cédula:</label>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="validar_cedula()">Validar</button>
            </div>
        </div>
    </div>
</div>