@if(session('message'))
<div class="alert alert-{{ is_array(session('message')) ? session('message')['type'] : 'success' }} alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    {{ is_array(session('message')) ? session('message')['body'] : session('message') }}
</div>
@endif