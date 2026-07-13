<div class="w-full lg:w-40 md:w-40 settings-sidebar bg-red-800 self-stretch">
	<ul class="list-reset">
		<li class="{{ Request::segment('3') == 'generalsettings' ? 'active' : '' }}"><a href="{{url('admin/settings/generalsettings/')}}" class="no-underline">General settings</a></li>
		{{-- <li class="{{ Request::segment('3') == 'seodetailsettings' ? 'active' : '' }}"><a href="{{url('admin/settings/seodetailsettings/')}}" class="no-underline">SEO Details</a></li>
		<li class="{{ Request::segment('3') == 'maintenancesettings' ? 'active' : '' }}"><a href="{{url('admin/settings/maintenancesettings/')}}" class="no-underline">Maintenance Mode</a></li>
		<li class="{{ Request::segment('3') == 'securitysettings' ? 'active' : '' }}"><a href="{{url('admin/settings/securitysettings/')}}" class="no-underline">Security</a></li> --}}
		<li class="{{ in_array(Request::segment('3'), ['countries', 'country']) ? 'active' : '' }}"><a href="{{url('admin/setting/countries')}}" class="no-underline">Countries</a></li>
		<li class="{{ in_array(Request::segment('3'), ['states', 'state']) ? 'active' : '' }}"><a href="{{url('admin/setting/states')}}" class="no-underline">States</a></li>
		<li class="{{ in_array(Request::segment('3'), ['cities', 'city']) ? 'active' : '' }}"><a href="{{url('admin/setting/cities')}}" class="no-underline">Cities</a></li>
	</ul>
</div>