
function addToCartDataLayer(id,name,category,price){
            
    dataLayer.push({
        ecommerce: null
    });

    dataLayer.push({
        'event': 'add_to_cart',
        'ecommerce': {
            currencyCode: 'EGP',
            add: {
                products: [{
                    id: id,
                    name: name,
                    category: category,
                    price: price,
                }]
            }
        }
    })
}
function wishListDataLayer(id,name,category,price){
            
    dataLayer.push({
        ecommerce: null
    });

    dataLayer.push({
        'event': 'add_to_wishlist',
        'ecommerce': {
            currencyCode: 'EGP',
            add: {
                products: [{
                    id: id,
                    name: name,
                    category: category,
                    price: price,
                }]
            }
        }
    })
}
function initiate_checkout_dataLayer(total,count_items){
            
    dataLayer.push({
        ecommerce: null
    });

    dataLayer.push({
        'event': 'initiate_checkout',
        'ecommerce': {
            currencyCode: 'EGP',
            add: {
                products: [{ 
                    items: count_items,
                    total: total,
                }]
            }
        }
    })
}
function checkoutOrder_dataLayer(total,count_items){
            
    dataLayer.push({
        ecommerce: null
    });

    dataLayer.push({
        'event': 'add_payment_info',
        'ecommerce': {
            currencyCode: 'EGP',
            add: {
                products: [{
                    total: total,
                }]
            }
        }
    })
    dataLayer.push({
        ecommerce: null
    });

    dataLayer.push({
        'event': 'purchase',
        'ecommerce': {
            currencyCode: 'EGP',
            add: {
                products: [{ 
                    items: count_items,
                    total: total,
                }]
            }
        }
    })
}


function search_dataLayer(search_string){
            
    dataLayer.push({
        ecommerce: null
    });

    dataLayer.push({
        'event': 'search',
        'ecommerce': {
            currencyCode: 'EGP',
            search_string: search_string
        }
    })
}