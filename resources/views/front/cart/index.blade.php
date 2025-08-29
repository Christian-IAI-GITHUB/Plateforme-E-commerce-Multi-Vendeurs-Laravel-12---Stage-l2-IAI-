@extends('front.layout.layout')

@section('content')
<div class="container py-4">
    @if($items->isEmpty())
    <div class="row">
        <div class="col-lg-8">
            <div class="table-responsive">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th>Produits</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Montant Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="cart-empty text-center py-5">
                <div class="cart-empty__icon mb-3"><i class="fa fa-shopping-cart"></i></div>
                <h5 class="mb-2">Votre panier est vide</h5>
                <p class="text-muted mb-4">Ajoutez des produits pour commencer vos achats</p>
                <a href="{{ url('/') }}" class="btn btn-primary">Continuer les achats</a>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-light"><strong>Résumé de la commande</strong></div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2"><span>Sous-total</span><span>0.00 FCFA</span></div>
                    <div class="d-flex justify-content-between"><span>Livraison</span><span>Gratuite</span></div>
                    <hr>
                    <div class="d-flex justify-content-between"><strong>Total</strong><strong>0.00 FCFA</strong></div>
                    <button class="btn btn-light btn-block mt-3" disabled>Panier vide</button>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-lg-8">
            <div class="table-responsive">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th>Produit</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        @php
                            $thumb = !empty($item->product->main_image)
                                ? asset('product-image/small/' . $item->product->main_image)
                                : asset('front/images/no-image.jpg');
                        @endphp
                        <tr>
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $thumb }}" alt="{{ $item->product->product_name ?? 'Produit' }}" style="width:50px;height:50px;object-fit:cover" class="mr-2">
                                    <div>
                                        <div>{{ $item->product->product_name ?? 'Produit' }}</div>
                                        @if(!empty($item->product->product_code))
                                            <small class="text-muted">{{ $item->product->product_code }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ number_format($item->unit_price, 0, ',', ' ') }} FCFA</td>
                            <td>
                                <div class="input-group" style="width:120px;">
                                    <div class="input-group-prepend"><button class="btn btn-primary btn-sm js-qty" data-id="{{ $item->id }}" data-delta="-1">-</button></div>
                                    <input type="text" class="form-control form-control-sm text-center" value="{{ $item->quantity }}" readonly>
                                    <div class="input-group-append"><button class="btn btn-primary btn-sm js-qty" data-id="{{ $item->id }}" data-delta="1">+</button></div>
                                </div>
                            </td>
                            <td>{{ number_format($item->total_price, 0, ',', ' ') }} FCFA</td>
                            <td><button class="btn btn-sm btn-danger js-remove" data-id="{{ $item->id }}">×</button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">Résumé</div>
                <div class="card-body">
                    <p class="d-flex justify-content-between"><span>Total</span><strong id="js-cart-total">{{ number_format($total, 0, ',', ' ') }} FCFA</strong></p>
                    <a href="{{ url('/checkout') }}" class="btn btn-primary btn-block">Passer la commande</a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@push('styles')
<style>
.cart-empty__icon{width:84px;height:84px;border-radius:50%;background:#f3f4f6;display:inline-flex;align-items:center;justify-content:center;color:#b91c1c}
.cart-empty__icon i{font-size:38px}
.thead-dark th{background:#313843;color:#fff;border-color:#313843}
</style>
@endpush
@push('scripts')
<script>
document.addEventListener('click', async (e)=>{
    if(e.target.classList.contains('js-qty')){
        const id = e.target.dataset.id;
        const delta = parseInt(e.target.dataset.delta,10);
        const row = e.target.closest('tr');
        const current = parseInt(row.querySelector('input').value,10);
        const q = Math.max(0, current + delta);
        const res = await fetch('{{ url('/cart/update') }}', {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'}, body: JSON.stringify({id:id, quantity:q})});
        if(res.ok) location.reload();
    }
    if(e.target.classList.contains('js-remove')){
        const id = e.target.dataset.id;
        const res = await fetch('{{ url('/cart/remove') }}', {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'}, body: JSON.stringify({id:id})});
        if(res.ok) location.reload();
    }
});
</script>
@endpush
@endsection


