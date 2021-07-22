<div class="card card-order">
    <div class="card-body">
        <h3 class="card-order-title">Orden #00{{ $order->id }}</h3>

        <h6 class="title-order-separator">Resumen de tu Orden</h6>
        @foreach($order->cart->items as $product)
        @php
            $item_img = $product['item']['image'];
            $variant = $product['variant'];
        @endphp

        <div class="product-checkout-line">
            <div class="row align-items-center">
                <div class="col-5 text-left">
                    <div class="row align-items-center">
                        <div class="col">
                            <img class="mr-4" style="width: 100px;" src="{{ asset('img/products/' . $item_img ) }}" alt="{{ $product['item']['name'] }}">
                        </div>
                        <div class="col">
                            <h5 class="mt-0 mb-0">{{ $product['item']['name'] }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col col-md-2 text-center">
                    <h5 class="mb-0"><span>Talla:</span><br> {{ $variant }}</h5>
                </div>
                <div class="col col-md-2 text-center">
                    <h5 class="mb-0"><span>Cantidad:</span><br> {{ $product['qty'] }}</h5>
                </div>
                <div class="col-2 hid text-right">
                    <p class="mb-0">$ {{ number_format($product['price']) }} </p>
                </div>
            </div>
        </div>
        @endforeach
        <hr>
        <div class="row">
            <div class="col text-right">
                <h5 class="price-order">Total: $ {{ $order->cart->totalPrice }}</h5>
            </div>
        </div>

        <div class="order-info">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <div class="bg-primary text-white">
                        <h6 class="title-order-separator">Método de Pago</h6>
                        <p class="order-info-big">
                            @if(substr($order->payment_id, 0, 3) == 'ord')
                            Tarjeta {{ $order->card_digits }}
                            @else
                            OXXO PAY
                            @endif
                        </p>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="">
                        @if(substr($order->payment_id, 0, 3) == 'ord')
                        <h6 class="title-order-separator">Número</h6>
                        @else
                        <h6 class="title-order-separator">Referencia</h6>
                        @endif
                        <p class="order-info-big">
                            @if(substr($order->payment_id, 0, 3) == 'ord')
                            **** **** **** {{ $order->card_digits }}
                            @else
                            {{ wordwrap($order->payment_id, 4, "-", true) }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <h6 class="title-order-separator">Dirección de Envío</h6>
                <p>{{ $order->street }} {{ $order->street_num }}, {{ $order->city }} {{ $order->state }}, {{ $order->country }}, C.P {{ $order->postal_code }}</p>
            </div>
            <div class="col-md-6 text-right">
                <h6 class="title-order-separator">Fecha de Compra</h6>
                <p>{{ Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}</p>
            </div>
        </div>
    </div>
</div>