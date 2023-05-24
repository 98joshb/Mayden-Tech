@extends('layouts.base')

@section('content')
    <div class="container">
        <h1>Shopping List</h1>

        <form method="POST" action="{{ route('shopping_list.store') }}">
            @csrf
            <div class="form-group">
                <label for="description">Description:</label>
                <input type="text" id="description" name="description" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="amount">Amount:</label>
                <input type="text" id="amount" name="amount" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Item</button>
        </form>

        <ul class="list-group sortable-list">
            @if(isset($items) && count($items) > 0)
                @foreach($items as $shoppingList)
                    @foreach($shoppingList->items as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-start {{ $item->checked ? 'text-decoration-line-through' : '' }}" data-item-id="{{ $item->id }}">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">{{ $item->description }}</div>
                                <small class="text-muted">{{ $item->amount }}</small>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ $item->quantity }}</span>
                            <form method="POST" action="{{ route('shopping_list.destroy', $item->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm mt-2">Delete</button>
                            </form>
                        </li>
                    @endforeach
                @endforeach
            @else
                <li class="list-group-item">No items</li>
            @endif
        </ul>

        <div class="text-end mt-3">
            <strong>Total Amount: ${{ $totalAmount }}</strong>
        </div>

        @if(isset($alertMessage))
            <div class="alert {{ $alertClass }}">
                {{ $alertMessage }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const sortableList = document.querySelector('.sortable-list');

            new Sortable(sortableList, {
                onEnd: function(event) {
                    const itemIds = Array.from(sortableList.children).map(item => item.dataset.itemId);
                    $.ajax({
                        url: '{{ route('shopping_list.reorder') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            itemIds: itemIds
                        }
                    });
                }
            });

            const hardcodedProducts = [
                { id: 1, text: 'Carrot', price: 1.99 },
                { id: 2, text: 'Broccoli', price: 2.49 },
                { id: 3, text: 'Tomato', price: 0.99 },
                { id: 4, text: 'Apple', price: 0.89 },
                { id: 5, text: 'Banana', price: 0.49 },
                { id: 6, text: 'Orange', price: 1.29 },
                { id: 7, text: 'Strawberry', price: 2.99 },
                { id: 8, text: 'Grapes', price: 3.99 },
            ];

            $('#description').selectize({
                valueField: 'text',
                labelField: 'text',
                searchField: 'text',
                maxItems: 1,
                options: hardcodedProducts,
                onChange: function(value) {
                    const selectedProduct = this.options[value];
                    const price = selectedProduct ? selectedProduct.price : '';
                    $('#amount').val(price);

                    const quantity = $('#quantity').val();
                    if (!quantity) {
                        $('#quantity').val(1);
                    }
                }
            });

            $('.list-group-item').click(function() {
                const listItem = $(this);
                const itemId = $(this).data('itemId');

                $.ajax({
                    url: `/shopping_list/${itemId}/check`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            listItem.toggleClass('text-decoration-line-through');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            });

            function updateTotalPrice() {
                const quantity = parseFloat($('#quantity').val());
                const selectedProduct = $('#description').val();
                const product = hardcodedProducts.find(p => p.text === selectedProduct);
                const price = product ? parseFloat(product.price) : 0;
                const totalPrice = quantity * price;
                $('#amount').val(totalPrice.toFixed(2));
            }

            $('#description').change(function() {
                updateTotalPrice();
            });

            $('#quantity').keyup(function() {
                updateTotalPrice();
            });

        });
    </script>
@endpush
