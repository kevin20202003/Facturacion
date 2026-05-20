@extends('layouts.app')

@section('content')
    <h1>Nueva Factura</h1>
    <form method="POST" action="{{ route('invoices.store') }}" id="invoiceForm">
        @csrf

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-3">
            <label class="form-label">Cliente</label>
            <select name="client_id" class="form-select" required>
                @foreach($clients as $c)
                    <option value="{{ $c->id }}" {{ old('client_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label">Número de factura</label>
                <input name="invoice_number" class="form-control" value="{{ old('invoice_number') }}" required />
            </div>
            <div class="col-md-6">
                <label class="form-label">Fecha</label>
                <input type="date" name="date" class="form-control" value="{{ old('date') }}" />
            </div>
        </div>

        <h3>Items</h3>
        <div class="table-responsive">
            <table id="itemsTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio unitario</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="itemsBody"></tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end">Subtotal:</td>
                        <td id="subtotal">0.00</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-end">Impuesto ({{ intval(($taxRate ?? 0.21) * 100) }}%):</td>
                        <td id="tax">0.00</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                        <td id="total">0.00</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mb-3">
            <button type="button" id="addItem" class="btn btn-sm btn-outline-primary">Agregar ítem</button>
            <button type="submit" class="btn btn-primary">Crear factura</button>
        </div>
    </form>
@endsection

@section('scripts')
<script>
    const products = @json($products->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'price' => (float) $p->price]));
    let taxRate = {{ $taxRate ?? 0.21 }};
    let itemIndex = 0;
    const oldItems = @json(old('items', []));

    function createRow(productId = null, quantity = 1) {
        const tr = document.createElement('tr');
        tr.dataset.index = itemIndex;
        tr.innerHTML = `
            <td>
                <select name="items[${itemIndex}][product_id]" class="form-select productSelect" required>
                    <option value="">--Seleccionar--</option>
                    ${products.map(p => `<option value="${p.id}" data-price="${p.price}" ${productId==p.id?'selected':''}>${p.name} - ${p.price}</option>`).join('')}
                </select>
            </td>
            <td><input type="number" name="items[${itemIndex}][quantity]" class="form-control qty" value="${quantity}" min="1" required /></td>
            <td>
                <span class="unitPrice">0.00</span>
                <input type="hidden" name="items[${itemIndex}][unit_price]" class="unitPriceInput" value="0" />
            </td>
            <td><span class="lineTotal">0.00</span></td>
            <td><button type="button" class="btn btn-danger btn-sm removeItem">Eliminar</button></td>
        `;
        itemIndex++;
        return tr;
    }

    function recalc() {
        let subtotal = 0;
        document.querySelectorAll('#itemsBody tr').forEach(tr => {
            const select = tr.querySelector('.productSelect');
            const opt = select.options[select.selectedIndex];
            const price = opt ? parseFloat(opt.dataset.price || 0) : 0;
            const qty = parseInt(tr.querySelector('.qty').value) || 0;
            const line = parseFloat((price * qty).toFixed(2));
            tr.querySelector('.unitPrice').textContent = price.toFixed(2);
            tr.querySelector('.unitPriceInput').value = price.toFixed(2);
            tr.querySelector('.lineTotal').textContent = line.toFixed(2);
            subtotal += line;
        });
        const tax = parseFloat((subtotal * taxRate).toFixed(2));
        const total = parseFloat((subtotal + tax).toFixed(2));
        document.getElementById('subtotal').textContent = subtotal.toFixed(2);
        document.getElementById('tax').textContent = tax.toFixed(2);
        document.getElementById('total').textContent = total.toFixed(2);
    }

    function addRowListeners(row) {
        row.querySelector('.productSelect').addEventListener('change', recalc);
        row.querySelector('.qty').addEventListener('input', recalc);
        row.querySelector('.removeItem').addEventListener('click', function() { row.remove(); recalc(); });
    }

    document.getElementById('addItem').addEventListener('click', () => {
        const row = createRow();
        document.getElementById('itemsBody').appendChild(row);
        addRowListeners(row);
        recalc();
    });

    document.getElementById('invoiceForm').addEventListener('input', recalc);

    // Restore old items after validation error or add a default row
    if (oldItems && oldItems.length) {
        oldItems.forEach(it => {
            const row = createRow(it.product_id ?? null, it.quantity ?? 1);
            document.getElementById('itemsBody').appendChild(row);
            addRowListeners(row);
        });
    } else {
        document.getElementById('addItem').click();
    }
    recalc();
</script>
@endsection
