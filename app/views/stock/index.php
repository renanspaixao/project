<?php layout('header', ['title' => 'Lista de Estoque']); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<style>
    .main-content,
    .content-wrapper,
    .container-fluid,
    .row,
    .col-12,
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

    .card-custom {
        border: 1px solid #ccc;
        border-radius: 6px;
        padding: 15px;
        height: 100%;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        background-color: #fff;
        cursor: pointer;
    }

    .card-info {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        height: 100%;
        border-color: #53b6b2 !important;
    }

    .card-title {
        font-weight: bold;
        font-size: 16px;
        margin-bottom: 8px;
    }

    .card-sub {
        font-size: 14px;
        color: #555;
    }

    .stock-amount {
        font-size: 14px;
        font-weight: bold;
        margin-top: 10px;
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
                    <h5 class="mb-0 h5-header">Estoque</h5>
                </div>
                <div class="row g-3">
                    <div class="d-flex justify-content-end mt-3 px-4">
                        <button id="open-cart" class="btn btn-outline-success" title="Carrinho">
                            <i class="bi bi-cart3"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3" id="stock-cards"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addToCartModal" tabindex="-1" aria-labelledby="addToCartModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="add-to-cart-form">
                <div class="modal-header">
                    <h5 class="modal-title" id="addToCartModalLabel">Adicionar ao Carrinho</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="modal-product-id">
                    <input type="hidden" id="modal-variation-id">
                    <div class="mb-3">
                        <label for="product-quantity" class="form-label">Quantidade</label>
                        <input type="number" class="form-control" id="product-quantity" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Adicionar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="checkout-form">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkoutModalLabel">Finalizar Pedido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="asterisk">Nome</label>
                                <input type="text" class="form-control" name="clientName" placeholder="Digite o seu Nome Completo" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="asterisk">Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Digite o seu E-mail" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cep">CEP</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="cep" id="cep" required placeholder="Digite o seu CEP e busque">
                                    <button type="button" class="btn btn-outline-secondary" id="search-cep" title="Buscar endereço">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="asterisk">Endereço</label>
                                <input type="text" class="form-control" name="address" id="address" placeholder="..." required readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="asterisk">Cidade</label>
                                <input type="text" class="form-control" name="city" id="city" required readonly placeholder="...">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="asterisk">Estado</label>
                                <input type="text" class="form-control" name="state" id="state" required readonly placeholder="...">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <input type="hidden" id="couponId" name="couponId">
                                <label for="couponCode">Cupom</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="couponCode" id="couponCode" placeholder="Digite o Cupom">
                                    <button type="button" class="btn btn-outline-secondary" id="search-coupon" title="Buscar Cupom">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Desconto</label>
                                <input type="text" class="form-control" id="couponDiscount" name="couponDiscount" placeholder="..." readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="hidden" id="subtotal" name="subtotal">
                                <label>Frete</label>
                                <input type="text" class="form-control" id="shipping" required name="shipping" placeholder="..." readonly>
                            </div>
                        </div>
                        <div id="cart-summary" class="text-center"></div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center align-items-center">
                    <button type="submit" class="btn btn-success" title="Enviar Pedido">Enviar Pedido</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php layout('footer'); ?>

<script>
    $(document).ready(function() {
        function updateCartCount() {
            $.get('/cart/count', function(res) {
                $('#cart-count').text(res.count);
            });
        }

        updateCartCount();

        $.ajax({
            url: '/stock/listing',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                const stockList = response.data;
                const container = $('#stock-cards');
                container.empty();

                if (stockList.length === 0) {
                    container.append('<div class="col-12"><p class="text-center">Nenhum item em Estoque.</p></div>');
                    return;
                }

                stockList.forEach(item => {
                    const card = `
                    <div class="col-md-3">
                        <div class="card-custom card-info card-clickable"
                            data-product-id="${item.productId}"
                            data-variation-id="${item.variationId || ''}"
                            data-stock="${item.stock}">
                            <div class="card-title">${item.productName}</div>
                            <hr class="mb-4">
                            <div class="card-sub">Preço: R$ ${parseFloat(item.price).toFixed(2).replace('.', ',')}</div>
                            ${item.color || item.size ? `
                                <div class="card-sub">
                                    ${item.color ? `Cor: ${item.color}<br>` : ''}
                                    ${item.size ? `Tamanho: ${item.size}<br>` : ''}
                                </div>
                            ` : ''}
                            <hr class="mb-4">
                            <div class="stock-amount">Estoque: ${item.stock}</div>
                        </div>
                    </div>
                `;
                    container.append(card);
                });
            },
            error: function() {
                $('#stock-cards').html('<div class="col-12"><p class="text-center text-danger">Erro ao carregar o estoque.</p></div>');
            }
        });

        $(document).on('click', '.card-clickable', function() {
            const productId = $(this).data('product-id');
            const variationId = $(this).data('variation-id');
            const stock = $(this).data('stock');

            $('#modal-product-id').val(productId);
            $('#modal-variation-id').val(variationId);
            $('#product-quantity').attr('max', stock).val(1);
            $('#addToCartModal').modal('show');
        });

        $('#add-to-cart-form').on('submit', function(e) {
            e.preventDefault();

            const productId = $('#modal-product-id').val();
            const variationId = $('#modal-variation-id').val();
            const quantity = parseInt($('#product-quantity').val());

            const selector = `.card-clickable[data-product-id="${productId}"]` +
                (variationId ? `[data-variation-id="${variationId}"]` : `[data-variation-id=""]`);
            const card = $(selector);
            const priceText = card.find('.card-sub').first().text().replace('Preço: R$ ', '').replace(',', '.');
            const price = parseFloat(priceText);

            $.ajax({
                url: '/cart/add',
                method: 'POST',
                data: {
                    productId,
                    variationId,
                    quantity,
                    price
                },
                success: function() {
                    $('#addToCartModal').modal('hide');
                    updateCartCount();
                    let currentStock = parseInt(card.data('stock'));
                    const newStock = currentStock - quantity;
                    card.data('stock', newStock);
                    card.find('.stock-amount').text(`Estoque: ${newStock}`);
                },
                error: function() {
                    alert('Erro ao adicionar ao carrinho.');
                }
            });
        });

        $('#open-cart').on('click', function() {
            $.get('/cart/list', function(items) {
                if (items.length === 0) {
                    alert('Carrinho vazio!');
                    return;
                }

                let html = '<h6>Itens:</h6><ul class="list-group">';
                let subtotal = 0;
                items.forEach(item => {
                    const price = parseFloat(item.price) || 0;
                    const total = item.quantity * price;
                    subtotal += total;
                    html += `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Produto ID ${item.productId} - Qtd: ${item.quantity} - R$ ${total.toFixed(2).replace('.', ',')}
                <button class="btn btn-sm btn-danger remove-from-cart" data-product-id="${item.productId}" data-variation-id="${item.variationId}">&times;</button>
            </li>`;
                });
                html += `</ul><hr><strong>Subtotal: R$ ${subtotal.toFixed(2).replace('.', ',')}</strong>`;

                let shippingCost = 0;
                if (subtotal >= 52.00 && subtotal <= 166.59) {
                    shippingCost = 15.00;
                } else if (subtotal > 200.00) {
                    shippingCost = 0.00;
                } else {
                    shippingCost = 20.00;
                }

                $('#shipping').val(`R$ ${shippingCost.toFixed(2).replace('.', ',')}`);

                let finalTotal = subtotal + shippingCost;
                $('#subtotal').val(finalTotal.toFixed(2).replace('.', ','));

                $('#cart-summary').html(html);
                $('#checkoutModal').modal('show');
            });
        });

        $(document).on('click', '.remove-from-cart', function() {
            const productId = $(this).data('product-id');
            const variationId = $(this).data('variation-id');

            $.post('/cart/remove', {
                productId,
                variationId
            }, function() {
                updateCartCount();

                $.get('/cart/list', function(items) {
                    if (!Array.isArray(items) || items.length === 0) {
                        $('#checkoutModal').modal('hide');
                        setTimeout(() => {
                            location.reload();
                        }, 300);
                    } else {
                        $('#open-cart').click();
                    }
                });
            });
        });

        $('#checkout-form').on('submit', function(e) {
            e.preventDefault();
            const data = $(this).serialize();

            $.post('/order/store', data, function(response) {
                alert('Pedido enviado com sucesso, verifique o seu e-mail!');
                $('#checkoutModal').modal('hide');
                updateCartCount();
            }).fail(function(xhr) {
                alert(xhr.responseJSON?.error || 'Erro ao finalizar o pedido');
            });
        });

        $('#search-cep').on('click', function() {
            const cep = $('#cep').val().replace(/\D/g, '');

            if (cep.length !== 8) {
                alert('CEP inválido. Deve conter 8 dígitos.');
                return;
            }

            $('#search-cep').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

            $.get(`https://viacep.com.br/ws/${cep}/json/`, function(data) {
                if (data.erro) {
                    alert('CEP não encontrado.');
                    return;
                }

                $('#address').val(data.logradouro || '');
                $('#city').val(data.localidade || '');
                $('#state').val(data.uf || '');
            }).fail(function() {
                alert('Erro ao consultar o CEP. Verifique a conexão ou tente novamente.');
            }).always(function() {
                $('#search-cep').html('<i class="bi bi-search"></i>');
            });
        });


        $(document).ready(function() {
            $('#search-coupon').on('click', function() {
                const couponCode = $('#couponCode').val();
                const subtotal = parseFloat($('#cart-summary').find('strong').text().replace('Subtotal: R$ ', '').replace(',', '.'));

                if (!couponCode) {
                    alert('Por favor, insira o código do cupom.');
                    return;
                }

                $.get('/coupon/check', {
                    couponCode: couponCode,
                    subtotal: subtotal
                }, function(response) {
                    if (response.error) {
                        alert(response.error);
                    } else {
                        if (response.coupon.discountType === "P") {
                            $('#couponDiscount').val(`${response.coupon.discountValue}%`);
                        } else if (response.coupon.discountType === "V") {
                            $('#couponDiscount').val(`R$ ${response.coupon.discountValue.toFixed(2).replace('.', ',')}`);
                        }

                        const finalSubtotal = response.finalSubtotal;
                        $('#cart-summary').find('strong').text(`Subtotal: R$ ${finalSubtotal.toFixed(2).replace('.', ',')}`);

                        $('#checkoutModal').find('input[name="subtotal"]').val(finalSubtotal);

                        let discount = response.discount;
                        if (!$('#cart-discount').length) {
                            $('#cart-summary').append(`<div id="cart-discount" class="my-3"><strong>Desconto aplicado: </strong> R$ - ${discount.toFixed(2).replace('.', ',')}</div>`);
                        } else {
                            $('#cart-discount').text(`Desconto aplicado: R$ ${discount.toFixed(2).replace('.', ',')}`);
                        }
                    }
                }).fail(function(xhr) {
                    let errorMessage = xhr.responseJSON?.error || 'Cupom inválido para esta compra.';
                    alert(errorMessage);
                });
            });
        });


    });
</script>