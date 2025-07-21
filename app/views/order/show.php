<?php layout('header', ['title' => 'Visualizar Pedido']); ?>

<style>
    .main-content, .content-wrapper, .container-fluid, .row, .col-12, .card, .card-header {
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

    .form-control[readonly], .form-control[disabled] {
        background-color: #f5f5f5;
        color: #000;
        cursor: not-allowed;
    }
</style>

<div class="container-fluid p-0 m-0">
    <div class="row g-0 m-0">
        <div class="col-12 p-0 m-0">
            <div class="card border-0 rounded-0">
                <div class="card-header page-header">
                    <h5 class="mb-0 h5-header">Visualizar Pedido</h5>
                </div>
                <div class="card-body px-4 py-3">
                    <form>
                        <div class="row g-3">
                            <div class="col-md-2">
                                <label class="form-label">ID</label>
                                <input type="text" class="form-control" id="id" readonly>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Cliente</label>
                                <input type="text" class="form-control" id="clientName" readonly>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">CEP</label>
                                <input type="text" class="form-control" id="cep" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Endereço</label>
                                <input type="text" class="form-control" id="address" readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Cidade</label>
                                <input type="text" class="form-control" id="city" readonly>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">UF</label>
                                <input type="text" class="form-control" id="state" readonly>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row g-3">
                            <div class="col-md-2">
                                <label class="form-label">Subtotal</label>
                                <input type="text" class="form-control" id="subtotal" readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Frete</label>
                                <input type="text" class="form-control" id="shipping" readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Cupom</label>
                                <input type="text" class="form-control" id="couponCode" readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Desconto</label>
                                <input type="text" class="form-control" id="discount" readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Total</label>
                                <input type="text" class="form-control" id="total" readonly>
                            </div>
                        </div>

                        <hr class="my-4">
                        <h6>Itens do Pedido</h6>
                        <div id="order-items" class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Produto</th>
                                        <th>Cor</th>
                                        <th>Tamanho</th> 
                                        <th>Quantidade</th>
                                        <th>Preço Unitário</th>
                                        <th>Preço Total</th>
                                    </tr>
                                </thead>
                                <tbody id="order-items-body"></tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            <a href="/order" class="btn btn-info">Voltar</a>
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
        const match = path.match(/\/order\/show\/(\d+)/);

        if (match) {
            const id = match[1];

            $.get(`/order/select/${id}`, function(data) {
                $('#id').val(data.id);
                $('#clientName').val(data.clientName);
                $('#email').val(data.email);
                $('#cep').val(data.cep);
                $('#address').val(data.address);
                $('#city').val(data.city);
                $('#state').val(data.state);
                $('#couponCode').val(data.couponCode ? data.couponCode : 'Pedido sem nenhum Cupom');
                $('#subtotal').val('R$ ' + parseFloat(data.subtotal).toFixed(2));
                $('#shipping').val('R$ ' + parseFloat(data.shipping).toFixed(2));
                $('#discount').val('R$ ' + parseFloat(data.discount).toFixed(2));
                $('#total').val('R$ ' + parseFloat(data.total).toFixed(2));

                if (Array.isArray(data.items)) {
                    const tbody = $('#order-items-body');
                    data.items.forEach(item => {
                        const row = `
                            <tr>
                                <td>${item.productName || '-'}</td>
                                <td>${item.variationColor || '-'}</td>
                                <td>${item.variationSize || '-'}</td>
                                <td>${item.quantity}</td>
                                <td>R$ ${parseFloat(item.priceUnit).toFixed(2)}</td>
                                <td>R$ ${parseFloat(item.priceTotal).toFixed(2)}</td>
                            </tr>
                        `;
                        tbody.append(row);
                    });
                }
            }).fail(() => {
                alert('Erro ao carregar pedido.');
                window.location.href = "/order";
            });
        }
    });

</script>
