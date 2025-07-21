<?php layout('header', ['title' => 'Visualizar Produto']); ?>

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
                    <h5 class="mb-0 h5-header">Produto</h5>
                </div>
                <div class="card-body px-4 py-3">
                    <form id="formProduct">
                        <div class="row g-3">
                            <div class="col-md-2" style="display: none;">
                                <label for="id" class="form-label">ID</label>
                                <input type="text" class="form-control" id="id" name="id" readonly>
                            </div>
                            <div class="col-md-7">
                                <label for="name" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="name" name="name" readonly>
                            </div>
                            <div class="col-md-5">
                                <label for="price" class="form-label">Preço</label>
                                <input type="text" class="form-control" id="price" name="price" readonly>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h6>Variações</h6>
                        <div id="variations-container" class="mb-3"></div>

                        <hr class="mb-4">
                        <div class="d-flex justify-content-center mt-4">
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
                <input type="text" class="form-control" name="variations[${variationIndex}][color]" value="${variation.color || ''}" readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tamanho</label>
                <input type="text" class="form-control" name="variations[${variationIndex}][size]" value="${variation.size || ''}" readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label">Preço</label>
                <input type="text" class="form-control" name="variations[${variationIndex}][price]" value="${variation.price || ''}" readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label">Estoque</label>
                <input type="number" class="form-control" name="variations[${variationIndex}][stock]" value="${variation.stock || 0}" readonly>
            </div>
        </div>`;
        $('#variations-container').append(row);
        variationIndex++;
    }

    $(document).ready(() => {
        const path = window.location.pathname;
        const match = path.match(/\/product\/show\/(\d+)/);

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
    });

    
</script>
