<?php layout('header', ['title' => 'Lista de Pedidos']); ?>

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
                    <h5 class="mb-0 h5-header">Pedidos</h5>
                </div>
                <div class="card-body px-4 py-3">
                    <div class="table-responsive">
                        <table id="ordersTable" class="table table-bordered table-striped table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Email</th>
                                    <th>Subtotal</th>
                                    <th>Desconto</th>
                                    <th>Frete</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Data</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php layout('footer'); ?>

<script>
    $(document).ready(() => {
        const table = $('#ordersTable').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
            },
            processing: true,
            ajax: {
                url: "/order/listing",
                type: "POST"
            },
            columns: [{
                    data: "id"
                },
                {
                    data: "clientName"
                },
                {
                    data: "email"
                },
                {
                    data: "subtotal"
                },
                {
                    data: "discount"
                },
                {
                    data: "shipping"
                },
                {
                    data: "total"
                },
                {
                    data: "statusLabel"
                },
                {
                    data: "createdAt"
                },
                {
                    data: "actions",
                    orderable: false,
                    searchable: false
                }
            ],
            order: [
                [0, "desc"]
            ]
        });

        $(document).on('click', '.btn-deliver', function() {
            const btn = $(this);
            const id = btn.data('id');

            if (!confirm('Marcar este pedido como Entregue?')) return;

            btn.prop('disabled', true);

            $.post('/order/delivered', {
                id
            }, function() {
                alert('Pedido marcado como entregue! O cliente recebeu um e-mail de informando.');
                table.ajax.reload();
            }).fail(function() {
                alert('Erro ao marcar como entregue. Contate o administrador.');
            }).always(() => {
                btn.prop('disabled', false);
            });
        });


        $(document).on('click', '.btn-cancel', function() {
            const btn = $(this);
            const id = btn.data('id');

            if (!confirm('Deseja realmente cancelar este pedido?')) return;

            btn.prop('disabled', true);

            $.post('/order/cancel', {
                id
            }, function() {
                alert('Pedido cancelado com sucesso. O cliente recebeu um e-mail de informando.');
                table.ajax.reload();
            }).fail(function() {
                alert('Erro ao cancelar. Contate o administrador.');
            }).always(() => {
                btn.prop('disabled', false);
            });
        });


    });
</script>