@extends('front.layout.layout')

@section('content')
<div class="container py-4">
  <div class="row">
    <div class="col-lg-8">
      <div class="card mb-3">
        <div class="card-header bg-info text-white"><i class="fa fa-map-marker-alt mr-1"></i> Informations de livraison</div>
        <div class="card-body">
          <form method="POST" action="{{ url('/checkout/place') }}">
            @csrf
            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Nom complet *</label>
                <input type="text" class="form-control" name="customer_name" required>
              </div>
              <div class="form-group col-md-6">
                <label>Numéro de téléphone *</label>
                <input type="text" class="form-control" name="phone_number" required>
              </div>
            </div>
            <div class="form-group">
              <label>Adresse de livraison *</label>
              <textarea class="form-control" name="delivery_address" rows="3" required></textarea>
            </div>
            <div class="form-group">
              <label>Mode de paiement *</label>
              <div class="form-row">
                <div class="col-md-6 d-flex align-items-center">
                  <div class="custom-control custom-radio">
                    <input class="custom-control-input" type="radio" id="pm_flooz" name="payment_method" value="flooz" required>
                    <label class="custom-control-label d-flex align-items-center" for="pm_flooz">
                      <img src="{{ asset('front/images/logos/flooz.png') }}" alt="Flooz" class="pay-logo mr-2"> Flooz
                    </label>
                  </div>
                </div>
                <div class="col-md-6 d-flex align-items-center">
                  <div class="custom-control custom-radio">
                    <input class="custom-control-input" type="radio" id="pm_tmoney" name="payment_method" value="tmoney">
                    <label class="custom-control-label d-flex align-items-center" for="pm_tmoney">
                      <img src="{{ asset('front/images/logos/yas.png') }}" alt="TMoney" class="pay-logo mr-2"> TMoney
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-check mr-1"></i> Valider la commande</button>
          </form>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card">
        <div class="card-header bg-info text-white">Résumé de la commande</div>
        <div class="card-body">
          @foreach($items as $i)
            <div class="d-flex justify-content-between">
              <span>{{ $i->product->product_name ?? 'Produit' }} x {{ $i->quantity }}</span>
              <span>{{ number_format($i->total_price,0,',',' ') }} FCFA</span>
            </div>
          @endforeach
          <hr>
          <div class="d-flex justify-content-between"><strong>Total</strong><strong>{{ number_format($total,0,',',' ') }} FCFA</strong></div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@push('styles')
<style>
.pay-logo{width:32px;height:32px;object-fit:contain}
.custom-control-label .pay-logo{margin-right:.75rem}
.custom-control.custom-radio .custom-control-label{line-height:32px}
</style>
@endpush


