<?php

if(config('app.name') == 'ErtgalStore'){ 
    return [
        'site_title' => '<b style="color: #f5e30b">E</b>rtgal<b style="color: #f5e30b">S</b>tore', 
        'receiptCompany' => [
            'title'          => 'فواتير أرتجال',
            'title_singular' => 'فاتورة أرتجال',
        ],
    ];
}elseif(config('app.name') == 'FiguresStore'){

    return [
        'site_title' => '<b style="color: #f7941d">F</b>igures<b style="color: #f7941d">S</b>tore', 
        'receiptCompany' => [
            'title'          => 'فواتير فيجرز',
            'title_singular' => 'فاتورة فيجرز',
        ],
    ];
}elseif(config('app.name') == 'EbtekarStore'){ 
    return [
        'site_title' => '<b style="color: #FBAC00">E</b>btekar<b style="color: #FBAC00">S</b>tore', 
        'receiptCompany' => [
            'title'          => 'فواتير أبتكار',
            'title_singular' => 'فاتورة أبتكار',
        ],
    ];
} 
