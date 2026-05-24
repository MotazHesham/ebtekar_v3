<?php

return [
    'timeline' => [
        'created'                 => 'تم إنشاء سجل الشحن',
        'status_changed'          => 'تغيير الحالة: :from → :to',
        'assigned_to'             => 'تم تعيين الأوردر إلى: :name',
        'admin_managed_auto_received' => 'استلام في المخزن (شريك بإدارة داخلية — بدون مسح استلام)',
        'marked_delivered'        => 'تم تحديد الطلب كمسلّم',
        'revert_handoff'          => 'إرجاع الطلب من مرحلة تسليم الشريك',
        'cancel_delivered'        => 'إلغاء حالة تم التسليم',
        'cancel_return'           => 'إلغاء حالة المرتجع',
    ],
    'dashboard' => [
        'today_received'      => 'مستلمة اليوم',
        'today_delivered'     => 'مسلّمة اليوم',
        'today_returns'       => 'مرتجعات اليوم',
        'on_delivery'         => 'قيد التوصيل',
        'total_shipping'      => 'إجمالي تكلفة الشحن',
        'total_cod'           => 'إجمالي التحصيل (COD)',
        'total_cod_collect'   => 'إجمالي المطلوب تحصيله',
        'total_cod_collected' => 'إجمالي المحصّل',
        'total_returns_amount'=> 'إجمالي مبالغ المرتجعات',
    ],
    'fields' => [
        'full_address' => 'العنوان كامل',
        'row_num'      => '#',
    ],
    'actions' => [
        'mark_delivered' => 'تم التسليم',
        'revert_handoff' => 'إرجاع للمرحلة السابقة',
        'export_selected'=> 'تصدير المحدد',
        'export_all'     => 'تصدير الكل',
    ],
    'list' => [
        'selected_cod_total' => 'إجمالي المتبقي للتحصيل (المحدد)',
    ],
    'messages' => [
        'status_updated'   => 'تم تحديث الحالة بنجاح',
        'confirm_status'   => 'هل أنت متأكد؟',
        'confirm_ok'       => 'موافق',
        'confirm_cancel'   => 'إلغاء',
    ],
    'scan' => [
        'menu'        => 'مسح الأوردر',
        'title'       => 'مسح أوردر التوصيل',
        'enter_code'  => 'أدخل رقم الأوردر أو الباركود',
        'or_manual'   => 'أو أدخل الرمز يدوياً',
        'lookup'      => 'بحث',
        'order_found' => 'بيانات الأوردر',
    ],
    'admin_actions' => [
        'title'            => 'إجراءات الإدارة',
        'cancel_delivered' => 'إلغاء «تم التسليم»',
        'cancel_return'    => 'إلغاء «مرتجع»',
    ],
    'errors' => [
        'cannot_mark_delivered'   => 'لا يمكن تحديد هذا الطلب كمسلّم في حالته الحالية',
        'cannot_revert_handoff'   => 'لا يمكن الإرجاع إلا من حالة «تم التسليم لشريك الشحن»',
        'not_delivered'           => 'الطلب ليس في حالة تم التسليم',
        'not_return'              => 'الطلب ليس في حالة مرتجع',
        'courier_partner_mismatch'=> 'المندوب لا يتبع نفس شريك الشحن',
    ],
];
