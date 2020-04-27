<nav class="sidebar sidebar-offcanvas" id="sidebar">
	<ul class="nav">
		<li class="nav-item nav-profile">
      <a href="#" class="nav-link">
        <div class="nav-profile-image">
          <img src="{{asset('sources/testface1.jpg')}}" alt="profile">
      			<span class="login-status online"></span>
      			<!--change to offline or busy as needed-->
        </div>
        <div class="nav-profile-text d-flex flex-column">
          	<span class="font-weight-bold mb-2">Jatmiko Gunawan</span>
          	<span class="text-secondary text-small">Front-End Developer</span>
        </div>
        <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
      </a>
    </li>
  	<li class="nav-item">
  		<a class="nav-link" href="index.html">
    		<span class="menu-title">Dashboard</span>
    		<i class="mdi mdi-home menu-icon"></i>
  		</a>
  	</li>
  	<li class="nav-item">
  		<a class="nav-link" href="frontcms.html">
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
          <li class="nav-item"> <a class="nav-link" href="add_do.html">Tambah DO</a></li>
          <li class="nav-item"> <a class="nav-link" href="list_do.html">Daftar DO</a></li>
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
          <li class="nav-item"> <a class="nav-link" href="add_cso.html">Tambah CSO</a></li>
          <li class="nav-item"> <a class="nav-link" href="list_cso.html">Daftar CSO</a></li>
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
          <li class="nav-item"> <a class="nav-link" href="add_branch.html">Tambah Branch</a></li>
          <li class="nav-item"> <a class="nav-link" href="list_branch.html">Daftar Branch</a></li>
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
          <li class="nav-item"> <a class="nav-link" href="add_kategori.html">Tambah Kategori</a></li>
          <li class="nav-item"> <a class="nav-link" href="list_kategori.html">Daftar Kategori</a></li>
        </ul>
      </div>
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#produk-dd" aria-expanded="false" aria-controls="produk-dd">
        <span class="menu-title">Produk</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-package menu-icon"></i>
      </a>
      <div class="collapse" id="produk-dd">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="add_produk.html">Tambah Produk</a></li>
          <li class="nav-item"> <a class="nav-link" href="list_produk.html">Daftar Produk</a></li>
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
          <li class="nav-item"> <a class="nav-link" href="add_promo.html">Tambah Promo</a></li>
          <li class="nav-item"> <a class="nav-link" href="list_promo.html">Daftar Promo</a></li>
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
          <li class="nav-item"> <a class="nav-link" href="add_admin.html">Tambah Admin</a></li>
          <li class="nav-item"> <a class="nav-link" href="list_admin.html">Daftar Admin</a></li>
        </ul>
      </div>
    </li>
	</ul>
</nav>