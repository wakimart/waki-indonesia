@if(Gate::check('show-dashboard'))
<li class="{{isset($menu_item_page) && $menu_item_page == 'dashboard'? 'active': '' }} nav-item">
	<a class="nav-link" href="{{ route('dashboard')}}">
		<span class="menu-title">Dashboard</span>
		<i class="mdi mdi-home menu-icon"></i>
	</a>
</li>
@endif

@if(Gate::check('add-order') || Gate::check('browse-order') || Gate::check('browse-order_report') || Gate::check('browse-order_report_branch') || Gate::check('browse-order_report_cso'))
<li class="nav-item {{isset($menu_item_page) && $menu_item_page == 'order' || isset($menu_item_page) && $menu_item_page == 'order_report' ? 'active': '' }}">
  <a class="nav-link" data-toggle="collapse" href="#orderTrans-dd" aria-expanded="{{isset($menu_item_page) && $menu_item_page == 'order' || isset($menu_item_page) && $menu_item_page == 'order_report' ? 'true': '' }}" aria-controls="orderTrans-dd">
    <span class="menu-title">Order Transaction</span>
    <i class="menu-arrow"></i>
    <i class="mdi mdi-format-float-left menu-icon"></i>
  </a>
  <div class="collapse {{isset($menu_item_page) && $menu_item_page == 'order' || isset($menu_item_page) && $menu_item_page == 'order_report' ? 'show': '' }}" id="orderTrans-dd">
    <ul class="nav flex-column">

		@if(Gate::check('add-order') || Gate::check('browse-order'))
		<li class="nav-item {{isset($menu_item_page) && $menu_item_page == 'order'? 'active': '' }}">
		  <a class="nav-link" data-toggle="collapse" href="#order-dd" aria-expanded="false" aria-controls="order-dd">
		    <span class="menu-title">Order</span>
		    <i class="menu-arrow"></i>
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

		@if(Gate::check('browse-order_report') || Gate::check('browse-total_sale'))
		<li class="{{isset($menu_item_page_sub) && $menu_item_page_sub == 'order_report'? 'active': '' }} nav-item">
			<a class="nav-link" data-toggle="collapse" data-parent="#order-dd" href="#orderreport-dd" aria-expanded="false" aria-controls="orderreport-dd">
				<span class="menu-title">Order Report</span>
				<i class="menu-arrow"></i>
			</a>
			<div class="collapse {{isset($menu_item_page_sub) && $menu_item_page_sub == 'order_report'? 'show': '' }}" id="orderreport-dd">
				<ul class="nav flex-column sub-menu">
					@if(Gate::check('browse-order_report'))
					<li class="nav-item"> <a class="nav-link {{isset($menu_item_second_sub) && $menu_item_second_sub == 'list_order_report'? 'active': '' }}" href="{{  route('admin_list_order_report') }}">List Total Sale</a></li>
					@endif
					@if(Gate::check('browse-total_sale'))
					<li class="nav-item"> <a class="nav-link {{isset($menu_item_second_sub) && $menu_item_second_sub == 'list_total_sale'? 'active': '' }}" href="{{  route('list_total_sale') }}">List Order Report</a></li>
					@endif
				</ul>
			</div>
		</li>
		@endif

	</ul>
</div>
</li>
@endif

