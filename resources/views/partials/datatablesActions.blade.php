@can($viewGate)
    <a class="btn btn-xs btn-primary" href="{{ route('admin.' . $crudRoutePart . '.show', $row->id) }}">
        {{ __('global.view') }}
    </a>
@endcan
@can($editGate)
    <a class="btn btn-xs btn-info" href="{{ route('admin.' . $crudRoutePart . '.edit', $row->id) }}">
        {{ __('global.edit') }}
    </a>
@endcan
@can($deleteGate) 
    <?php $route = route('admin.' . $crudRoutePart . '.destroy', $row->id); ?>
    <a class="btn btn-xs btn-danger" href="#" onclick="deleteConfirmation('{{$route}}')">
        {{ __('global.delete') }}  
    </a>
@endcan