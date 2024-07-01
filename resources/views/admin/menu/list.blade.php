@if ($level == 'manager')
    @include('admin.menu.manager')  
@endif 

@if ($level == 'admin')
    @include('admin.menu.admin')  
@endif  

@if ($level == 'user')
    @include('admin.menu.user')  
@endif 

@if ($level == 'boss')
    @include('admin.menu.boss')  
@endif 