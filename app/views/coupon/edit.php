<?php layout('header', ['title' => 'Alterar Cupons']); ?>

<style>
    .main-content,
    .content-wrapper,
    .container-fluid,
    .row,
    .col-12,
    .card,
    .card-header {
        margin: 0 !important;
        padding: 0 !important;
        border-radius: 0 !important;
    }

    .page-header {
        background-color: #53b6b2 !important;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        color: white;
    }

    .h5-header {
        padding-left: 32px;
    }

    .asterisk::after {
        content: '*';
        color: #ec4b24;
    }
</style>

<div class="container-fluid p-0 m-0">
    <div class="row g-0 m-0">
        <div class="col-12 p-0 m-0">
            <div class="card border-0 rounded-0">
                <div class="card-header page-header">
                    <h5 class="mb-0 h5-header">Cupom</h5>
                </div>
                <div class="card-body px-4 py-3">
                    <form id="formCoupon" method="post">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <label for="id" class="form-label">ID</label>
                                <input type="text" class="form-control" id="id" name="id" readonly>
                            </div>
                            <div class="col-md-3">
                                <label for="code" class="form-label asterisk">Código</label>
                                <input type="text" class="form-control" id="code" name="code" placeholder="Ex: BEMVINDO10" required maxlength="25">
                            </div>
                            <div class="col-md-4">
                                <label for="description" class="form-label asterisk">Descrição</label>
                                <input type="text" class="form-control" id="description" name="description" placeholder="Ex: 10% na primeira compra" required maxlength="255">
                            </div>
                            <div class="col-md-3">
                                <label for="active" class="form-label asterisk">Ativo?</label>
                                <select class="form-select" id="active" name="active" required>
                                    <option value="">Selecione</option>
                                    <option value="1">Sim</option>
                                    <option value="0">Não</option>
                                </select>
                            </div>

                        </div>
                        <div class="row g-3 mt-2">
                            <div class="col-md-3">
                                <label for="discountType" class="form-label asterisk">Tipo de Desconto</label>
                                <select class="form-select" id="discountType" name="discountType" required>
                                    <option value="">Selecione</option>
                                    <option value="P">Percentual (%)</option>
                                    <option value="V">Valor Fixo (R$)</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="discountValue" class="form-label asterisk">Valor do Desconto</label>
                                <input type="text" class="form-control" id="discountValue" name="discountValue" required>
                            </div>
                            <div class="col-md-2">
                                <label for="expirationDate" class="form-label">Validade</label>
                                <input type="date" class="form-control" id="expirationDate" name="expirationDate" required
                                    value="<?= date('Y-m-d', strtotime('+1 month')) ?>">
                            </div>
                            <div class="col-md-2">
                                <label for="usageLimit" class="form-label asterisk">Limite de Uso</label>
                                <input type="text" class="form-control" id="usageLimit" name="usageLimit" maxlength="11">
                            </div>
                            <div class="col-md-2">
                                <label for="minimumValue" class="form-label asterisk">Valor Mínimo da Compra</label>
                                <input type="text" class="form-control" id="minimumValue" name="minimumValue">
                            </div>
                        </div>
                        <hr class="mb-4">
                        <div class="d-flex justify-content-center mt-4">
                            <button type="submit" class="btn btn-success me-2" title="Alterar Cupom">Alterar</button>
                            <a href="/coupon" class="btn btn-info" title="Voltar á Listagem">Voltar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php layout('footer'); ?>

<script>
    $(document).ready(() => {
        const $discountValue = $('#discountValue');
        const $discountType = $('#discountType');

        $discountValue.prop('disabled', true).val('');

        $discountType.on('change', function() {
            const selected = $(this).val();
            if (selected === 'P' || selected === 'V') {
                $discountValue.prop('disabled', false).val('');
            } else {
                $discountValue.prop('disabled', true).val('');
            }
        });

        $discountValue.on('input', function() {
            let value = $(this).val();

            value = value.replace(/[^0-9.]/g, '');

            const parts = value.split('.');
            if (parts.length > 2) {
                value = parts[0] + '.' + parts[1];
            }

            if ($discountType.val() === 'P') {
                const num = parseFloat(value);
                if (!isNaN(num) && num > 100) {
                    value = '100';
                }
            }

            $(this).val(value);
        });

        const $minimumValue = $('#minimumValue');

        $minimumValue.on('input', function() {
            let value = $(this).val();

            value = value.replace(/[^0-9.]/g, '');

            const parts = value.split('.');
            if (parts.length > 2) {
                value = parts[0] + '.' + parts[1];
            }

            $(this).val(value);
        });

        $('#usageLimit').on('input', function() {
            let value = $(this).val().replace(/\D/g, '');
            $(this).val(value);
        });
    });

    $(document).ready(() => {
        const path = window.location.pathname;
        const match = path.match(/\/coupon\/edit\/(\d+)/);

        if (match) {
            const id = match[1];

            $.ajax({
                url: '/coupon/select/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#id').val(data.id);
                    $('#code').val(data.code);
                    $('#description').val(data.description);
                    $('#active').val(data.active);
                    $('#discountType').val(data.discountType).trigger('change');
                    $('#discountValue').val(data.discountValue);
                    $('#expirationDate').val(data.expirationDate);
                    $('#usageLimit').val(data.usageLimit);
                    $('#minimumValue').val(data.minimumValue);
                },
                error: function(xhr) {
                    alert('Erro ao carregar os dados do cupom.');
                    console.error(xhr.responseText);
                }
            });
        }
    });

    $('#formCoupon').on('submit', function(e) {
        e.preventDefault();

        const discountType = $('#discountType').val();
        const $discountValue = $('#discountValue');
        const $minimumValue = $('#minimumValue');

        const discountVal = parseFloat($discountValue.val()) || 0;
        const minimumVal = parseFloat($minimumValue.val()) || 0;

        if (discountType === 'V' && discountVal > minimumVal) {
            alert('O valor do Desconto não pode ser maior que o Valor Mínimo da Compra.');

            return;
        }

        const id = $('#id').val();
        const url = id ? '/coupon/update' : '/coupon/store';

        $.ajax({
            url: url,
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                alert('Cupom alterado com sucesso!');
                window.location.href = `/coupon/edit/${id}`;
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                alert('Erro ao salvar o cupom!');
            }
        });
    });
</script>