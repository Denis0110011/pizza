<div>
    <h1>Список продуктов</h1>
    <ul>
        @foreach($products as $product)
            <li>
                <strong>{{$product->name}}</strong><br>
                <p>Тип:{{$product->type}}</p>
                <p>Цена:{{$product->price}}</p>
                <p>{{$product->description}}</p>
            </li>
        @endforeach
    </ul>
</div>
