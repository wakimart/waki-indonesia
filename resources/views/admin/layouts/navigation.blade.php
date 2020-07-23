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

@if(Gate::check('add-deliveryorder') || Gate::check('browse-deliveryorder'))
<li class="nav-item {{isset($menu_item_page) && $menu_item_page == 'deliveryorder'? 'active': '' }}">
  <a class="nav-link" data-toggle="collapse" href="#deliveryorder-dd" aria-expanded="{{isset($menu_item_page) && $menu_item_page == 'deliveryorder'? 'true': '' }}" aria-controls="deliveryorder-dd">
    <span class="menu-title">Registration</span>
    <i class="menu-arrow"></i>
    <i class="mdi mdi-calendar-text menu-icon"></i>
  </a>
  <div class="collapse {{isset($menu_item_page) && $menu_item_page == 'deliveryorder'? 'show': '' }}" id="deliveryorder-dd">
    <ul class="nav flex-column sub-menu">
      @if(Gate::check('add-deliveryorder'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'add_deliveryorder'? 'active': '' }}" href="{{ route('add_deliveryorder')}}">Add Registration</a></li>
      @endif
      @if(Gate::check('browse-deliveryorder'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_deliveryorder'? 'active': '' }}" href="{{ route('list_deliveryorder') }}">List Registration</a></li>
      @endif
    </ul>
  </div>
</li>
@endif

@if(Gate::check('add-order') || Gate::check('browse-order'))
<li class="{{isset($menu_item_page) && $menu_item_page == 'order'? 'active': '' }} nav-item">
  <a class="nav-link" data-toggle="collapse" href="#order-dd" aria-expanded="false" aria-controls="order-dd">
    <span class="menu-title">Order</span>
    <i class="menu-arrow"></i>
    <i class="mdi mdi-calendar-text menu-icon"></i>
  </a>
  <div class="collapse {{isset($menu_item_page) && $menu_item_page == 'order'? 'show': '' }}" id="order-dd">
    <ul class="nav flex-column sub-menu">
      @if(Gate::check('add-order'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'add_order'? 'active': '' }}" href="{{ route('admin_add_order')}}">Add Order</a></li>
      @endif
      @if(Gate::check('browse-order'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_order'? 'active': '' }}" href="{{  route('admin_list_order') }}">List Order</a></li>
      @endif
    </ul>
  </div>
</li>
@endif

@if(Gate::check('add-home_service') || Gate::check('browse-home_service'))
<li class="{{isset($menu_item_page) && $menu_item_page == 'homeservice'? 'active': '' }} nav-item">
  <a class="nav-link" data-toggle="collapse" href="#homeservice-dd" aria-expanded="false" aria-controls="homeservice-dd">
    <span class="menu-title">Home Service</span>
    <i class="menu-arrow"></i>
    <i class="mdi mdi-calendar-text menu-icon"></i>
  </a>
  <div class="collapse {{isset($menu_item_page) && $menu_item_page == 'homeservice'? 'show': '' }}" id="homeservice-dd">
    <ul class="nav flex-column sub-menu">
      @if(Gate::check('add-home_service'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'add_homeservice' ? 'active': '' }}" href="{{ route('admin_add_homeService')}}">Add Home Service</a></li>
      @endif
      @if(Gate::check('browse-home_service'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_homeservice' ? 'active': '' }}" href="{{  route('admin_list_homeService') }}">List Home Service</a></li>
      @endif
    </ul>
  </div>
</li>
@endif

@if(Gate::check('add-cso') || Gate::check('browse-cso'))
<li class="{{isset($menu_item_page) && $menu_item_page == 'cso'? 'active': '' }} nav-item">
  <a class="nav-link" data-toggle="collapse" href="#cso-dd" aria-expanded="false" aria-controls="cso-dd">
    <span class="menu-title">CSO</span>
    <i class="menu-arrow"></i>
    <i class="mdi mdi-account-multiple menu-icon"></i>
  </a>
  <div class="collapse {{isset($menu_item_page) && $menu_item_page == 'cso'? 'show': '' }}" id="cso-dd">
    <ul class="nav flex-column sub-menu">
      @if(Gate::check('add-cso'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'add_cso'? 'active': '' }}" href="{{route('add_cso')}}">Add CSO</a></li>
      @endif
      @if(Gate::check('browse-cso'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_cso'? 'active': '' }}" href="{{route('list_cso')}}">List CSO</a></li>
      @endif
    </ul>
  </div>
</li>
@endif

@if(Gate::check('add-branch') || Gate::check('browse-branch'))
<li class="{{isset($menu_item_page) && $menu_item_page == 'branch'? 'active': '' }} nav-item">
  <a class="nav-link" data-toggle="collapse" href="#branch-dd" aria-expanded="false" aria-controls="branch-dd">
    <span class="menu-title">Branch</span>
    <i class="menu-arrow"></i>
    <i class="mdi mdi-source-branch menu-icon"></i>
  </a>
  <div class="collapse {{isset($menu_item_page) && $menu_item_page == 'branch'? 'show': '' }}" id="branch-dd">
    <ul class="nav flex-column sub-menu">
      @if(Gate::check('add-branch'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'add_branch'? 'active': '' }}" href="{{route('add_branch')}}">Add Branch</a></li>
      @endif
      @if(Gate::check('browse-branch'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_branch'? 'active': '' }}" href="{{route('list_branch')}}">List Branch</a></li>
      @endif
    </ul>
  </div>
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


{{-- <li class="nav-item">
  <a class="nav-link" data-toggle="collapse" href="#rekap-dd" aria-expanded="false" aria-controls="rekap-dd">
    <span class="menu-title">Rekap Data</span>
    <i class="menu-arrow"></i>
    <i class="mdi mdi-content-paste menu-icon"></i>
  </a>
  <div class="collapse" id="rekap-dd">
    <ul class="nav flex-column sub-menu">

      <li class="nav-item"> <a class="nav-link" href="#">Daftar Produk</a></li>


      <li class="nav-item"> <a class="nav-link"href="#">Produk Terjual</a></li>

    </ul>
  </div>
</li> --}}


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
