<!-- Left side column. contains the logo and sidebar -->

<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- sidebar: style can be found in sidebar.less -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        @if (! Auth::guest())
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="pull-left image">
                <img src="{{ asset('la-assets/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><a href="#" class="d-block">{{ Auth::user()->name }}</a></p>
            </div>
        </div>
        @endif

        <!-- search form (Optional) -->
        @if(LAConfigs::getByKey('sidebar_search'))
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..." />
                <span class="input-group-btn">
                    <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </form>
        @endif
        <!-- /.search form -->

        <!-- Sidebar Menu -->
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
            <!-- 
                <li class="nav-header">MÓDULOS</li>
                Optionally, you can Adicionar icons to the links 
            -->
            <li class="nav-item">
                <a href="{{ url(config('laraadmin.adminRoute')) }}" class="nav-link"><i class='nav-icon fa fa-home'></i>
                    <p>Painel</p>
                </a>
            </li>
            <?php
            $menuItems = Dwij\Laraadmin\Models\Menu::where("parent", 0)->orderBy('hierarchy', 'asc')->get();
            ?>
            @foreach ($menuItems as $menu)
            @if($menu->type == "module")
            <?php
            $temp_module_obj = Module::get($menu->name);
            ?>
            @la_access($temp_module_obj->id)
            @if(isset($module->id) && $module->name == $menu->name)
            <?php echo LAHelper::print_menu($menu, true); ?>
            @else
            <?php echo LAHelper::print_menu($menu); ?>
            @endif
            @endla_access
            @else
            <?php echo LAHelper::print_menu($menu); ?>
            @endif
            @endforeach
            <!-- LAMenus -->

        </ul><!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>