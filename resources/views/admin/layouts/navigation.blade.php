@if(Auth::user()->roles[0]['slug'] == 'head-admin')
<li class="{{isset($menu_item_page) && $menu_item_page == 'dashboard'? 'active': '' }} nav-item">
	<a class="nav-link" href="{{ route('dashboard')}}">
		<span class="menu-title">Dashboard</span>
		<i class="mdi mdi-home menu-icon"></i>
	</a>
</li>
@endif

@if(Gate::check('browse-frontendcms'))
<li class="{{isset($menu_item_page) && $menu_item_page == 'index_frontendcms'? 'active': '' }} nav-item">
	<a class="nav-link" href="{{  route('index_frontendcms') }}">
		<span class="menu-title">Front-End CMS</span>
		<i class="mdi mdi-format-float-left menu-icon"></i>
	</a>
</li>
@endif


@if(Gate::check('add-category') || Gate::check('browse-category'))
<li class="{{isset($menu_item_page) && $menu_item_page == 'category'? 'active': '' }} nav-item">
  <a class="nav-link" data-toggle="collapse" href="#kategori-dd" aria-expanded="false" aria-controls="kategori-dd">
    <span class="menu-title">Category</span>
    <i class="menu-arrow"></i>
    <i class="mdi mdi-filter-variant menu-icon"></i>
  </a>
  <div class="collapse {{isset($menu_item_page) && $menu_item_page == 'category'? 'show': '' }}" id="kategori-dd">
    <ul class="nav flex-column sub-menu">
      @if(Gate::check('add-category'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'add_category'? 'active': '' }}" href="{{route('add_category')}}">Add Category</a></li>
      @endif
      @if(Gate::check('browse-category'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_category'? 'active': '' }}" href="{{route('list_category')}}">List Category</a></li>
      @endif
    </ul>
  </div>
</li>
@endif

@if(Gate::check('add-product') || Gate::check('browse-product'))
<li class="{{isset($menu_item_page) && $menu_item_page == 'product'? 'active': '' }} nav-item">
  <a class="nav-link" data-toggle="collapse" href="#produk-dd" aria-expanded="false" aria-controls="produk-dd">
    <span class="menu-title">Product</span>
    <i class="menu-arrow"></i>
    <i class="mdi mdi-package menu-icon"></i>
  </a>
  <div class="collapse {{isset($menu_item_page) && $menu_item_page == 'product'? 'show': '' }}" id="produk-dd">
    <ul class="nav flex-column sub-menu">
      @if(Gate::check('add-product'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'add_product'? 'active': '' }}" href="{{route('add_product')}}">Add Product</a></li>
      @endif
      @if(Gate::check('browse-product'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_product'? 'active': '' }}" href="{{route('list_product')}}">List Product</a></li>
      @endif
    </ul>
  </div>
</li>
@endif

@if(Gate::check('add-promo') || Gate::check('browse-promo'))
<li class="{{isset($menu_item_page) && $menu_item_page == 'promo'? 'active': '' }} nav-item">
  <a class="nav-link" data-toggle="collapse" href="#promo-dd" aria-expanded="false" aria-controls="promo-dd">
    <span class="menu-title">Promo</span>
    <i class="menu-arrow"></i>
    <i class="mdi mdi-gift menu-icon"></i>
  </a>
  <div class="collapse {{isset($menu_item_page) && $menu_item_page == 'promo'? 'show': '' }}" id="promo-dd">
    <ul class="nav flex-column sub-menu">
      @if(Gate::check('add-promo'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'add_promo'? 'active': '' }}" href="{{route('add_promo')}}">Add Promo</a></li>
      @endif
      @if(Gate::check('browse-promo'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_promo'? 'active': '' }}" href="{{route('list_promo')}}">List Promo</a></li>
      @endif
    </ul>
  </div>
</li>
@endif


@if(Gate::check('add-user') || Gate::check('browse-user'))
<li class="{{isset($menu_item_page) && $menu_item_page == 'user'? 'active': '' }} nav-item">
  <a class="nav-link" data-toggle="collapse" href="#admin-dd" aria-expanded="false" aria-controls="admin-dd">
    <span class="menu-title">Admin</span>
    <i class="menu-arrow"></i>
    <i class="mdi mdi-account menu-icon"></i>
  </a>
  <div class="collapse {{isset($menu_item_page) && $menu_item_page == 'user'? 'show': '' }}" id="admin-dd">
    <ul class="nav flex-column sub-menu">
      @if(Gate::check('add-user'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'add_user'? 'active': '' }}" href="{{route('add_useradmin')}}">Add Admin</a></li>
      @endif
      @if(Gate::check('browse-user'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_user'? 'active': '' }}" href="{{route('list_useradmin')}}">List Admin</a></li>
      @endif
    </ul>
  </div>
</li>
@endif