@if(Gate::check('add-financial_routine') || Gate::check('browse-financial_routine'))
<li class="nav-item {{isset($menu_item_page) && $menu_item_page == 'financial_routine'? 'active': '' }}">
  <a class="nav-link" data-toggle="collapse" href="#financial_routine-dd" aria-expanded="false" aria-controls="financial_routine-dd">
    <span class="menu-title">Financial Routine</span>
    <i class="menu-arrow"></i>
    <i class="mdi mdi-shape menu-icon"></i>
  </a>
  <div class="collapse {{isset($menu_item_page) && $menu_item_page == 'financial_routine'? 'show': '' }}" id="financial_routine-dd">
    <ul class="nav flex-column sub-menu">
      @if(Gate::check('add-financial_routine'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'add_financial_routine'? 'active': '' }}" href="{{ route('add_financial_routine')}}">Add by Bank</a></li>
      @endif
      @if(Gate::check('browse-financial_routine'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_financial_routine'? 'active': '' }}" href="{{ route('list_financial_routine') }}">List by Bank</a></li>
      @endif
      @if(Gate::check('add-financial_routine'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'add_financial_routine_branch'? 'active': '' }}" href="{{ route('add_financial_routine_branch')}}">Add by Branch</a></li>
      @endif
      @if(Gate::check('browse-financial_routine'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_financial_routine_branch'? 'active': '' }}" href="{{ route('list_financial_routine_branch') }}">List by Branch</a></li>
      @endif
    </ul>
  </div>
</li>
@endif

@if (Gate::check("add-petty_cash_in") || Gate::check("add-petty_cash_out") || Gate::check("browse-petty_cash"))
  <li class="{{isset($menu_item_page) && $menu_item_page === 'petty_cash' ? 'active' : '' }} nav-item">
    <a class="nav-link"
      data-toggle="collapse"
      href="#petty_cash-dd"
      aria-expanded="false"
      aria-controls="petty_cash-dd">
      <span class="menu-title">Petty Cash</span>
      <i class="menu-arrow"></i>
      <i class="mdi mdi-shape menu-icon"></i>
    </a>
    <div class="collapse {{ isset($menu_item_page) && $menu_item_page === 'petty_cash' ? 'show' : '' }}"
      id="petty_cash-dd">
      <ul class="nav flex-column sub-menu">
        @if (Gate::check("add-petty_cash_in"))
          <li class="nav-item">
            <a class="nav-link {{ isset($menu_item_second) && $menu_item_second === 'add_petty_cash_in' ? 'active' : '' }}"
              href="{{ route('add_petty_cash_in') }}">
              Petty Cash In
            </a>
          </li>
        @endif
        @if (Gate::check("add-petty_cash_out"))
          <li class="nav-item">
            <a class="nav-link {{ isset($menu_item_second) && $menu_item_second === 'add_petty_cash_out' ? 'active' : '' }}"
              href="{{ route('add_petty_cash_out') }}">
              Petty Cash Out
            </a>
          </li>
        @endif
        @if (Gate::check("browse-petty_cash"))
          <li class="nav-item">
            <a class="nav-link {{ isset($menu_item_second) && $menu_item_second === 'list_petty_cash' ? 'active' : '' }}"
              href="{{ route('list_petty_cash') }}">
              List Petty Cash
            </a>
          </li>
        @endif
        @if (Gate::check("add-posting_petty_cash"))
          <li class="nav-item">
            <a class="nav-link {{ isset($menu_item_second) && $menu_item_second === 'add_posting_petty_cash' ? 'active' : '' }}"
              href="{{ route('add_posting_petty_cash') }}">
              Posting Petty Cash
            </a>
          </li>
        @endif
      </ul>
    </div>
  </li>
@endif

@if (Gate::check("add-petty_cash_type") || Gate::check("browse-petty_cash_type"))
  <li class="{{isset($menu_item_page) && $menu_item_page === 'petty_cash_type' ? 'active' : '' }} nav-item">
    <a class="nav-link"
      data-toggle="collapse"
      href="#petty_cash_type-dd"
      aria-expanded="false"
      aria-controls="petty_cash_type-dd">
      <span class="menu-title">Petty Cash Type</span>
      <i class="menu-arrow"></i>
      <i class="mdi mdi-shape menu-icon"></i>
    </a>
    <div class="collapse {{ isset($menu_item_page) && $menu_item_page === 'petty_cash_type' ? 'show' : '' }}"
      id="petty_cash_type-dd">
      <ul class="nav flex-column sub-menu">
        @if (Gate::check("add-petty_cash_type"))
          <li class="nav-item">
            <a class="nav-link {{ isset($menu_item_second) && $menu_item_second === 'add_petty_cash_type' ? 'active' : '' }}"
              href="{{ route('add_petty_cash_type') }}">
              Add Petty Cash Type
            </a>
          </li>
        @endif
        @if (Gate::check("browse-petty_cash_type"))
          <li class="nav-item">
            <a class="nav-link {{ isset($menu_item_second) && $menu_item_second === 'list_petty_cash_type' ? 'active' : '' }}"
              href="{{ route('list_petty_cash_type') }}">
              List Petty Cash Type
            </a>
          </li>
        @endif
      </ul>
    </div>
  </li>
@endif

@if(Gate::check('add-deliveryorder') || Gate::check('browse-deliveryorder'))
{{-- <li class="nav-item {{isset($menu_item_page) && $menu_item_page == 'deliveryorder'? 'active': '' }}">
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
</li> --}}
@endif

@if(Gate::check('add-phc-product') || Gate::check('browse-phc-product') || Gate::check('add-personal-homecare') || Gate::check('browse-personal-homecare'))
{{-- <li class="{{isset($menu_item_page) && $menu_item_page == 'personal_homecare'? 'active': '' }} nav-item">
  <a class="nav-link" data-toggle="collapse" href="#personalhomecare-dd" aria-expanded="false" aria-controls="personalhomecare-dd">
    <span class="menu-title">Personal Homecare</span>
    <i class="menu-arrow"></i>
    <i class="mdi mdi-calendar-text menu-icon"></i>
  </a>
  <div class="collapse {{isset($menu_item_page) && $menu_item_page == 'personal_homecare'? 'show': '' }}" id="personalhomecare-dd">
    <ul class="nav flex-column sub-menu">
      @if(Gate::check('add-phc-product'))
      <li class="nav-item">
				<a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'add_financial_routine'? 'active': '' }}" href="{{ route('add_financial_routine')}}">
					Add by Bank
				</a>
			</li>
      @endif
      @if(Gate::check('browse-financial_routine'))
      <li class="nav-item">
				<a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_financial_routine'? 'active': '' }}" href="{{ route('list_financial_routine') }}">
					List by Bank
				</a>
			</li>
      @endif
      @if(Gate::check('add-financial_routine'))
      <li class="nav-item">
				<a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'add_financial_routine_branch'? 'active': '' }}" href="{{ route('add_financial_routine_branch')}}">
					Add by Branch
				</a>
			</li>
      @endif
      @if(Gate::check('browse-financial_routine'))
      <li class="nav-item">
				<a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_financial_routine_branch'? 'active': '' }}" href="{{ route('list_financial_routine_branch') }}">
					List by Branch
				</a>
			</li>
      @endif
    </ul>
  </div>
</li> --}}
@endif


