<!DOCTYPE html>
<html>
<head>
    <title>Shopping List Email</title>
</head>
<body>
    <h1>Your Shopping List</h1>
    <p>Here is your shopping list:</p>
    
    <ul>
        @foreach ($shoppingList->items as $item)
            <li>{{ $item->description }}</li>
        @endforeach
    </ul>
    

    <p class="total-price">Total Price: £{{ $totalAmount }}</p>

    @if ($message)
        <p>Additional message: {{ $messages }}</p>
    @endif
</body>
</html>