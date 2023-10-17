@if (Session::get('level') == 'manager')
    @include('admin.menu.manager')  
@endif 

@if (Session::get('level') == 'admin')
    @include('admin.menu.admin')  
@endif  

@if (Session::get('level') == 'user')
    @include('admin.menu.user')  
@endif 