@if(Gate::check('brwose-stock') || Gate::check('add-stock_in') || Gate::check('add-stock_out') || Gate::check('browse-stock_in_out'))
<li class="{{isset($menu_item_page) && $menu_item_page == 'stock'? 'active': '' }} nav-item">
  <a class="nav-link" data-toggle="collapse" href="#stock-dd" aria-expanded="false" aria-controls="stock-dd">
    <span class="menu-title">Stock</span>
    <i class="menu-arrow"></i>
    <i class="mdi mdi-package menu-icon"></i>
  </a>
  <div class="collapse {{isset($menu_item_page) && $menu_item_page == 'stock'? 'show': '' }}" id="stock-dd">
    <ul class="nav flex-column sub-menu">
      @if(Gate::check('add-stock_in'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'add_stock_in'? 'active': '' }}" href="{{route('add_stock_in')}}">Stock In</a></li>
      @endif
      @if(Gate::check('add-stock_in'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'add_stock_out'? 'active': '' }}" href="{{route('add_stock_out')}}">Stock Out</a></li>
      @endif
      @if(Gate::check('browse-stock'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_stock'? 'active': '' }}" href="{{route('list_stock')}}">List Stock</a></li>
      @endif
      @if(Gate::check('browse-stock_in_out'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_stock_in_out'? 'active': '' }}" href="{{route('list_stock_in_out')}}">List Stock In/Out</a></li>
      @endif
      @if(Gate::check('browse-stock_order_request'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_stock_order_request'? 'active': '' }}" href="{{route('list_stock_order_request')}}">Stock Order Request</a></li>
      @endif
    </ul>
  </div>
</li>
@endif

@if(Gate::check('add-data_sourcing') || Gate::check('browse-data_sourcing') || Gate::check('add-data_therapy') || Gate::check('browse-data_therapy'))
<li class="nav-item {{isset($menu_item_page) && $menu_item_page == 'data_sourcing' || isset($menu_item_page) && $menu_item_page == 'data_therapy' || isset($menu_item_page) && $menu_item_page == 'mpc_waki' || isset($menu_item_page) && $menu_item_page == 'import_data_sourcing' || isset($menu_item_page) && $menu_item_page == 'data_stock' ? 'active': '' }}">
  <a class="nav-link" data-toggle="collapse" href="#masterData-dd" aria-expanded="{{isset($menu_item_page) && $menu_item_page == 'data_sourcing' || isset($menu_item_page) && $menu_item_page == 'data_therapy' || isset($menu_item_page) && $menu_item_page == 'mpc_waki' || isset($menu_item_page) && $menu_item_page == 'import_data_sourcing' || isset($menu_item_page) && $menu_item_page == 'data_stock' ? 'true': '' }}" aria-controls="masterData-dd">
    <span class="menu-title">Master Data</span>
    <i class="menu-arrow"></i>
    <i class="mdi mdi-table-edit menu-icon"></i>
  </a>
  <div class="collapse {{isset($menu_item_page) && $menu_item_page == 'data_sourcing' || isset($menu_item_page) && $menu_item_page == 'data_therapy' || isset($menu_item_page) && $menu_item_page == 'mpc_waki' || isset($menu_item_page) && $menu_item_page == 'import_data_sourcing' || isset($menu_item_page) && $menu_item_page == 'data_stock' ? 'show': '' }}" id="masterData-dd">
    <ul class="nav flex-column">

		@if(Gate::check('add-data_sourcing') || Gate::check('browse-data_sourcing'))
		<li class="{{isset($menu_item_page) && $menu_item_page == 'data_sourcing'? 'active': '' }} nav-item">
		  <a class="nav-link" data-toggle="collapse" href="#data_sourcing-dd" aria-expanded="false" aria-controls="data_sourcing-dd">
		    <span class="menu-title">Data Sourcing</span>
		    <i class="menu-arrow"></i>
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

		@if(Gate::check('add-data_sourcing'))
		<li class="{{isset($menu_item_page) && $menu_item_page == 'data_stock'? 'active': '' }} nav-item">
			<a class="nav-link" data-toggle="collapse" href="#data_stock-dd" aria-expanded="false" aria-controls="data_stock-dd">
			  <span class="menu-title">Data Stock</span>
			  <i class="menu-arrow"></i>
			</a>
			<div class="collapse {{isset($menu_item_page) && $menu_item_page == 'data_stock'? 'show': '' }}" id="data_stock-dd">
			  <ul class="nav flex-column sub-menu">
				<li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'import_data_stock'? 'active': '' }}" href="{{route('import_data_stock')}}">Import Data</a></li>
			  </ul>
			</div>
		  </li>
		@endif

	</ul>
</div>
</li>
@endif


@if(Gate::check('add-cso') || Gate::check('browse-cso') || Gate::check('add-branch') || Gate::check('browse-branch'))
<li class="nav-item {{isset($menu_item_page) && $menu_item_page == 'cso' || isset($menu_item_page) && $menu_item_page == 'branch' || isset($menu_item_page) && $menu_item_page == 'promo' || isset($menu_item_page) && $menu_item_page == 'type_customer' || isset($menu_item_page) && $menu_item_page == 'bank' || isset($menu_item_page) && $menu_item_page == 'souvenir' || isset($menu_item_page) && $menu_item_page == 'prize' ? 'active': '' }}">
  <a class="nav-link" data-toggle="collapse" href="#masterAdmin-dd" aria-expanded="{{isset($menu_item_page) && $menu_item_page == 'cso' || isset($menu_item_page) && $menu_item_page == 'branch' || isset($menu_item_page) && $menu_item_page == 'promo' || isset($menu_item_page) && $menu_item_page == 'type_customer' || isset($menu_item_page) && $menu_item_page == 'bank' || isset($menu_item_page) && $menu_item_page == 'souvenir' || isset($menu_item_page) && $menu_item_page == 'prize' ? 'true': '' }}" aria-controls="masterAdmin-dd">
    <span class="menu-title">Master Admin</span>
    <i class="menu-arrow"></i>
    <i class="mdi mdi-account-convert menu-icon"></i>
  </a>
  <div class="collapse {{isset($menu_item_page) && $menu_item_page == 'cso' || isset($menu_item_page) && $menu_item_page == 'branch' || isset($menu_item_page) && $menu_item_page == 'promo' || isset($menu_item_page) && $menu_item_page == 'type_customer' || isset($menu_item_page) && $menu_item_page == 'bank' || isset($menu_item_page) && $menu_item_page == 'souvenir' || isset($menu_item_page) && $menu_item_page == 'prize' ? 'show': '' }}" id="masterAdmin-dd">
    <ul class="nav flex-column">

		@if(Gate::check('add-cso') || Gate::check('browse-cso'))
		<li class="{{isset($menu_item_page) && $menu_item_page == 'cso'? 'active': '' }} nav-item">
		  <a class="nav-link" data-toggle="collapse" href="#cso-dd" aria-expanded="false" aria-controls="cso-dd">
		    <span class="menu-title">CSO</span>
		    <i class="menu-arrow"></i>
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

		@if(Gate::check('add-promo') || Gate::check('browse-promo'))
		<li class="{{isset($menu_item_page) && $menu_item_page == 'promo'? 'active': '' }} nav-item">
		  <a class="nav-link" data-toggle="collapse" href="#promo-dd" aria-expanded="false" aria-controls="promo-dd">
		    <span class="menu-title">Promo</span>
		    <i class="menu-arrow"></i>
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

		@if(Gate::check('add-bank') || Gate::check('browse-bank'))
		<li class="{{isset($menu_item_page) && $menu_item_page == 'bank'? 'active': '' }} nav-item">
		  <a class="nav-link" data-toggle="collapse" href="#bank-dd" aria-expanded="false" aria-controls="bank-dd">
		    <span class="menu-title">Bank</span>
		    <i class="menu-arrow"></i>
		  </a>
		  <div class="collapse {{isset($menu_item_page) && $menu_item_page == 'bank'? 'show': '' }}" id="bank-dd">
		    <ul class="nav flex-column sub-menu">
		      @if(Gate::check('add-bank'))
		      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'add_bank'? 'active': '' }}" href="{{route('add_bank')}}">Add Bank</a></li>
		      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'add_bank_account'? 'active': '' }}" href="{{route('add_bank_account')}}">Add Bank Account</a></li>
		      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'add_credit_card'? 'active': '' }}" href="{{route('add_credit_card')}}">Add Credit Card</a></li>
		      @endif
		      @if(Gate::check('browse-bank'))
		      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_bank'? 'active': '' }}" href="{{route('list_bank')}}">List Bank</a></li>
		      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_bank_account'? 'active': '' }}" href="{{route('list_bank_account')}}">List Bank Account</a></li>
		      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_credit_card'? 'active': '' }}" href="{{route('list_credit_card')}}">List Credit Card</a></li>
		      @endif
		    </ul>
		  </div>
		</li>
		@endif

	</ul>
