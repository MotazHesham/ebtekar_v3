
@foreach($branches as  $branch)
    <option value="{{ $branch->id }}" {{ old('r_branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
@endforeach 