<?php layout('header', ['title' => 'Lista de Cupons']); ?>

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
                    <h5 class="mb-0 h5-header">Cupons</h5>
                </div>
                <div class="row g-3">
                    <div class="d-flex justify-content-end mt-4">
                        <a href="/coupon/create" class="btn btn-success" title="Cadastrar Cupom">Cadastrar</a>
                    </div>
                </div>
                <div class="card-body px-4 py-3">
                    <div class="table-responsive">
                        <table id="couponsTable" class="table table-bordered table-striped table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Código</th>
                                    <th>Descrição</th>
                                    <th>Tipo</th>
                                    <th>Valor</th>
                                    <th>Validade</th>
                                    <th>Limite de Uso</th>
                                    <th>Valor de Mínimo de Compra</th>
                                    <th>Ativo?</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
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
        const table = $('#couponsTable').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
            },
            processing: true,
            ajax: {
                url: "/coupon/listing",
                type: "POST"
            },
            columns: [
                { data: "id" },
                { data: "code" },
                { data: "description" },
                { data: "discountType" },
                { data: "discountValue" },
                { data: "expirationDate" },
                { data: "usageLimit" },
                { data: "minimumValue" },
                { data: "active" },
                { data: "actions", orderable: false, searchable: false }
            ],
            order: [[0, "desc"]]
        });

        $(document).on('click', '.btn-delete', function () {
            const btn = $(this);
            const id = btn.data('id');

            if (!confirm('Deseja realmente excluir este cupom?')) return;

            btn.prop('disabled', true);

            $.post('/coupon/delete', { id }, function () {
                alert('Cupom excluído com sucesso!');
                table.ajax.reload();
            }).fail(function () {
                alert('Cupom já foi usado em alguma compra, apenas inative ele.');
            }).always(() => {
                btn.prop('disabled', false);
            });
        });
    });

    
</script>

