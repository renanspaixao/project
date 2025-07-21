<?php layout('header', ['title' => 'Visualizar Cupom']); ?>

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
                    <form id="formCoupon">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <label for="id" class="form-label">ID</label>
                                <input type="text" class="form-control" id="id" name="id" readonly>
                            </div>
                            <div class="col-md-3">
                                <label for="code" class="form-label asterisk">Código</label>
                                <input type="text" class="form-control" id="code" name="code" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="description" class="form-label asterisk">Descrição</label>
                                <input type="text" class="form-control" id="description" name="description" readonly>
                            </div>
                            <div class="col-md-3">
                                <label for="active" class="form-label asterisk">Ativo?</label>
                                <select class="form-select" id="active" name="active" disabled>
                                    <option value="">Selecione</option>
                                    <option value="1">Sim</option>
                                    <option value="0">Não</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-3">
                                <label for="discountType" class="form-label asterisk">Tipo de Desconto</label>
                                <select class="form-select" id="discountType" name="discountType" disabled>
                                    <option value="">Selecione</option>
                                    <option value="P">Percentual (%)</option>
                                    <option value="V">Valor Fixo (R$)</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="discountValue" class="form-label asterisk">Valor do Desconto</label>
                                <input type="text" class="form-control" id="discountValue" name="discountValue" readonly>
                            </div>
                            <div class="col-md-2">
                                <label for="expirationDate" class="form-label">Validade</label>
                                <input type="date" class="form-control" id="expirationDate" name="expirationDate" readonly>
                            </div>
                            <div class="col-md-2">
                                <label for="usageLimit" class="form-label asterisk">Limite de Uso</label>
                                <input type="text" class="form-control" id="usageLimit" name="usageLimit" readonly>
                            </div>
                             <div class="col-md-2">
                                <label for="minimumValue" class="form-label asterisk">Valor Mínimo da Compra</label>
                                <input type="text" class="form-control" id="minimumValue" name="minimumValue" readonly>
                            </div>
                        </div>
                        <hr class="mb-4">
                        <div class="d-flex justify-content-center mt-4">
                            <a href="/coupon" class="btn btn-info" title="Voltar à Listagem">Voltar</a>
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
        const path = window.location.pathname;
        const match = path.match(/\/coupon\/show\/(\d+)/);

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
    
</script>
