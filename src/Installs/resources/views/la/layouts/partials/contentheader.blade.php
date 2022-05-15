
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
    <h1>
        @yield('contentheader_title', 'Page Header here')
        <small>@yield('contentheader_description')</small>
    </h1>
    </div> <div class="col-sm-6">
    @hasSection('headerElems')
   
        @yield('headerElems')
        
    @else 
        @hasSection('section')
        <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="@yield('section_url')"><i class="fa fa-dashboard"></i> @yield('section')</a></li>
            @hasSection('sub_section')<li class="breadcrumb-item active"> @yield('sub_section') </li>@endif
        </ol>
        @endif
    @endif
    </div>
    </div>
  </div>
</div>