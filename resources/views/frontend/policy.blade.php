@extends('frontend.layout.app')

@section('content')
    @isset($policy)
        <div class="container">
            <!-- about section start -->
            <h3>{{ $policy->name ? \App\Models\Police::NAME_SELECT[$policy->name] : '' }}</h3>
            <!-- about section end -->
            <hr>
            <p>
                <?php echo $policy->content;  ?> 
            </p>
        </div>
    @endisset
@endsection
