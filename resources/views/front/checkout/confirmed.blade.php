@extends('front.layout.layout')

@section('content')
<div class="container py-4">
  <div class="card border-success mb-4">
    <div class="card-header bg-success text-white py-2">
      <strong><i class="fa fa-check-circle mr-1"></i> Commande confirmée !</strong>
    </div>
    <div class="card-body">
      <div class="text-center mb-3">
        <span class="rounded-circle d-inline-flex align-items-center justify-content-center bg-success text-white" style="width:64px;height:64px;">
          <i class="fa fa-check fa-lg"></i>
        </span>
      </div>
      <h5 class="text-center text-success font-weight-bold mb-1">Merci pour votre commande !</h5>
      <p class="text-center text-muted mb-4">Votre commande a été traitée avec succès et votre paiement a été confirmé.</p>

      <div class="row no-gutters mb-3">
        <div class="col-md-6 pr-md-2 mb-2 mb-md-0">
          <div class="border rounded p-3 h-100">
            <div class="text-muted small">Numéro de commande</div>
            <div class="h5 mb-0">#{{ $order->id }}</div>
          </div>
        </div>
        <div class="col-md-6 pl-md-2">
          <div class="border rounded p-3 h-100">
            <div class="text-muted small">Montant total</div>
            <div class="h5 mb-0">{{ number_format($order->total_amount,0,',',' ') }} FCFA</div>
          </div>
        </div>
      </div>

      <div class="table-responsive mb-3">
        <table class="table table-sm table-bordered mb-0">
          <thead class="thead-light">
            <tr>
              <th class="w-50">Produit</th>
              <th class="text-center" style="width:10%">Quantité</th>
              <th class="text-right" style="width:20%">Prix unitaire</th>
              <th class="text-right" style="width:20%">Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach(($order->details ?? []) as $detail)
              <tr>
                <td>{{ $detail->product->product_name ?? 'Produit' }}</td>
                <td class="text-center">{{ $detail->quantity }}</td>
                <td class="text-right">{{ number_format($detail->unit_price, 0, ',', ' ') }} FCFA</td>
                <td class="text-right">{{ number_format($detail->total_price, 0, ',', ' ') }} FCFA</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <div class="small text-muted font-weight-bold mb-1">Informations de livraison</div>
          <div><strong>Nom :</strong> {{ $order->customer_name }}</div>
          <div><strong>Téléphone :</strong> {{ $order->phone_number }}</div>
        </div>
        <div class="col-md-6">
          <div class="small text-muted font-weight-bold mb-1">Adresse :</div>
          <div>{{ $order->delivery_address }}</div>
        </div>
      </div>

      <div class="next-steps rounded p-3 mb-3">
        <div class="font-weight-bold mb-2 d-flex align-items-center">
          <span class="badge badge-success mr-2" style="font-size:90%"><i class="fa fa-bolt mr-1"></i>Important</span>
          Prochaines étapes
        </div>
        <ul class="mb-0 pl-4">
          <li>Vous recevrez un SMS de confirmation sur votre téléphone</li>
          <li>Notre équipe traitera votre commande dans les plus brefs délais</li>
          <li>Vous serez contacté pour confirmer la livraison</li>
        </ul>
      </div>

      <div class="d-flex justify-content-center">
        <a href="{{ url('/') }}" class="btn btn-secondary mr-2"><i class="fa fa-home mr-1"></i> Retour à l'accueil</a>
        <a href="{{ url('/') }}" class="btn btn-outline-secondary" role="button">Continuer les achats</a>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
.table thead th{white-space:nowrap}
.next-steps{background:#fff6e6;border:1px solid #ffd8a8}
.next-steps li{margin-bottom:6px}
</style>
@endpush


