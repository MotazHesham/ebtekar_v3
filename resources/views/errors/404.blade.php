@extends('frontend.layout.app')

@section('content')
    <section class="p-0 b-g-light">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="error-section">
                        <h1>404</h1>
                        <h2>الصفحة غير موجودة</h2>
                        <a href="{{ route('home') }}" class="btn btn-normal">العودة للرئيسية</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
