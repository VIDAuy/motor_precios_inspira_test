<!-- Modal -->
<div class="modal fade mt-5" id="modal_cargar_beneficiarios_grupo_familiar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">
                    Cargar Beneficiario 👉
                    <span class="fw-bolder" id="span_cedula_beneficiario_grupo_familiar"></span>
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">


                <svg xmlns="http://www.w3.org/2000/svg" class="d-none mt-3 mb-4">
                    <symbol id="exclamation-triangle-fill" viewBox="0 0 16 16">
                        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"></path>
                    </symbol>
                </svg>
                <div class="alert alert-warning d-flex align-items-center mt-2 mb-4" role="alert">
                    <svg class="bi flex-shrink-0 me-2" role="img" aria-label="Warning:">
                        <use xlink:href="#exclamation-triangle-fill"></use>
                    </svg>
                    <div>
                        IMPORTANTE: Los campos con <span class="text-danger fw-bolder">*</span> son obligatorios
                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-2">
                        <div class="form-floating mb-4">
                            <input type="text" class="form-control solo_numeros" id="txt_cedula_beneficiario_grupo_familiar" placeholder="Cédula" disabled>
                            <label for="txt_cedula_beneficiario_grupo_familiar">Cédula:</label>
                        </div>
                    </div>


                    <div class="col-lg-4 col-md-4 col-sm-2">
                        <div class="form-floating mb-4">
                            <input type="text" class="form-control solo_letras" id="txt_nombre_beneficiario_grupo_familiar" placeholder="Nombre completo">
                            <label for="txt_nombre_beneficiario_grupo_familiar">Nombre completo: <span class="text-danger fw-bolder">*</span></label>
                        </div>
                    </div>


                    <div class="col-lg-4 col-md-4 col-sm-2">
                        <div class="form-floating mb-4">
                            <input type="date" class="form-control" id="txt_fecha_nacimiento_beneficiario_grupo_familiar" placeholder="Fecha de nacimiento">
                            <label for="txt_fecha_nacimiento_beneficiario_grupo_familiar">Fecha de nacimiento: <span class="text-danger fw-bolder">*</span></label>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-auto mb-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txt_calle_beneficiario_grupo_familiar" placeholder="Calle" maxlength="20">
                            <label for="txt_calle_beneficiario_grupo_familiar">Calle: <span class="text-danger fw-bolder">*</span></label>
                        </div>
                        <div class="form-text" id="basic-addon4">Caracteres disponibles: <span class="text-danger fw-bolder" id="span_calle_beneficiario_grupo_familiar">20</span></div>
                    </div>


                    <div class="col-auto mb-4">
                        <div>Elige una opción: <span class="text-danger fw-bolder">*</span></div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="rbtn_beneficiario_grupo_familiar" id="rbtn_beneficiario_grupo_familiar_1" value="Puerta">
                            <label class="form-check-label" for="rbtn_beneficiario_grupo_familiar_1">
                                Puerta
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="rbtn_beneficiario_grupo_familiar" id="rbtn_beneficiario_grupo_familiar_2" value="Solar/manzana">
                            <label class="form-check-label" for="rbtn_beneficiario_grupo_familiar_2">
                                Solar/manzana
                            </label>
                        </div>
                    </div>


                    <div class="col-auto mb-4 div_rbtn_1_beneficiario_grupo_familiar">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txt_puerta_beneficiario_grupo_familiar" placeholder="Puerta" maxlength="4">
                            <label for="txt_puerta_beneficiario_grupo_familiar">Puerta: <span class="text-danger fw-bolder">*</span></label>
                        </div>
                        <div class="form-text" id="basic-addon4">Caracteres disponibles: <span class="text-danger fw-bolder" id="span_puerta_beneficiario_grupo_familiar">4</span></div>
                    </div>


                    <div class="col-auto mb-4 div_rbtn_2_beneficiario_grupo_familiar">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txt_solar_beneficiario_grupo_familiar" placeholder="Solar" maxlength="4">
                            <label for="txt_solar_beneficiario_grupo_familiar">Solar: <span class="text-danger fw-bolder">*</span></label>
                        </div>
                        <div class="form-text" id="basic-addon4">Caracteres disponibles: <span class="text-danger fw-bolder" id="span_solar_beneficiario_grupo_familiar">4</span></div>
                    </div>

                    <div class="col-auto mb-4 div_rbtn_2_beneficiario_grupo_familiar">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txt_manzana_beneficiario_grupo_familiar" placeholder="Manzana" maxlength="4">
                            <label for="txt_manzana_beneficiario_grupo_familiar">Manzana: <span class="text-danger fw-bolder">*</span></label>
                        </div>
                        <div class="form-text" id="basic-addon4">Caracteres disponibles: <span class="text-danger fw-bolder" id="span_manzana_beneficiario_grupo_familiar">4</span></div>
                    </div>


                    <div class="col-auto mb-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txt_esquina_beneficiario_grupo_familiar" placeholder="Esquina" maxlength="20">
                            <label for="txt_esquina_beneficiario_grupo_familiar">Esquina: <span class="text-danger fw-bolder">*</span></label>
                        </div>
                        <div class="form-text" id="basic-addon4">Caracteres disponibles: <span class="text-danger fw-bolder" id="span_esquina_beneficiario_grupo_familiar">20</span></div>
                    </div>


                    <div class="col-auto mb-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txt_apartamento_beneficiario_grupo_familiar" placeholder="Apartamento" maxlength="4">
                            <label for="txt_apartamento_beneficiario_grupo_familiar">Apartamento:</label>
                        </div>
                        <div class="form-text" id="basic-addon4">Caracteres disponibles: <span class="text-danger fw-bolder" id="span_apartamento_beneficiario_grupo_familiar">4</span></div>
                    </div>


                    <div class="col-auto">
                        <div class="form-floating mb-4">
                            <input type="text" class="form-control" id="txt_referencia_beneficiario_grupo_familiar" placeholder="Referencia">
                            <label for="txt_referencia_beneficiario_grupo_familiar">Referencia: <span class="text-danger fw-bolder">*</span></label>
                        </div>
                    </div>


                    <div class="col-auto">
                        <div class="form-floating mb-4">
                            <select class="form-select" id="select_localidades_beneficiario_grupo_familiar" aria-label="Seleccione una localidad">
                            </select>
                            <label for="select_localidades_beneficiario_grupo_familiar">Seleccione una localidad: <span class="text-danger fw-bolder">*</span></label>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-auto">
                        <div class="form-floating mb-4">
                            <input type="email" class="form-control" id="txt_correo_electronico_beneficiario_grupo_familiar" placeholder="Correo electrónico">
                            <label for="txt_correo_electronico_beneficiario_grupo_familiar">Correo electrónico:</label>
                        </div>
                    </div>


                    <div class="col-auto">
                        <div class="form-floating mb-4">
                            <input type="text" class="form-control solo_numeros" id="txt_celular_beneficiario_grupo_familiar" placeholder="Celular" maxlength="9">
                            <label for="txt_celular_beneficiario_grupo_familiar">Celular: <span class="text-danger fw-bolder">*</span></label>
                        </div>
                    </div>


                    <div class="col-auto">
                        <div class="form-floating mb-4">
                            <input type="text" class="form-control solo_numeros" id="txt_telefono_fijo_beneficiario_grupo_familiar" placeholder="Teléfono fijo" maxlength="8">
                            <label for="txt_telefono_fijo_beneficiario_grupo_familiar">Teléfono fijo:</label>
                        </div>
                    </div>


                    <div class="col-auto">
                        <div class="form-floating mb-4">
                            <input type="text" class="form-control solo_numeros" id="txt_telefono_alternativo_beneficiario_grupo_familiar" placeholder="Teléfono alternativo" maxlength="9">
                            <label for="txt_telefono_alternativo_beneficiario_grupo_familiar">Teléfono alternativo:</label>
                        </div>
                    </div>
                </div>


                <div class="form-floating mb-4">
                    <select class="form-select" id="select_dato_extra_grupo_familiar" aria-label="Seleccione una opción en caso de aplicar a alguna">
                        <option value="3" selected>No aplica</option>
                        <option value="2">Herencia</option>
                    </select>
                    <label for="select_dato_extra_grupo_familiar">Elige una opción en caso de aplicar a alguna:</label>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="agregar_datos_beneficiario(false)">Guardar</button>
            </div>
        </div>
    </div>
</div>