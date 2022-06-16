@if(Gate::check('show-dashboard'))
<li class="{{isset($menu_item_page) && $menu_item_page == 'dashboard'? 'active': '' }} nav-item">
	<a class="nav-link" href="{{ route('dashboard')}}">
		<span class="menu-title">Dashboard</span>
		<i class="mdi mdi-home menu-icon"></i>
	</a>
</li>
@endif

@if(Gate::check('browse-frontendcms'))
<li class="{{isset($menu_item_page) && $menu_item_page == 'index_frontendcms'? 'active': '' }} nav-item">
	<a class="nav-link" href="{{ route('add_album') }}">
		<span class="menu-title">Front-End CMS</span>
		<i class="mdi mdi-format-float-left menu-icon"></i>
	</a>
</li>
@endif

@if(Gate::check('add-deliveryorder') || Gate::check('browse-deliveryorder'))
<li class="nav-item {{isset($menu_item_page) && $menu_item_page == 'registerevent'? 'active': '' }}">
  <a class="nav-link" data-toggle="collapse" href="#registerevent-dd" aria-expanded="{{isset($menu_item_page) && $menu_item_page == 'registerevent'? 'true': '' }}" aria-controls="registerevent-dd">
    <span class="menu-title">Registration Event</span>
    <i class="menu-arrow"></i>
    <i class="mdi mdi-calendar-text menu-icon"></i>
  </a>
  <div class="collapse {{isset($menu_item_page) && $menu_item_page == 'registerevent'? 'show': '' }}" id="registerevent-dd">
    <ul class="nav flex-column sub-menu">
      @if(Gate::check('add-deliveryorder'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'add_deliveryorder'? 'active': '' }}" href="{{ route('add_regispromo')}}">Add Registration</a></li>
      @endif
      @if(Auth::user()->roles[0]['slug'] == 'head-admin')
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_regispromo'? 'active': '' }}" href="{{ route('list_regispromo') }}">List Registration Promotion</a></li>
      @endif
    </ul>
  </div>
</li>
@endif

@if(Gate::check('add-order') || Gate::check('browse-order')
  || Gate::check('browse-order_report') || Gate::check('browse-order_report_branch') || Gate::check('browse-order_report_cso'))
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
@if(Gate::check('browse-order_report') || Gate::check('browse-order_report_branch') || Gate::check('browse-order_report_cso'))
<li class="{{isset($menu_item_page_sub) && $menu_item_page_sub == 'order_report'? 'active': '' }} nav-item">
	<a class="nav-link" data-toggle="collapse" data-parent="#order-dd" href="#orderreport-dd" aria-expanded="false" aria-controls="orderreport-dd">
		<span class="menu-title">Order Report</span>
		<i class="menu-arrow"></i>
		<i class="mdi mdi-calendar-text menu-icon"></i>
	</a>
	<div class="collapse {{isset($menu_item_page_sub) && $menu_item_page_sub == 'order_report'? 'show': '' }}" id="orderreport-dd">
		<ul class="nav flex-column sub-menu">
			@if(Gate::check('browse-order_report'))
			<li class="nav-item"> <a class="nav-link {{isset($menu_item_second_sub) && $menu_item_second_sub == 'list_order_report'? 'active': '' }}" href="{{  route('admin_list_order_report') }}">List Order Report</a></li>
			@endif
			@if(Gate::check('browse-order_report_branch'))
			<li class="nav-item"> <a class="nav-link {{isset($menu_item_second_sub) && $menu_item_second_sub == 'list_order_report_branch'? 'active': '' }}" href="{{  route('admin_list_order_report_branch') }}">List Order Report By Branch</a></li>
			@endif
			@if(Gate::check('browse-order_report_cso'))
			<li class="nav-item"> <a class="nav-link {{isset($menu_item_second_sub) && $menu_item_second_sub == 'list_order_report_cso'? 'active': '' }}" href="{{  route('admin_list_order_report_cso') }}">List Order Report By CSO</a></li>
			@endif
		</ul>
	</div>
</li>
@endif

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

