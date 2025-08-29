@extends('admin.layout.layout')
@section('content')
<main class="app-main">
  <!--begin::App Content Header-->
  <div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
      <!--begin::Row-->
      <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Gestion de categorie</h3></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
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
        <div class="col-md-8">
          <!--begin::Quick Example-->
          <div class="card card-primary card-outline mb-4">
            <!--begin::Header-->
            <div class="card-header"><div class="card-title">{{$title}}</div></div>
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
                  <strong>Error!</strong> {!! $error !!}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              @endforeach
            <!--begin::Form-->
                <form name="categoryForm" id="categoryForm" action="{{ isset($category->id) ? route('categories.update', $category->id) : route('categories.store') }}" method="post" enctype="multipart/form-data">@csrf
                  @if(isset($category->id)) @method('PUT') @endif
                  <div class="card-body">
                        <div class="mb-3">
                          <label for="category_name">Niveau de catégorie*</label>
                          <select name="parent_id" class="form-control">
                              <option value="">Select</option>
                              <option value="" @if($category->parent_id == null) selected @endif>Catégorie principale</option>
                              @foreach($getCategories as $cat)
                                  <option value="{{ $cat['id'] }}" @if(isset($category['parent_id']) && $category['parent_id'] == $cat['id']) selected @endif>
                                      {{ $cat['name'] }}
                                  </option>
                                  @if(!empty($cat['subcategories']))
                                      @foreach($cat['subcategories'] as $subcat)
                                          <option value="{{ $subcat['id'] }}" @if(isset($category['parent_id']) && $category['parent_id'] == $subcat['id']) selected @endif>
                                              &nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo;{{ $subcat['name'] }}
                                          </option> 
                                          @if(!empty($subcat['subcategories']))
                                              @foreach($subcat['subcategories'] as $subsubcat)
                                                  <option value="{{ $subsubcat['id'] }}" @if(isset($category['parent_id']) && $category['parent_id'] == $subsubcat['id']) selected @endif>
                                                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo;{{ $subsubcat['name'] }}
                                                  </option> 
                                              @endforeach
                                          @endif
                                      @endforeach
                                  @endif
                              @endforeach
                          </select>
                      </div>
                      <div class="mb-3">
                          <label class="form-label" for="category_name">Nom de catégorie*</label>
                          <input type="text" class="form-control" id="category_name" name="category_name"
                                 placeholder="Enter Category Name"
                                 value="{{ old('category_name', $category->name ?? '') }}">
                      </div>
                      <div class="mb-3">
                          <label class="form-label" for="category_image">Image de catégorie</label>
                          <input type="file" class="form-control" id="category_image" name="category_image" accept="image/*">
                          @if(!empty($category->image))
                              <div class="mt-2" id="categoryImageBlock">
                                  <a target="_blank" href="{{ url('front/images/categories/' . $category->image) }}"><img src="{{ asset('front/images/categories/' . $category->image) }}" width="50" alt="Category Image"></a>
                                  <a href="javascript:void(0);" id="deleteCategoryImage" data-category-id="{{ $category->id }}" class="text-danger">Supprimer</a>
                              </div>
                          @endif
                      </div>
                      <div class="mb-3">
                          <label class="form-label" for="size_chart">Tableau des tailles</label>
                          <input type="file" class="form-control" id="size_chart" name="size_chart" accept="image/*">
                          @if(!empty($category->size_chart))
                              <div class="mt-2" id="sizechartImageBlock">
                                  <a target="_blank" href="{{ url('front/images/sizecharts/' . $category->size_chart) }}"><img src="{{ asset('front/images/sizecharts/' . $category->size_chart) }}" width="50" alt="Size Chart"></a>
                                  <a href="javascript:void(0);" id="deleteSizeChartImage" data-category-id="{{ $category->id }}" class="text-danger">Supprimer</a>
                              </div>
                          @endif
                      </div>
                      <div class="mb-3">
                          <label class="form-label" for="category_discount">Remise de catégorie</label>
                          <input type="text" class="form-control" id="category_discount" placeholder="Enter Category Discount"
                                 name="category_discount" value="{{ old('category_discount', $category->discount ?? '') }}">
                      </div>
                      <div class="mb-3">
                          <label class="form-label" for="url">URL de catégorie*</label>
                          <input type="text" class="form-control" id="url" name="url" placeholder="Entrer URL de catégorie"
                                 value="{{ old('url', $category->url ?? '') }}">
                      </div>
                      <div class="mb-3">
                          <label class="form-label" for="description">Description de la catégorie*</label>
                          <textarea class="form-control" rows="3" id="description" name="description"
                                    placeholder="Enter Description">{{ old('description', $category->description ?? '') }}</textarea>
                      </div>
                      <div class="mb-3">
                          <label class="form-label" for="meta_title">Titre Meta</label>
                          <input type="text" class="form-control" id="meta_title" name="meta_title" placeholder="Enter Meta Title"
                                 value="{{ old('meta_title', $category->meta_title ?? '') }}">
                      </div>
                      <div class="mb-3">
                          <label class="form-label" for="meta_description">Meta Description</label>
                          <input type="text" class="form-control" id="meta_description" name="meta_description" placeholder="Entrer la Meta Description"
                                 value="{{ old('meta_description', $category->meta_description ?? '') }}">
                      </div>
                      <div class="mb-3">
                          <label class="form-label" for="meta_keywords">Meta Keywords</label>
                          <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" placeholder="Enter Meta Keywords"
                                 value="{{ old('meta_keywords', $category->meta_keywords ?? '') }}">
                      </div>
                      <div class="mb-3">
                          <label for="menu_status">Afficher dans le menu principal</label><br>
                          <input type="checkbox" name="menu_status" value="1" {{ !empty($category->menu_status) ? 'checked' : '' }}>
                      </div>
                  </div>
                  <div class="card-footer">
                      <button type="submit" class="btn btn-primary">Soumettre</button>
                  </div>
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