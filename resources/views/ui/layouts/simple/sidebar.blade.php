<div class="sidebar-wrapper">
	<div>
		<div class="logo-wrapper">
			<a href="{{route('/')}}"><img class="img-fluid for-light" style="max-width: 90% !important;" src="{{asset('assets/images/logo/logo_receipt.png')}}" alt=""><img class="img-fluid for-dark" src="{{asset('assets/images/logo/logo_dark.png')}}" alt=""></a>
			<div class="back-btn"><i class="fa fa-angle-left"></i></div>
			<div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
		</div>
		<div class="logo-icon-wrapper"><a href="{{route('/')}}"><img class="img-fluid" src="{{asset('assets/images/logo/logo-icon.png')}}" alt=""></a></div>
		<nav class="sidebar-main">
			<div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
			<div id="sidebar-menu">
				<ul class="sidebar-links" id="simple-bar">
					<li class="back-btn">
						<a href="{{route('/')}}"><img class="img-fluid" src="{{asset('assets/images/logo/logo-icon.png')}}" alt=""></a>
						<div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
					</li>

                        <li class="sidebar-list">
                            <a class="sidebar-link sidebar-title link-nav " href="{{route('index')}}">
                                <i data-feather="git-pull-request"> </i><span>{{ trans('lang.Dashboard') }}</span>
                            </a>
                    </li>

                    {{-- user --}}

                    <li class="sidebar-list">
						 {{ request()->routeIs(['sales.index','sales.create']) ? 'active' : '' }}
                        <a class="sidebar-link sidebar-title {{ request()->routeIs(['sales.index','sales.create']) ? 'active' : '' }} " href="#">
                            <i data-feather="shopping-bag"></i><span>Sales</span>
                            <div class="according-menu"><i class="fa fa-angle-{{request()->routeIs(['sales.index','sales.create']) ? 'down' : 'right' }}"></i></div>
                        </a>
                        <ul class="sidebar-submenu" style="display: {{request()->routeIs(['sales.index','sales.create']) ? 'block' : 'none;' }};">
                            <li><a href="{{route('sales.index')}}" class="{{ request()->routeIs(['sales.index']) ? 'active' : '' }}">Sales</a></li>
                            <li><a href="{{route('sales.create')}}" class="{{ request()->routeIs(['sales.create']) ? 'active' : '' }}">Create Sale</a></li>
                        </ul>
                    </li>
					<li class="sidebar-list">
						<a class="sidebar-link sidebar-title {{ request()->routeIs(['author.index','author.create']) ? 'active' : '' }}" href="#">
							<i data-feather="feather"></i><span>Authors</span>
							<div class="according-menu"><i class="fa fa-angle-{{request()->routeIs(['author.index','author.create']) ? 'down' : 'right' }}"></i></div>
						</a>
						{{-- {{request()->routeIs(['purchase.index','purchase.create'])  ? 'block' : 'none;' }} --}}
						<ul class="sidebar-submenu" style="display: {{request()->routeIs(['author.index','author.create'])  ? 'block' : 'none;' }};">
		                    <li><a href="{{route('author.index')}}" class="{{ request()->routeIs(['author.index']) ? 'active' : '' }}">Authors</a></li>
		                    <li><a href="{{route('author.create')}}" class="{{ request()->routeIs(['author.create']) ? 'active' : '' }}">Create Author</a></li>
		                </ul>
					</li>



                    <li class="sidebar-list">
						<a class="sidebar-link sidebar-title {{ request()->routeIs(['publisher.index','publisher.create']) ? 'active' : '' }}" href="#">
							<i data-feather="layers"></i><span>Publishers</span>
							<div class="according-menu"><i class="fa fa-angle-{{request()->routeIs(['publisher.index','publisher.create']) ? 'down' : 'right' }}"></i></div>
						</a>
						<ul class="sidebar-submenu" style="display:{{request()->routeIs(['publisher.index','publisher.create'])  ? 'block' : 'none;' }};">
		                    <li><a href="{{route('publisher.index')}}" class="{{ request()->routeIs(['publisher.index']) ? 'active' : '' }}">Publishers</a></li>
		                    <li><a href="{{route('publisher.create')}}" class="{{ request()->routeIs(['publisher.create']) ? 'active' : '' }}">Create Publisher</a></li>
		                </ul>
					</li>
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title{{ request()->routeIs(['supplier.index','supplier.create']) ? 'active' : '' }} " href="#">
                            <i data-feather="truck"></i><span>Supplier</span>
                            <div class="according-menu"><i class="fa fa-angle-{{request()->routeIs(['supplier.index','supplier.create']) ? 'down' : 'right' }}"></i></div>
                        </a>
                        <ul class="sidebar-submenu" style="display: {{request()->routeIs(['supplier.index','supplier.create'])  ? 'block' : 'none;' }}">
                            <li><a href="{{route('supplier.index')}}" class="{{ request()->routeIs(['supplier.index']) ? 'active' : '' }}">Suppliers</a></li>
                            <li><a href="{{route('supplier.create')}}" class="{{ request()->routeIs(['supplier.create']) ? 'active' : '' }}">Create Supplier</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title{{ request()->routeIs(['book.index','book.create']) ? 'active' : '' }} " href="#">
                            <i data-feather="book-open"></i><span>Book</span>
                            <div class="according-menu"><i class="fa fa-angle-{{request()->routeIs(['book.index','book.create']) ? 'down' : 'right' }}"></i></div>
                        </a>
                        <ul class="sidebar-submenu" style="display: {{request()->routeIs(['book.index','book.create'])  ? 'block' : 'none;' }}">
                            <li><a href="{{route('book.index')}}" class="{{ request()->routeIs(['book.index']) ? 'active' : '' }}">Books</a></li>
                            <li><a href="{{route('book.create')}}" class="{{ request()->routeIs(['book.create']) ? 'active' : '' }}">Create Book</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title{{ request()->routeIs(['purchase.index','purchase.create']) ? 'active' : '' }} " href="#">
                            <i data-feather="shopping-cart"></i><span>Purchase</span>
                            <div class="according-menu"><i class="fa fa-angle-{{request()->routeIs(['purchase.index','purchase.create']) ? 'down' : 'right' }}"></i></div>
                        </a>
                        <ul class="sidebar-submenu" style="display: {{request()->routeIs(['purchase.index','purchase.create'])  ? 'block' : 'none;' }}">
                            <li><a href="{{route('purchase.index')}}" class="{{ request()->routeIs(['purchase.index']) ? 'active' : '' }}">Purchases</a></li>
                            <li><a href="{{route('purchase.create')}}" class="{{ request()->routeIs(['purchase.create']) ? 'active' : '' }}">Create Purchase</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title{{ request()->routeIs(['expenses.index','expenses.create']) ? 'active' : '' }} " href="#">
                            <i data-feather="shopping-cart"></i><span>Expenses</span>
                            <div class="according-menu"><i class="fa fa-angle-{{request()->routeIs(['expenses.index','expenses.create']) ? 'down' : 'right' }}"></i></div>
                        </a>
                        <ul class="sidebar-submenu" style="display: {{request()->routeIs(['expenses.index','expenses.create'])  ? 'block' : 'none;' }}">
                            <li><a href="{{route('expenses.index')}}" class="{{ request()->routeIs(['expenses.index']) ? 'active' : '' }}">Expenses</a></li>
                            <li><a href="{{route('expenses.create')}}" class="{{ request()->routeIs(['expenses.create']) ? 'active' : '' }}">Create Expenses</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title{{ request()->routeIs(['customer.index','customer.create']) ? 'active' : '' }} " href="#">
                            <i data-feather="shopping-cart"></i><span>Customer</span>
                            <div class="according-menu"><i class="fa fa-angle-{{request()->routeIs(['customer.index','customer.create']) ? 'down' : 'right' }}"></i></div>
                        </a>
                        <ul class="sidebar-submenu" style="display: {{request()->routeIs(['customer.index','customer.create'])  ? 'block' : 'none;' }}">
                            <li><a href="{{route('customer.index')}}" class="{{ request()->routeIs(['customer.index']) ? 'active' : '' }}">Customers</a></li>
                            <li><a href="{{route('customer.create')}}" class="{{ request()->routeIs(['customer.create']) ? 'active' : '' }}">Create Customer</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title " href="#">
                            <i data-feather="corner-up-left"></i><span>Returns</span>
                            <div class="according-menu"><i class="fa fa-angle-"></i></div>
                        </a>
                        <ul class="sidebar-submenu" style="display: none;">
                            <li><a href="" class="">Returns</a></li>
                            <li><a href="" class="">Create Return</a></li>
                        </ul>
                    </li>


                    {{-- <li class="sidebar-list">
						<a class="sidebar-link sidebar-title {{ request()->routeIs(['customer.index','customer.create']) ? 'active' : '' }}" href="#">
							<i data-feather="users"></i><span>Reports</span>
							<div class="according-menu"><i class="fa fa-angle-{{request()->routeIs(['customer.index','customer.create']) ? 'down' : 'right' }}"></i></div>
						</a>
						<ul class="sidebar-submenu" style="display: {{request()->routeIs(['customer.index','customer.create']) ? 'block' : 'none;' }};">
		                    <li><a href="{{route('customer.index')}}" class="{{ Route::currentRouteName()=='customer.index' ? 'active' : '' }}">Reports</a></li>
		                    <li><a href="{{route('customer.create')}}" class="{{ Route::currentRouteName()=='customer.create' ? 'active' : '' }}">Create Customer</a></li>
		                </ul>
					</li>
                    <li class="sidebar-list">
						<a class="sidebar-link sidebar-title {{ request()->routeIs(['purchaser.index','purchaser.create']) ? 'active' : '' }}" href="#">
							<i data-feather="user-check"></i><span>Settings</span>
							<div class="according-menu"><i class="fa fa-angle-{{request()->routeIs(['purchaser.index','purchaser.create']) ? 'down' : 'right' }}"></i></div>
						</a>
						<ul class="sidebar-submenu" style="display: {{request()->routeIs(['purchaser.index','purchaser.create']) ? 'block' : 'none;' }};">
		                    <li><a href="{{route('purchaser.index')}}" class="{{ Route::currentRouteName()=='purchaser.index' ? 'active' : '' }}">Settings</a></li>
		                    <li><a href="{{route('purchaser.create')}}" class="{{ Route::currentRouteName()=='purchaser.create' ? 'active' : '' }}">Create Purchaser</a></li>
		                </ul>
					</li> --}}




					{{-- <li class="sidebar-main-title">
						<div>
							<h6>{{ trans('lang.Forms & Table') }}</h6>
                 			<p>{{ trans('lang.Ready to use froms & tables') }}</p>
						</div>
					</li>
					<li class="sidebar-list">
						<a class="sidebar-link sidebar-title {{request()->route()->getPrefix() == '/forms' ? 'active' : '' }}" href="#">
							<i data-feather="file-text"></i><span>{{ trans('lang.Forms') }}</span>
							<div class="according-menu"><i class="fa fa-angle-{{request()->route()->getPrefix() == '/forms' ? 'down' : 'right' }}"></i></div>
						</a>
						<ul class="sidebar-submenu" style="display: {{ request()->route()->getPrefix() == '/forms' ? 'block;' : 'none;' }}">
							<li>
								<a class="submenu-title {{ in_array(Route::currentRouteName(), ['form-validation', 'base-input', 'radio-checkbox-control', 'input-group', 'megaoptions']) ? 'active' : '' }}" href="#">Form Controls
									<div class="according-menu"><i class="fa fa-angle-{{ in_array(Route::currentRouteName(), ['form-validation', 'base-input', 'radio-checkbox-control', 'input-group', 'megaoptions']) ? 'down' : 'right' }}"></i></div>
								</a>
								<ul class="nav-sub-childmenu submenu-content" style="display: {{ in_array(Route::currentRouteName(), ['form-validation', 'base-input', 'radio-checkbox-control', 'input-group', 'megaoptions']) ? 'block' : 'none;' }};">
									<li><a href="{{route('form-validation')}}" class="{{ Route::currentRouteName()=='form-validation' ? 'active' : '' }}">Form Validation</a></li>
		                            <li><a href="{{route('base-input')}}" class="{{ Route::currentRouteName()=='base-input' ? 'active' : '' }}">Base Inputs</a></li>
		                            <li><a href="{{route('radio-checkbox-control')}}" class="{{ Route::currentRouteName()=='radio-checkbox-control' ? 'active' : '' }}">Checkbox & Radio</a></li>
		                            <li><a href="{{route('input-group')}}" class="{{ Route::currentRouteName()=='input-group' ? 'active' : '' }}">Input Groups</a></li>
		                            <li><a href="{{route('megaoptions')}}" class="{{ Route::currentRouteName()=='megaoptions' ? 'active' : '' }}">Mega Options</a></li>
								</ul>
							</li>
							<li>
								<a class="submenu-title {{ in_array(Route::currentRouteName(), ['datepicker', 'time-picker', 'datetimepicker','daterangepicker' ,'touchspin', 'select2', 'switch', 'typeahead', 'clipboard']) ? 'active' : '' }}" href="#">Form Widgets
									<div class="according-menu"><i class="fa fa-angle-{{ in_array(Route::currentRouteName(), ['datepicker', 'time-picker', 'datetimepicker','daterangepicker' ,'touchspin', 'select2', 'switch', 'typeahead', 'clipboard']) ? 'down' : 'right' }}"></i></div>
								</a>
								<ul class="nav-sub-childmenu submenu-content" style="display: {{ in_array(Route::currentRouteName(), ['datepicker', 'time-picker', 'datetimepicker','daterangepicker' ,'touchspin', 'select2', 'switch', 'typeahead', 'clipboard']) ? 'block' : 'none;' }};">
										<li><a href="{{route('datepicker')}}" class="{{ Route::currentRouteName()=='datepicker' ? 'active' : '' }}">Datepicker</a></li>
		                              	<li><a href="{{route('time-picker')}}" class="{{ Route::currentRouteName()=='time-picker' ? 'active' : '' }}">Timepicker</a></li>
		                              	<li><a href="{{route('datetimepicker')}}" class="{{ Route::currentRouteName()=='datetimepicker' ? 'active' : '' }}">Datetimepicker</a></li>
		                              	<li><a href="{{route('daterangepicker')}}" class="{{ Route::currentRouteName()=='daterangepicker' ? 'active' : '' }}">Daterangepicker</a></li>
		                              	<li><a href="{{route('touchspin')}}" class="{{ Route::currentRouteName()=='touchspin' ? 'active' : '' }}">Touchspin</a></li>
		                              	<li><a href="{{route('select2')}}" class="{{ Route::currentRouteName()=='select2' ? 'active' : '' }}">Select2</a></li>
		                              	<li><a href="{{route('switch')}}" class="{{ Route::currentRouteName()=='switch' ? 'active' : '' }}">Switch</a></li>
		                              	<li><a href="{{route('typeahead')}}" class="{{ Route::currentRouteName()=='typeahead' ? 'active' : '' }}">Typeahead</a></li>
		                              	<li><a href="{{route('clipboard')}}" class="{{ Route::currentRouteName()=='clipboard' ? 'active' : '' }}">Clipboard</a></li>
								</ul>
							</li>
							<li>
								<a class="submenu-title {{ in_array(Route::currentRouteName(), ['default-form', 'form-wizard', 'form-wizard-two', 'form-wizard-three']) ? 'active' : '' }}" href="#">Form Layout
									<div class="according-menu"><i class="fa fa-angle-{{ in_array(Route::currentRouteName(), ['default-form', 'form-wizard', 'form-wizard-two', 'form-wizard-three']) ? 'down' : 'right' }}"></i></div>
								</a>
								<ul class="nav-sub-childmenu submenu-content" style="display: {{ in_array(Route::currentRouteName(), ['default-form', 'form-wizard', 'form-wizard-two', 'form-wizard-three']) ? 'block' : 'none;' }};">
	                              	<li><a href="{{route('default-form')}}" class="{{ Route::currentRouteName()=='default-form' ? 'active' : '' }}">Default Forms</a></li>
	                              	<li><a href="{{route('form-wizard')}}" class="{{ Route::currentRouteName()=='form-wizard' ? 'active' : '' }}">Form Wizard 1</a></li>
	                              	<li><a href="{{route('form-wizard-two')}}" class="{{ Route::currentRouteName()=='form-wizard-two' ? 'active' : '' }}">Form Wizard 2</a></li>
	                              	<li><a href="{{route('form-wizard-three')}}" class="{{ Route::currentRouteName()=='form-wizard-three' ? 'active' : '' }}">Form Wizard 3</a></li>
	                        	</ul>
							</li>
						</ul>
					</li>
					<li class="sidebar-list">
						<a class="sidebar-link sidebar-title {{request()->route()->getPrefix() == '/tables' ? 'active' : '' }}" href="#"><i data-feather="server"></i><span>{{ trans('lang.Tables') }}</span>
							<div class="according-menu"><i class="fa fa-angle-{{request()->route()->getPrefix() == '/tables' ? 'down' : 'right' }}"></i></div>
						</a>
						<ul class="sidebar-submenu" style="display: {{ request()->route()->getPrefix() == '/tables' ? 'block;' : 'none;' }}">
							<li>
								<a class="submenu-title  {{ in_array(Route::currentRouteName(), ['bootstrap-basic-table', 'bootstrap-sizing-table', 'bootstrap-sizing-table', 'bootstrap-border-table', 'bootstrap-styling-table', 'table-components']) ? 'active' : '' }}"  href="#">Bootstrap Tables
									<div class="according-menu"><i class="fa fa-angle-{{ in_array(Route::currentRouteName(), ['bootstrap-basic-table', 'bootstrap-sizing-table', 'bootstrap-sizing-table', 'bootstrap-border-table', 'bootstrap-styling-table', 'table-components']) ? 'down' : 'right' }}"></i></div>
								</a>
								<ul class="nav-sub-childmenu submenu-content" style="display: {{ in_array(Route::currentRouteName(), ['bootstrap-basic-table', 'bootstrap-sizing-table', 'bootstrap-sizing-table', 'bootstrap-border-table', 'bootstrap-styling-table', 'table-components']) ? 'block' : 'none;' }};">
	                              	<li><a href="{{route('bootstrap-basic-table')}}" class="{{ Route::currentRouteName()=='bootstrap-basic-table' ? 'active' : '' }}">Basic Tables</a></li>
	                                <li><a href="{{route('bootstrap-sizing-table')}}" class="{{ Route::currentRouteName()=='bootstrap-sizing-table' ? 'active' : '' }}">Sizing Tables</a></li>
	                              	<li><a href="{{route('bootstrap-border-table')}}" class="{{ Route::currentRouteName()=='bootstrap-border-table' ? 'active' : '' }}">Border Tables</a></li>
	                              	<li><a href="{{route('bootstrap-styling-table')}}" class="{{ Route::currentRouteName()=='bootstrap-styling-table' ? 'active' : '' }}">Styling Tables</a></li>
	                              	<li><a href="{{route('table-components')}}" class="{{ Route::currentRouteName()=='table-components' ? 'active' : '' }}">Table components</a></li>
                       			</ul>
							</li>
							<li>
								<a class="submenu-title  {{ in_array(Route::currentRouteName(), ['datatable-basic-init', 'datatable-advance', 'datatable-styling', 'datatable-ajax', 'datatable-server-side', 'datatable-plugin', 'datatable-api']) ? 'active' : '' }}" href="#">Data Tables
									<div class="according-menu"><i class="fa fa-angle-{{ in_array(Route::currentRouteName(), ['datatable-basic-init', 'datatable-advance', 'datatable-styling', 'datatable-ajax', 'datatable-server-side', 'datatable-plugin', 'datatable-api', 'datatable-data-source']) ? 'down' : 'right' }}"></i></div>
								</a>
								<ul class="nav-sub-childmenu submenu-content" style="display: {{ in_array(Route::currentRouteName(), ['datatable-basic-init', 'datatable-advance', 'datatable-styling', 'datatable-ajax', 'datatable-server-side', 'datatable-plugin', 'datatable-api', 'datatable-data-source']) ? 'block' : 'none;' }};">
		                              	<li><a href="{{route('datatable-basic-init')}}" class="{{ Route::currentRouteName()=='datatable-basic-init' ? 'active' : '' }}">Basic Init</a></li>
		                              	<li><a href="{{route('datatable-advance')}}" class="{{ Route::currentRouteName()=='datatable-advance' ? 'active' : '' }}">Advance Init</a></li>
		                              	<li><a href="{{route('datatable-styling')}}" class="{{ Route::currentRouteName()=='datatable-styling' ? 'active' : '' }}">Styling</a></li>
		                              	<li><a href="{{route('datatable-ajax')}}" class="{{ Route::currentRouteName()=='datatable-ajax' ? 'active' : '' }}">AJAX</a></li>
		                              	<li><a href="{{route('datatable-server-side')}}" class="{{ Route::currentRouteName()=='datatable-server-side' ? 'active' : '' }}">Server Side</a></li>
		                              	<li><a href="{{route('datatable-plugin')}}" class="{{ Route::currentRouteName()=='datatable-plugin' ? 'active' : '' }}">Plug-in</a></li>
		                              	<li><a href="{{route('datatable-api')}}" class="{{ Route::currentRouteName()=='datatable-api' ? 'active' : '' }}">API</a></li>
		                              	<li><a href="{{route('datatable-data-source')}}" class="{{ Route::currentRouteName()=='datatable-data-source' ? 'active' : '' }}">Data Sources</a></li>
		                        </ul>
							</li>
							<li>
								<a class="submenu-title {{ in_array(Route::currentRouteName(), ['datatable-ext-autofill', 'datatable-ext-basic-button', 'datatable-ext-col-reorder', 'datatable-ext-fixed-header', 'datatable-ext-html-5-data-export', 'datatable-ext-responsive', 'datatable-ext-row-reorder', 'datatable-ext-scroller']) ? 'active' : '' }}" href="#">Ex. Data Tables
									<div class="according-menu"><i class="fa fa-angle-{{ in_array(Route::currentRouteName(), ['datatable-ext-autofill', 'datatable-ext-basic-button', 'datatable-ext-col-reorder', 'datatable-ext-fixed-header', 'datatable-ext-html-5-data-export', 'datatable-ext-key-table', 'datatable-ext-responsive', 'datatable-ext-row-reorder', 'datatable-ext-scroller']) ? 'down' : 'right' }}"></i></div>
								</a>
								<ul class="nav-sub-childmenu submenu-content" style="display: {{ in_array(Route::currentRouteName(), ['datatable-ext-autofill', 'datatable-ext-basic-button', 'datatable-ext-col-reorder', 'datatable-ext-fixed-header', 'datatable-ext-html-5-data-export', 'datatable-ext-key-table', 'datatable-ext-responsive', 'datatable-ext-row-reorder', 'datatable-ext-scroller']) ? 'block' : 'none;' }};">
									<li><a href="{{route('datatable-ext-autofill')}}" class="{{ Route::currentRouteName()=='datatable-ext-autofill' ? 'active' : '' }}">Auto Fill</a></li>
									<li><a href="{{route('datatable-ext-basic-button')}}" class="{{ Route::currentRouteName()=='datatable-ext-basic-button' ? 'active' : '' }}">Basic Button</a></li>
									<li><a href="{{route('datatable-ext-col-reorder')}}" class="{{ Route::currentRouteName()=='datatable-ext-col-reorder' ? 'active' : '' }}">Column Reorder</a></li>
									<li><a href="{{route('datatable-ext-fixed-header')}}" class="{{ Route::currentRouteName()=='datatable-ext-fixed-header' ? 'active' : '' }}">Fixed Header</a></li>
									<li><a href="{{route('datatable-ext-html-5-data-export')}}" class="{{ Route::currentRouteName()=='datatable-ext-html-5-data-export' ? 'active' : '' }}">HTML 5 Export</a></li>
									<li><a href="{{route('datatable-ext-key-table')}}" class="{{ Route::currentRouteName()=='datatable-ext-key-table' ? 'active' : '' }}">Key Table</a></li>
									<li><a href="{{route('datatable-ext-responsive')}}" class="{{ Route::currentRouteName()=='datatable-ext-responsive' ? 'active' : '' }}">Responsive</a></li>
									<li><a href="{{route('datatable-ext-row-reorder')}}" class="{{ Route::currentRouteName()=='datatable-ext-row-reorder' ? 'active' : '' }}">Row Reorder</a></li>
									<li><a href="{{route('datatable-ext-scroller')}}" class="{{ Route::currentRouteName()=='datatable-ext-scroller' ? 'active' : '' }}">Scroller</a></li>
								</ul>
							</li>
							<li><a href="{{route('jsgrid-table')}}" class="{{ Route::currentRouteName()=='jsgrid-table' ? 'active' : '' }}">Js Grid Table </a></li>
						</ul>
					</li> --}}





				</ul>
			</div>
			<div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
		</nav>
	</div>
</div>