@if(Gate::check('add-phc-product') || Gate::check('browse-phc-product') || Gate::check('add-personal-homecare') || Gate::check('browse-personal-homecare'))
<li class="{{isset($menu_item_page) && $menu_item_page == 'personal_homecare'? 'active': '' }} nav-item">
  <a class="nav-link" data-toggle="collapse" href="#personalhomecare-dd" aria-expanded="false" aria-controls="personalhomecare-dd">
    <span class="menu-title">Personal Homecare</span>
    <i class="menu-arrow"></i>
    <i class="mdi mdi-calendar-text menu-icon"></i>
  </a>
  <div class="collapse {{isset($menu_item_page) && $menu_item_page == 'personal_homecare'? 'show': '' }}" id="personalhomecare-dd">
    <ul class="nav flex-column sub-menu">
      @if(Gate::check('add-phc-product'))
      <li class="nav-item">
        <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'add_phc_product'? 'active': '' }}"
          href="{{ route('add_phc_product')}}">
          Add Product
        </a>
      </li>
      @endif
      @if(Gate::check('browse-phc-product'))
      <li class="nav-item">
        <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_product'? 'active': '' }}"
          href="{{  route('list_phc_product') }}">
          List Product
        </a>
      </li>
      @endif
      @if(Gate::check('browse-personal-homecare'))
      <li class="nav-item">
        <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_approved'? 'active': '' }}"
          href="{{  route('list_approved_phc') }}">
          List Approve Out Product
        </a>
      </li>
      @endif
      @if(Gate::check('add-personal-homecare'))
      <li class="nav-item">
        <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'add_personal_homecare'? 'active': '' }}"
          href="{{  route('add_personal_homecare') }}">
          Add Personal Homecare
        </a>
      </li>
      @endif
      @if(Gate::check('browse-personal-homecare'))
      <li class="nav-item">
        <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_all'? 'active': '' }}"
          href="{{  route('list_all_phc') }}">
          List All Personal Homecare
        </a>
      </li>
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

      @if(Auth::user()->roles[0]['slug'] == 'head-admin')
        <li class="nav-item">
            <a class="nav-link {{ isset($menu_item_second) && $menu_item_second == "track_homeservice" ? "active" : "" }}"
                href="{{  route("track_homeservice") }}">
                Track Home Service
            </a>
        </li>
      @endif
    </ul>
  </div>
</li>
@endif

