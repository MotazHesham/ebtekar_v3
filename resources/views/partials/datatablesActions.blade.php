@can($viewGate)
    <a class="btn btn-xs btn-primary" href="{{ route('admin.' . $crudRoutePart . '.show', $row->id) }}">
        {{ trans('global.view') }}
    </a>
@endcan
@can($editGate)
    <a class="btn btn-xs btn-info" href="{{ route('admin.' . $crudRoutePart . '.edit', $row->id) }}">
        {{ trans('global.edit') }}
    </a>
@endcan
@can($deleteGate) 
    <?php $route = route('admin.' . $crudRoutePart . '.destroy', $row->id); ?>
    <a class="btn btn-xs btn-danger" href="#" onclick="deleteConfirmation('{{$route}}')">
        {{ trans('global.delete') }}  
    </a>
@endcan