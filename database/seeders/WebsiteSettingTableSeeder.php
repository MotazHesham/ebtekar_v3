<?php

namespace Database\Seeders;

use App\Models\WebsiteSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WebsiteSettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $websites = [
            [ 
                'id' => 1,
                'site_name' => 'EbtekarStore',
                'css_file_name' => 'color3.css',
                'domains' => 'local.ebtekar_v3',
                'description_seo' => ' ',
                'keywords_seo' => ' ',
                'author_seo' => 'EbtekarStore',
                'sitemap_link_seo' => 'example.com',
                'address' => 'وسط البلد',
                'description' => 'أفكار مبتنتهيش ❤️',
                'phone_number' => '01000586206',
                'email' => 'support@ebtekarstore.net',
                'facebook' => 'https://www.facebook.com/ebtekar.stor',
                'instagram' => 'https://www.instagram.com/ebtekarstore',
                'twitter' => 'https://www.twitter.com',
                'telegram' => 'https://telegram.me/Ebtekarstor',
                'linkedin' => 'https://linkedin.com',
                'whatsapp' => 'https://wa.me/message/D2F523WA4QLXM1',
                'youtube' => 'https://www.youtube.com/channel/UCdc3TF6fHfe95TUyE4xt-Pg',
                'google_plus' => 'https://google.com',
                'welcome_message' => 'Hello , Welcome to EbtekarStore',
                'video_instructions' => 'https://www.youtube.com/watch?v=RrZ7hcjYUxM',
                'delivery_system' => 'ebtekar',
                'borrow_password' => '123123',
                'designer_id' => 1,
                'preparer_id' => 1,
                'manufacturer_id' => 1,
                'shipment_id' => 1,
            ],
            [ 
                'id' => 2,
                'site_name' => 'ErtgalStore',
                'css_file_name' => 'color1.css',
                'domains' => 'local.ertgal',
                'description_seo' => ' ',
                'keywords_seo' => ' ',
                'author_seo' => 'ErtgalStore',
                'sitemap_link_seo' => 'example.com',
                'address' => 'وسط البلد',
                'description' => 'أفكار مبتنتهيش ❤️',
                'phone_number' => '01000586206',
                'email' => 'support@ertgalstore.net',
                'facebook' => 'https://www.facebook.com/ertgal.stor',
                'instagram' => 'https://www.instagram.com/ertgalstore',
                'twitter' => 'https://www.twitter.com',
                'telegram' => 'https://telegram.me/Ertgalstor',
                'linkedin' => 'https://linkedin.com',
                'whatsapp' => 'https://wa.me/message/D2F523WA4QLXM1',
                'youtube' => 'https://www.youtube.com/channel/UCdc3TF6fHfe95TUyE4xt-Pg',
                'google_plus' => 'https://google.com',
                'welcome_message' => 'Hello , Welcome to ErtgalStore',
                'video_instructions' => 'https://www.youtube.com/watch?v=RrZ7hcjYUxM',
                'delivery_system' => 'ertgal',
                'borrow_password' => '123123',
                'designer_id' => 1,
                'preparer_id' => 1,
                'manufacturer_id' => 1,
                'shipment_id' => 1,
            ],
            [ 
                'id' => 3,
                'site_name' => 'FiguresStore',
                'css_file_name' => 'color2.css',
                'domains' => 'local.figures',
                'description_seo' => ' ',
                'keywords_seo' => ' ',
                'author_seo' => 'FiguresStore',
                'sitemap_link_seo' => 'example.com',
                'address' => 'وسط البلد',
                'description' => 'أفكار مبتنتهيش ❤️',
                'phone_number' => '01000586206',
                'email' => 'support@figuresstore.net',
                'facebook' => 'https://www.facebook.com/figures.stor',
                'instagram' => 'https://www.instagram.com/figuresstore',
                'twitter' => 'https://www.twitter.com',
                'telegram' => 'https://telegram.me/Figuresstor',
                'linkedin' => 'https://linkedin.com',
                'whatsapp' => 'https://wa.me/message/D2F523WA4QLXM1',
                'youtube' => 'https://www.youtube.com/channel/UCdc3TF6fHfe95TUyE4xt-Pg',
                'google_plus' => 'https://google.com',
                'welcome_message' => 'Hello , Welcome to FiguresStore',
                'video_instructions' => 'https://www.youtube.com/watch?v=RrZ7hcjYUxM',
                'delivery_system' => 'figures',
                'borrow_password' => '123123',
                'designer_id' => 1,
                'preparer_id' => 1,
                'manufacturer_id' => 1,
                'shipment_id' => 1,
            ],
        ];

        WebsiteSetting::insert($websites);
    }
}
