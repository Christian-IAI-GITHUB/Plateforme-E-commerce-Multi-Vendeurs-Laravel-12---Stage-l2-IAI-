@extends('admin.layout.layout')
@section('content')
<main class="app-main">
  <!--begin::App Content Header-->
  <div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
      <!--begin::Row-->
      <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Gestion Administrative</h3></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="/">Acceill</a></li>
            <li class="breadcrumb-item active" aria-current="page">Modifier Détails</li>
          </ol>
        </div>
      </div>
      <!--end::Row-->
    </div>
    <!--end::Container-->
  </div>
  <!--end::App Content Header-->
  <!--begin::App Content-->
  <div class="app-content">
    <!--begin::Container-->
    <div class="container-fluid">
      <!--begin::Row-->
      <div class="row g-4">
        <!--begin::Col-->
        <div class="col-md-6">
          <!--begin::Quick Example-->
          <div class="card card-primary card-outline mb-4">
            <!--begin::Header-->
            <div class="card-header"><div class="card-title">Modifier Détails</div></div>
            <!--end::Header-->
              @if(Session::has('error_message'))
              <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                  <strong>Error: </strong> {{ Session::get('error_message') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              @endif
                @if(Session::has('success_message'))
              <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                  <strong>Success: </strong> {{ Session::get('success_message') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              @endif
                @foreach($errors->all() as $error)
              <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                  <strong>Erreur!</strong> {!! $error !!}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              @endforeach
            <!--begin::Form-->
              <form method="post" action="{{ route('admin.update-details.request') }}" enctype="multipart/form-data">@csrf 
                <!--begin::Body-->
                <div class="card-body">
                    <div class="mb-3"> 
                        <label for="email" class="form-label">Email address</label> 
                        <input type="email" class="form-control" id="email" value="{{ Auth::guard('admin')->user()->email }}" readonly style="background-color: #ccc;">
                    </div>
                    <div class="mb-3"> 
                        <label for="name" class="form-label">Name</label> 
                        <input type="text" class="form-control" id="name" name="name" value="{{ Auth::guard('admin')->user()->name }}">
                    </div>
                    <div class="mb-3"> 
                        <label for="mobile" class="form-label">Mobile</label> 
                        <input type="text" class="form-control" id="mobile" name="mobile" value="{{ Auth::guard('admin')->user()->mobile }}">
                    </div>
                      <div class="mb-3">  
                        <label for="image" class="form-label">Image</label>  
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">  
                        @if(!empty(Auth::guard('admin')->user()->image))  
                            <div id="profileImageBlock">
                                <a target="_blank" href="{{ url('admin/images/photos/'.Auth::guard('admin')->user()->image) }}">Afficher</a> |  
                                <input type="hidden" name="current_image" value="{{ Auth::guard('admin')->user()->image }}">  
                                <a href="javascript:void(0);" id="deleteProfileImage" data-admin-id="{{ Auth::guard('admin')->user()->id }}" class="text-danger">Supprimer</a>  
                            </div>  
                        @endif  
                    </div>

                </div>
                <!--end::Body--> 
                <!--begin::Footer-->
                <div class="card-footer"> 
                    <button type="submit" class="btn btn-primary">Soumettre</button>
                </div>
                <!--end::Footer-->
            </form>

            <!--end::Form-->
          </div>
          <!--end::Quick Example-->
          
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        
        <!--end::Col-->
      </div>
      <!--end::Row-->
    </div>
    <!--end::Container-->
  </div>
  <!--end::App Content-->
</main>
@endsection