</div>
</li>
@endif

@if(Gate::check('add-category') || Gate::check('browse-category') || Gate::check('add-product') || Gate::check('browse-product'))
<li class="nav-item {{isset($menu_item_page) && $menu_item_page == 'index_frontendcms' || isset($menu_item_page) && $menu_item_page == 'category' || isset($menu_item_page) && $menu_item_page == 'product' ? 'active': '' }}">
  <a class="nav-link" data-toggle="collapse" href="#masterCMS-dd" aria-expanded="{{isset($menu_item_page) && $menu_item_page == 'index_frontendcms' || isset($menu_item_page) && $menu_item_page == 'category' || isset($menu_item_page) && $menu_item_page == 'product' ? 'true': '' }}" aria-controls="masterCMS-dd">
    <span class="menu-title">Master CMS</span>
    <i class="menu-arrow"></i>
    <i class="mdi mdi-table-edit menu-icon"></i>
  </a>
  <div class="collapse {{isset($menu_item_page) && $menu_item_page == 'index_frontendcms' || isset($menu_item_page) && $menu_item_page == 'category' || isset($menu_item_page) && $menu_item_page == 'product' ? 'show': '' }}" id="masterCMS-dd">
    <ul class="nav flex-column">

		@if(Gate::check('add-category') || Gate::check('browse-category'))
		<li class="{{isset($menu_item_page) && $menu_item_page == 'category'? 'active': '' }} nav-item">
		  <a class="nav-link" data-toggle="collapse" href="#kategori-dd" aria-expanded="false" aria-controls="kategori-dd">
		    <span class="menu-title">Category</span>
		    <i class="menu-arrow"></i>
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

	</ul>
