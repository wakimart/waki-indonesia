<nav class="sidebar sidebar-offcanvas" id="sidebar">
	<ul class="nav">
		<li class="nav-item nav-profile">
      <a href="#" class="nav-link">
        <div class="nav-profile-image">
          <img src="{{asset('sources/favicon.png')}}" alt="profile">
      			<span class="login-status online"></span>
      			<!--change to offline or busy as needed-->
        </div>
        <div class="nav-profile-text d-flex flex-column">
          	<span class="font-weight-bold mb-2">{{ Auth::user()->name }}</span>
          	<span class="text-secondary text-small">Admin WAKI Indonesia</span>
        </div>
        <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
      </a>
    </li>
  	<li class="nav-item">
  		<a class="nav-link" href="{{ route('dashboard')}}">
    		<span class="menu-title">Dashboard</span>
    		<i class="mdi mdi-home menu-icon"></i>
  		</a>
  	</li>
  	<li class="nav-item">
  		<a class="nav-link" href="{{  route('frontend_cms') }}">
    		<span class="menu-title">Front-End CMS</span>
    		<i class="mdi mdi-format-float-left menu-icon"></i>
  		</a>
  	</li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#deliveryorder-dd" aria-expanded="false" aria-controls="deliveryorder-dd">
        <span class="menu-title">Delivery Order (DO)</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-calendar-text menu-icon"></i>
      </a>
      <div class="collapse" id="deliveryorder-dd">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{ route('add_deliveryorder')}}">Add Delivery Order</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{  route('list_deliveryorder') }}">List Delivery Order</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#order-dd" aria-expanded="false" aria-controls="order-dd">
        <span class="menu-title">Order</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-calendar-text menu-icon"></i>
      </a>
      <div class="collapse" id="order-dd">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{ route('admin_add_order')}}">Add Order</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{  route('admin_list_order') }}">List Order</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#cso-dd" aria-expanded="false" aria-controls="cso-dd">
        <span class="menu-title">CSO</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-account-multiple menu-icon"></i>
      </a>
      <div class="collapse" id="cso-dd">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{route('add_cso')}}">Add CSO</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('list_cso')}}">List CSO</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#branch-dd" aria-expanded="false" aria-controls="branch-dd">
        <span class="menu-title">Branch</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-source-branch menu-icon"></i>
      </a>
      <div class="collapse" id="branch-dd">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{route('add_branch')}}">Add Branch</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('list_branch')}}">List Branch</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#kategori-dd" aria-expanded="false" aria-controls="kategori-dd">
        <span class="menu-title">Kategori</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-filter-variant menu-icon"></i>
      </a>
      <div class="collapse" id="kategori-dd">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{route('add_category')}}">Add Kategori</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('list_category')}}">List Kategori</a></li>
        </ul>
      </div>
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#produk-dd" aria-expanded="false" aria-controls="produk-dd">
        <span class="menu-title">Product</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-package menu-icon"></i>
      </a>
      <div class="collapse" id="produk-dd">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{route('add_product')}}">Add Product</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('list_product')}}">List Product</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#promo-dd" aria-expanded="false" aria-controls="promo-dd">
        <span class="menu-title">Promo</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-gift menu-icon"></i>
      </a>
      <div class="collapse" id="promo-dd">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{route('add_promo')}}">Add Promo</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('list_promo')}}">List Promo</a></li>
        </ul>
      </div>
    </li>
    </li>
    <li class="nav-item">
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
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#admin-dd" aria-expanded="false" aria-controls="admin-dd">
        <span class="menu-title">Admin</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-account menu-icon"></i>
      </a>
      <div class="collapse" id="admin-dd">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="add_admin.html">Add Admin</a></li>
          <li class="nav-item"> <a class="nav-link" href="list_admin.html">List Admin</a></li>
        </ul>
      </div>
    </li>
	</ul>
</nav>