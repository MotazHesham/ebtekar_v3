

<section class="section-big-pb-space ratio_square product" style="direction: ltr;">
    <div class="container">
        <div class="row">
            <div class="col pr-0">
                <div class="product-slide-7 product-m no-arrow">
                    @foreach ($best_selling_products as $product) 
                        @include('frontend.partials.single-product',['product' => $product, 'preview' => 'preview2'])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>