@if(Gate::check('add-service') || Gate::check('browse-service'))
<li class="{{isset($menu_item_page) && $menu_item_page == 'service'? 'active': '' }} nav-item">
  <a class="nav-link" data-toggle="collapse" href="#service-dd" aria-expanded="false" aria-controls="service-dd">
    <span class="menu-title">Service</span>
    <i class="menu-arrow"></i>
    <i class="mdi mdi-calendar-text menu-icon"></i>
  </a>
  <div class="collapse {{isset($menu_item_page) && $menu_item_page == 'service'? 'show': '' }}" id="service-dd">
    <ul class="nav flex-column sub-menu">
      @if(Gate::check('add-service'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'add_service'? 'active': '' }}" href="{{ route('add_service')}}">Add Service</a></li>
      @endif
      @if(Gate::check('browse-service'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_service'? 'active': '' }}" href="{{  route('list_service') }}">List Service</a></li>
      @endif
      @if (Gate::check('add-sparepart'))
      <li class="nav-item">
        <a class="nav-link {{ isset($menu_item_second) && $menu_item_second == 'add_sparepart'? 'active': '' }}"
          href="{{ route('add_sparepart')}}">
          Add Sparepart
        </a>
      </li>
      @endif
      @if (Gate::check('browse-sparepart'))
      <li class="nav-item">
        <a class="nav-link {{ isset($menu_item_second) && $menu_item_second == 'list_sparepart'? 'active': '' }}"
          href="{{  route('list_sparepart') }}">
          List Sparepart
        </a>
      </li>
      @endif
    </ul>
  </div>
</li>
@endif


@if(Gate::check('add-service') || Gate::check('browse-service') || Gate::check('add-technician_schedule') || Gate::check('browse-technician_schedule'))
<li class="{{isset($menu_item_page) && $menu_item_page == 'technician'? 'active': '' }} nav-item">
  <a class="nav-link" data-toggle="collapse" href="#technician-dd" aria-expanded="false" aria-controls="technician-dd">
    <span class="menu-title">Technician</span>
    <i class="menu-arrow"></i>
    <i class="mdi mdi-calendar-text menu-icon"></i>
  </a>
  <div class="collapse {{isset($menu_item_page) && $menu_item_page == 'technician'? 'show': '' }}" id="technician-dd">
    <ul class="nav flex-column sub-menu">
      @if(Gate::check('add-service'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_task'? 'active': '' }}" href="{{ route('list_taskservice')}}">List Product Service/Upgrade </a></li>
      @endif
      @if(Gate::check('add-technician_schedule'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'add_technician_schedule'? 'active': '' }}" href="{{ route('add_technician_schedule')}}">Add Schedule </a></li>
      @endif
      @if(Gate::check('browse-technician_schedule'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_technician_schedule'? 'active': '' }}" href="{{ route('list_technician_schedule')}}">List Schedule </a></li>
      @endif
    </ul>
  </div>
</li>
@endif

@if (Gate::check('add-submission') || Gate::check('browse-submission') || Gate::check('browse-reference'))
<li class="nav-item {{ isset($menu_item_page) && $menu_item_page == 'submission' ? 'active': '' }}">
    <a class="nav-link"
        data-toggle="collapse"
        href="#submission-dd"
        aria-expanded="{{ isset($menu_item_page) && $menu_item_page == 'submission' ? 'true': '' }}"
        aria-controls="submission-dd">
        <span class="menu-title">Submission Registration</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-calendar-text menu-icon"></i>
    </a>
    <div class="collapse {{ isset($menu_item_page) && $menu_item_page == 'submission' ? 'show' : '' }}"
        id="submission-dd">
        <ul class="nav flex-column sub-menu">
        @if (Gate::check('add-submission'))
            <li class="nav-item">
                <a class="nav-link {{ isset($menu_item_second) && $menu_item_second == 'add_submission_mgm' ? 'active' : '' }}"
                    href="{{ route('add_submission_mgm')}}">
                    Add Submmission - MGM
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ isset($menu_item_second) && $menu_item_second == 'add_submission_reference' ? 'active' : '' }}"
                    href="{{ route('add_submission_reference')}}">
                    Add Submmission - Referensi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ isset($menu_item_second) && $menu_item_second == 'add_submission_takeaway' ? 'active' : '' }}"
                    href="{{ route('add_submission_takeaway')}}">
                    Add Submmission - Takeaway
                </a>
            </li>
        @endif
        @if (Gate::check('browse-submission'))
            <li class="nav-item">
                <a class="nav-link {{ isset($menu_item_second) && $menu_item_second == 'list_submission_form_mgm' ? 'active' : '' }}"
                    href="{{ route('list_submission_form', ["filter_type" => "mgm"]) }}">
                    List Submission - MGM
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ isset($menu_item_second) && $menu_item_second == 'list_submission_form_referensi' ? 'active' : '' }}"
                    href="{{ route('list_submission_form', ["filter_type" => "referensi"]) }}">
                    List Submission - Referensi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ isset($menu_item_second) && $menu_item_second == 'list_submission_form_takeaway' ? 'active' : '' }}"
                    href="{{ route('list_submission_form', ["filter_type" => "takeaway"]) }}">
                    List Submission - Takeaway
                </a>
            </li>
        @endif
        @if (Gate::check('browse-reference'))
           {{--  <li class="nav-item">
                <a class="nav-link {{ isset($menu_item_second) && $menu_item_second == 'list_reference' ? 'active' : '' }}"
                    href="{{ route('list_reference') }}">
                    List Reference
                </a>
            </li> --}}
        @endif
        </ul>
    </div>
</li>
@endif

