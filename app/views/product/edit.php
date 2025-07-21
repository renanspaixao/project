<?php layout('header', ['title' => 'Alterar Produto']); ?>

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
                    <h5 class="mb-0 h5-header">Produto</h5>
                </div>
                <div class="card-body px-4 py-3">
                    <form id="formProduct" method="post">
                        <div class="row g-3">
                            <div class="col-md-2" style="display: none;">
                                <label for="id" class="form-label">ID</label>
                                <input type="text" class="form-control" id="id" name="id" readonly>
                            </div>
                            <div class="col-md-7">
                                <label for="name" class="form-label asterisk">Nome</label>
                                <input type="text" class="form-control" id="name" name="name" required maxlength="255">
                            </div>
                            <div class="col-md-5">
                                <label for="price" class="form-label asterisk">Preço</label>
                                <input type="text" class="form-control" id="price" name="price" required maxlength="11">
                            </div>
                        </div>

                        <hr class="my-4">

                        <h6>Variações</h6>
                        <div id="variations-container" class="mb-3"></div>
                        <button type="button" class="btn btn-primary btn-sm mb-3" id="add-variation">+ Adicionar Variação</button>

                        <hr class="mb-4">
                        <div class="d-flex justify-content-center mt-4">
                            <button type="submit" class="btn btn-success me-2">Alterar</button>
                            <a href="/product" class="btn btn-info">Voltar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php layout('footer'); ?>

<script>
    let variationIndex = 0;

    function addVariationRow(variation = {}) {
        const row = `
        <div class="row g-2 variation-row mb-2" data-index="${variationIndex}">
            <div class="col-md-3">
                <label class="form-label">Cor</label>
                <input type="text" class="form-control" name="variations[${variationIndex}][color]" placeholder="Ex: Verde" maxlength="45" value="${variation.color || ''}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tamanho</label>
                <input type="text" class="form-control" name="variations[${variationIndex}][size]" placeholder="Ex: G" maxlength="45" value="${variation.size || ''}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Preço</label>
                <input type="text" class="form-control variation-price" name="variations[${variationIndex}][price]" placeholder="Preço" value="${variation.price || ''}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Estoque</label>
                <input type="number" class="form-control variation-stock" name="variations[${variationIndex}][stock]" placeholder="Quantidade para o Estoque" value="${variation.stock || ''}" min="0" step="1" required>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm remove-variation" title="Remover Variação">Remover</button>
            </div>
        </div>`;
        $('#variations-container').append(row);
        variationIndex++;
    }

    $(document).ready(() => {
        $('#price').on('input', function() {
            let value = $(this).val().replace(/[^0-9.]/g, '');
            const parts = value.split('.');
            if (parts.length > 2) value = parts[0] + '.' + parts[1];
            $(this).val(value);
        });

        $(document).on('input', '.variation-stock', function () {
            this.value = this.value.replace(/\D/g, '');
        });

        $(document).on('input', '.variation-price', function () {
            let val = this.value.replace(/[^0-9.]/g, '');
            const parts = val.split('.');
            if (parts.length > 2) val = parts[0] + '.' + parts[1];
            this.value = val;
        });

        const path = window.location.pathname;
        const match = path.match(/\/product\/edit\/(\d+)/);

        if (match) {
            const id = match[1];

            $.get(`/product/select/${id}`, function(data) {
                $('#id').val(data.id);
                $('#name').val(data.name);
                $('#price').val(data.price);
            });

            $.get(`/product/variations/${id}`, function(variations) {
                if (Array.isArray(variations)) {
                    variations.forEach(v => addVariationRow(v));
                }
            });
        }

        $('#add-variation').on('click', () => addVariationRow());

        $(document).on('click', '.remove-variation', function() {
            $(this).closest('.variation-row').remove();
        });

        $('#formProduct').on('submit', function(e) {
            e.preventDefault();
            const id = $('#id').val();
            const url = id ? '/product/update' : '/product/store';

            $.ajax({
                url,
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function() {
                    alert('Produto alterado com sucesso!');
                    window.location.href = `/product/edit/${id}`;
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('Erro ao salvar o produto!');
                }
            });
        });
    });


</script>
