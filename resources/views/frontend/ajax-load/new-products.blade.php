
<section class="section-big-pb-space ratio_asos product" style="direction: ltr;">
    <div class="container">
        <div class="row">
            <div class="col pr-0">
                <div class="product-slide-5 product-m no-arrow">
                    @foreach ($new_products as $product) 
                        @include('frontend.partials.single-product',['product' => $product, 'preview' => 'preview2'])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>