{{-- Khusus untuk Acc --}}
@if(Gate::check('add-acceptance') || Gate::check('browse-acceptance'))
<li class="nav-item {{isset($menu_item_page) && $menu_item_page == 'acceptance'? 'active': '' }}">
  <a class="nav-link" data-toggle="collapse" href="#acceptance-dd" aria-expanded="{{isset($menu_item_page) && $menu_item_page == 'acceptance'? 'true': '' }}" aria-controls="acceptance-dd">
    <span class="menu-title">ACC Upgrade Form</span>
    <i class="menu-arrow"></i>
    <i class="mdi mdi-calendar-text menu-icon"></i>
  </a>
  <div class="collapse {{isset($menu_item_page) && $menu_item_page == 'acceptance'? 'show': '' }}" id="acceptance-dd">
    <ul class="nav flex-column sub-menu">
      @if(Gate::check('add-acceptance'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'add_acceptance_form'? 'active': '' }}" href="{{ route('add_acceptance_form')}}">Add Acceptance</a></li>
      @endif
      @if(Gate::check('browse-acceptance'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_acceptance_form'? 'active': '' }}" href="{{ route('list_acceptance_form') }}?status=new">List Acceptance</a></li>
      @endif
    </ul>
  </div>
</li>
@endif

{{-- Khusus untuk Upgrade --}}
@if(Gate::check('browse-upgrade'))
<li class="nav-item {{isset($menu_item_page) && $menu_item_page == 'upgrade'? 'active': '' }}">
  <a class="nav-link" data-toggle="collapse" href="#upgrade-dd" aria-expanded="{{isset($menu_item_page) && $menu_item_page == 'upgrade'? 'true': '' }}" aria-controls="upgrade-dd">
    <span class="menu-title">Post Process Upgrade<br><b style="color: red">(Admin ONLY)</b></span>
    <i class="menu-arrow"></i>
    <i class="mdi mdi-calendar-text menu-icon"></i>
  </a>
  <div class="collapse {{isset($menu_item_page) && $menu_item_page == 'upgrade'? 'show': '' }}" id="upgrade-dd">
    <ul class="nav flex-column sub-menu">
      @if(Gate::check('browse-upgrade'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'new_upgrade_form'? 'active': '' }}" href="{{ route('list_new_upgrade_form')}}">New Upgrade</a></li>
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_upgrade_form'? 'active': '' }}" href="{{ route('list_upgrade_form') }}">List Upgrade</a></li>
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

@if(Gate::check('add-product') || Gate::check('browse-product'))
<li class="{{isset($menu_item_page) && $menu_item_page == 'stock'? 'active': '' }} nav-item">
  <a class="nav-link" data-toggle="collapse" href="#stock-dd" aria-expanded="false" aria-controls="stock-dd">
    <span class="menu-title">Stock</span>
    <i class="menu-arrow"></i>
    <i class="mdi mdi-package menu-icon"></i>
  </a>
  <div class="collapse {{isset($menu_item_page) && $menu_item_page == 'stock'? 'show': '' }}" id="stock-dd">
    <ul class="nav flex-column sub-menu">
      @if(Gate::check('browse-product'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_stock'? 'active': '' }}" href="{{route('list_stock')}}">List Stock</a></li>
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

@if(Gate::check('add-type_customer') || Gate::check('browse-type_customer'))
<li class="{{isset($menu_item_page) && $menu_item_page == 'type_customer'? 'active': '' }} nav-item">
  <a class="nav-link" data-toggle="collapse" href="#type_customer-dd" aria-expanded="false" aria-controls="type_customer-dd">
    <span class="menu-title">Type Customer</span>
    <i class="menu-arrow"></i>
    <i class="mdi mdi-package menu-icon"></i>
  </a>
  <div class="collapse {{isset($menu_item_page) && $menu_item_page == 'type_customer'? 'show': '' }}" id="type_customer-dd">
    <ul class="nav flex-column sub-menu">
      @if(Gate::check('add-type_customer'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'add_type_customer'? 'active': '' }}" href="{{route('add_type_customer')}}">Add Type Customer</a></li>
      @endif
      @if(Gate::check('browse-type_customer'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_type_customer'? 'active': '' }}" href="{{route('list_type_customer')}}">List Type Customer</a></li>
      @endif
    </ul>
  </div>
</li>
@endif

@if(Gate::check('add-data_sourcing') || Gate::check('browse-data_sourcing'))
<li class="{{isset($menu_item_page) && $menu_item_page == 'data_sourcing'? 'active': '' }} nav-item">
  <a class="nav-link" data-toggle="collapse" href="#data_sourcing-dd" aria-expanded="false" aria-controls="data_sourcing-dd">
    <span class="menu-title">Data Sourcing</span>
    <i class="menu-arrow"></i>
    <i class="mdi mdi-package menu-icon"></i>
  </a>
  <div class="collapse {{isset($menu_item_page) && $menu_item_page == 'data_sourcing'? 'show': '' }}" id="data_sourcing-dd">
    <ul class="nav flex-column sub-menu">
      @if(Gate::check('add-data_sourcing'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'add_data_sourcing'? 'active': '' }}" href="{{route('add_data_sourcing')}}">Add Data Sourcing</a></li>
      @endif
      @if(Gate::check('browse-data_sourcing'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_data_sourcing'? 'active': '' }}" href="{{route('list_data_sourcing')}}">List Data Sourcing</a></li>
      @endif
      @if(Auth::user()->roles[0]['slug'] == 'head-admin')
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'import_data_sourcing'? 'active': '' }}" href="{{route('import_data_sourcing')}}">Import Data</a></li>
      @endif
    </ul>
  </div>
</li>
@endif

@if(Gate::check('add-data_therapy') || Gate::check('browse-data_therapy'))
<li class="{{isset($menu_item_page) && $menu_item_page == 'data_therapy'? 'active': '' }} nav-item">
  <a class="nav-link" data-toggle="collapse" href="#data_therapy-dd" aria-expanded="false" aria-controls="data_therapy-dd">
    <span class="menu-title">Data Therapy</span>
    <i class="menu-arrow"></i>
    <i class="mdi mdi-package menu-icon"></i>
  </a>
  <div class="collapse {{isset($menu_item_page) && $menu_item_page == 'data_therapy'? 'show': '' }}" id="data_therapy-dd">
    <ul class="nav flex-column sub-menu">
      @if(Gate::check('add-data_therapy'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'add_data_therapy'? 'active': '' }}" href="{{route('add_data_therapy')}}">Add Data Therapy</a></li>
      @endif
      @if(Gate::check('browse-data_therapy'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_data_therapy'? 'active': '' }}" href="{{route('list_data_therapy')}}">List Data Therapy</a></li>
      @endif
    </ul>
  </div>
</li>
@endif

@if(Auth::user()->roles[0]['slug'] == 'head-admin')
<li class="{{isset($menu_item_page) && $menu_item_page == 'souvenir'? 'active': '' }} nav-item">
  <a class="nav-link" data-toggle="collapse" href="#souvenir-dd" aria-expanded="false" aria-controls="souvenir-dd">
    <span class="menu-title">Souvenir</span>
    <i class="menu-arrow"></i>
    <i class="mdi mdi-gift menu-icon"></i>
  </a>
  <div class="collapse {{isset($menu_item_page) && $menu_item_page == 'souvenir'? 'show': '' }}" id="souvenir-dd">
    <ul class="nav flex-column sub-menu">
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'add_souvenir'? 'active': '' }}" href="{{route('add_souvenir')}}">Add Souvenir</a></li>
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_souvenir'? 'active': '' }}" href="{{route('list_souvenir')}}">List Souvenir</a></li>
    </ul>
  </div>
</li>

<li class="{{isset($menu_item_page) && $menu_item_page == 'prize'? 'active': '' }} nav-item">
  <a class="nav-link" data-toggle="collapse" href="#prize-dd" aria-expanded="false" aria-controls="prize-dd">
    <span class="menu-title">Prize</span>
    <i class="menu-arrow"></i>
    <i class="mdi mdi-gift menu-icon"></i>
  </a>
  <div class="collapse {{isset($menu_item_page) && $menu_item_page == 'prize'? 'show': '' }}" id="prize-dd">
    <ul class="nav flex-column sub-menu">
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'add_prize'? 'active': '' }}" href="{{route('add_prize')}}">Add Prize</a></li>
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_prize'? 'active': '' }}" href="{{route('list_prize')}}">List Prize</a></li>
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

@if(Gate::check('add-app') || Gate::check('browse-app'))
<li class="{{isset($menu_item_page) && $menu_item_page == 'app'? 'active': '' }} nav-item">
  <a class="nav-link" data-toggle="collapse" href="#app-dd" aria-expanded="false" aria-controls="app-dd">
    <span class="menu-title">App Version</span>
    <i class="menu-arrow"></i>
    <i class="mdi mdi-account-multiple menu-icon"></i>
  </a>
  <div class="collapse {{isset($menu_item_page) && $menu_item_page == 'app'? 'show': '' }}" id="app-dd">
    <ul class="nav flex-column sub-menu">
      @if(Gate::check('add-app'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'add_appver'? 'active': '' }}" href="{{route('add_appVersion')}}">Add App Version</a></li>
      @endif
      @if(Gate::check('browse-app'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_appver'? 'active': '' }}" href="{{route('list_appVersion')}}">List App Version</a></li>
      @endif
    </ul>
  </div>
</li>
@endif
