<div class="modal fade mt-5" id="modal_datos_tarjeta_incremento" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Datos de la tarjeta</h1>
            </div>
            <div class="modal-body">

                <div class="form-group mb-3">
                    <img alt="alt" src="assets/img/visa.png" style="width: 50px" />
                    <img alt="alt" src="assets/img/mastercard.png" style="width: 50px" />
                    <img alt="alt" src="assets/img/oca.png" style="width: 50px" />
                    <img alt="alt" src="assets/img/lider.png" style="width: 50px" />
                    <img alt="alt" src="assets/img/creditel.jpg" style="width: 50px" />
                </div>

                <span class="fs-5">
                    Importe Total:
                    <span class="fw-bolder text-warning" id="span_precio_total_a_pagar_pago_incremento">?</span>
                </span>

                <div class="row mb-3 mt-3">
                    <div class="col-lg-4">
                        <div class="form-floating input-group mb-3 ">
                            <input type="text" class="form-control solo_numeros" id="txt_numero_tarjeta_pago_incremento" placeholder="Número de la tarjeta" pattern="\d*" min="1" maxlength="16" data-checkout="cardNumber" oninput="maxLengthCheck(this)" onchange="guessPaymentMethod(event)" />
                            <span class="input-group-text" id="basic-addon1">
                                <div id="img_tarjeta_incremento"></div>
                            </span>
                            <label for="txt_numero_tarjeta_pago_incremento">Número de la tarjeta:
                                <span class="fw-bolder text-danger">*</span>
                            </label>
                            <input type="hidden" id="payment_method_id_grupo_incremento" name="payment_method_id">
                            <input type="hidden" id="is_mercadopago_incremento" name="is_mercadopago">
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control solo_numeros" id="txt_numero_cvv_tarjeta_pago_incremento" placeholder="CVV (tres dígitos de atrás)" pattern="\d*" maxlength="4" data-checkout="securityCode" />
                            <label for="txt_numero_cvv_tarjeta_pago_incremento">CVV (tres dígitos de atrás):</label>
                            <input type="hidden" name="tipo_tarjeta_grupo" id="tipo_tarjeta_grupo_incremento">
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="select_banco_emisor_tarjeta_pago_incremento" aria-label="Seleccione un método de pago"></select>
                            <label for="select_banco_emisor_tarjeta_pago_incremento">Seleccione el banco emisor: <span class="fw-bolder text-danger">*</span></label>
                            <input type="hidden" id="banco_incremento" class="banco" name="banco">
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control solo_numeros" id="txt_cedula_titular_tarjeta_pago_incremento" placeholder="Cédula del títular de la tarjeta" pattern="\d*" min="1" maxlength="8" data-checkout="docNumber" oninput="maxLengthCheck(this)" />
                            <label for="txt_cedula_titular_tarjeta_pago_incremento">Cédula del títular de la tarjeta: <span class="fw-bolder text-danger">*</span></label>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control solo_letras" id="txt_nombre_titular_tarjeta_pago_incremento" placeholder="Nombre del títular de la tarjeta" maxlength="200" data-checkout="cardholderName" />
                            <label for="txt_nombre_titular_tarjeta_pago_incremento">Nombre del títular de la tarjeta: <span class="fw-bolder text-danger">*</span></label>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-floating mb-3">
                            <select
                                class="form-select"
                                id="select_mes_vencimiento_tarjeta_pago_incremento"
                                aria-label="Mes de vencimiento"
                                data-checkout="cardExpirationMonth">
                                <option value="" selected>Seleccione una opción</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                            </select>
                            <label for="select_mes_vencimiento_tarjeta_pago_incremento">Mes de vencimiento: <span class="fw-bolder text-danger">*</span></label>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="select_anio_vencimiento_tarjeta_pago_incremento" aria-label="Año de vencimiento" data-checkout="cardExpirationYear">
                            </select>
                            <label for="select_anio_vencimiento_tarjeta_pago_incremento">Año de vencimiento: <span class="fw-bolder text-danger">*</span></label>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="txt_correo_electronico_titular_tarjeta_pago_incremento" placeholder="Correo eléctronico del títular de la tarjeta" maxlength="250" />
                            <label for="txt_correo_electronico_titular_tarjeta_pago_incremento">Correo eléctronico del títular de la tarjeta: <span class="fw-bolder text-danger">*</span></label>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control solo_numeros" id="txt_celular_titular_tarjeta_pago_incremento" placeholder="Celular del títular de la tarjeta" pattern="\d*" maxlength="9" />
                            <label for="txt_celular_titular_tarjeta_pago_incremento">Celular del títular de la tarjeta: <span class="fw-bolder text-danger">*</span></label>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control solo_numeros" id="txt_telefono_titular_tarjeta_pago_incremento" placeholder="Teléfono del títular de la tarjeta" pattern="\d*" maxlength="8" />
                            <label for="txt_telefono_titular_tarjeta_pago_incremento">Teléfono del títular de la tarjeta: <span class="fw-bolder text-danger">*</span></label>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="paymentMethodId" id="paymentMethodId_incremento" value="" />
                <select id="docType" data-checkout="docType" style="display: none">
                    <option value="CI" selected="true">DNI</option>
                </select>
                </form>

                <input type="hidden" name="hash" id="hash" value="" />

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="validar_datos_tarjeta_incremento(false)">Guardar</button>
            </div>
        </div>
    </div>
</div>