</div>
</li>
@endif

@if(Gate::check('add-warehouse') || Gate::check('browse-warehouse'))
<li class="{{isset($menu_item_page) && $menu_item_page == 'warehouse'? 'active': '' }} nav-item">
  <a class="nav-link" data-toggle="collapse" href="#warehouse-dd" aria-expanded="false" aria-controls="warehouse-dd">
    <span class="menu-title">Warehouse</span>
    <i class="menu-arrow"></i>
    <i class="mdi mdi-gift menu-icon"></i>
  </a>
  <div class="collapse {{isset($menu_item_page) && $menu_item_page == 'warehouse'? 'show': '' }}" id="warehouse-dd">
    <ul class="nav flex-column sub-menu">
      @if(Gate::check('add-warehouse'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'add_warehouse'? 'active': '' }}" href="{{route('add_warehouse')}}">Add Warehouse</a></li>
      @endif
      @if(Gate::check('browse-warehouse'))
      <li class="nav-item"> <a class="nav-link {{isset($menu_item_second) && $menu_item_second == 'list_warehouse'? 'active': '' }}" href="{{route('list_warehouse')}}">List Warehouse</a></li>
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

<li class="{{isset($menu_item_page) && $menu_item_page == 'faq_agreement'? 'active': '' }} nav-item">
  <a class="nav-link" href="{{ route('faq_agreement') }}" >
    <span class="menu-title">FaQ</span>
    <i class="mdi mdi-account menu-icon"></i>
  </a>
</li>
