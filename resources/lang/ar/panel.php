<?php
$site_settings = get_site_setting();
if($site_settings->id  == 2){ 
    return [
        'site_title' => '<b style="color: #f5e30b">E</b>rtgal<b style="color: #f5e30b">S</b>tore',  
    ];
}elseif($site_settings->id  == 3){

    return [
        'site_title' => '<b style="color: #f7941d">F</b>igures<b style="color: #f7941d">S</b>tore',  
    ];
}else{ 
    return [
        'site_title' => '<b style="color: #FBAC00">E</b>btekar<b style="color: #FBAC00">S</b>tore',  
    ];
} 
