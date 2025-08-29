@extends('admin.layout.layout')
@section('content')
<main class="app-main">
  <div class="app-content p-3">
    <div class="container-fluid">
      <h3 class="mb-3">Commande #{{ $order->id }}</h3>
      <div class="row">
        <div class="col-md-6">
          <div class="card mb-3">
            <div class="card-header">Client</div>
            <div class="card-body">
              <div><strong>Nom: </strong>{{ $order->customer_name }}</div>
              <div><strong>Téléphone: </strong>{{ $order->phone_number }}</div>
              <div><strong>Adresse: </strong>{{ $order->delivery_address }}</div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card mb-3">
            <div class="card-header">Infos commande</div>
            <div class="card-body">
              <div><strong>Total: </strong>{{ number_format($order->total_amount,0,',',' ') }} FCFA</div>
              <div><strong>Statut: </strong>{{ ucfirst($order->order_status) }}</div>
              <div><strong>Payement: </strong>{{ strtoupper($order->payment_method) }}</div>
              <div><strong>Date: </strong>{{ optional($order->created_at)->format('d/m/Y H:i') }}</div>
            </div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header">Produits</div>
        <div class="table-responsive">
          <table class="table mb-0">
            <thead class="thead-light">
              <tr>
                <th>Produit</th>
                <th>Qté</th>
                <th class="text-right">PU</th>
                <th class="text-right">Total</th>
              </tr>
            </thead>
            <tbody>
              @foreach(($order->details ?? []) as $d)
              <tr>
                <td>{{ $d->product->product_name ?? 'Produit' }}</td>
                <td>{{ $d->quantity }}</td>
                <td class="text-right">{{ number_format($d->unit_price,0,',',' ') }} FCFA</td>
                <td class="text-right">{{ number_format($d->total_price,0,',',' ') }} FCFA</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</main>
@endsection


