<?php

return [
    'queue_hint'           => 'طلبات جاهزة للتوزيع (مستلمة في المخزن): :count',
    'assigned'             => 'تم توزيع الطلب :num على المندوب',
    'invalid_status'       => 'الطلب :num ليس في حالة «مستلم في المخزن»',
    'courier_inactive'     => 'المندوب غير نشط',
    'partner_mismatch'     => 'المندوب لا يتبع نفس شريك الشحن',
    'courier_at_capacity'  => 'المندوب وصل للحد الأقصى للطلبات النشطة',
    'shipment_not_found'   => 'الشحنة غير موجودة',
    'no_courier_available' => 'لا يوجد مندوب متاح للتوزيع التلقائي',
    'batch_done'           => 'اكتمل التوزيع: نجاح :success — فشل :failed',
];
