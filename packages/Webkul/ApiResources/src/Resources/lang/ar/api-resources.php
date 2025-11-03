<?php

return [
    'auth' => [
        'login' => [
            'invalid_credentials' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.',
            'success'             => 'تم تسجيل الدخول بنجاح.',
        ],
        'logout' => [
            'success' => 'تم تسجيل الخروج بنجاح.',
        ],
        'get' => [
            'success' => 'تم استرجاع بيانات المستخدم بنجاح.',
        ],
        'update' => [
            'success' => 'تم تحديث الملف الشخصي بنجاح.',
        ],
        'forgot_password' => [
            'link_sent'      => 'تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني.',
            'user_not_found' => 'المستخدم غير موجود.',
            'failed'         => 'فشل في إرسال رابط إعادة تعيين كلمة المرور.',
            'try_again'      => 'يرجى المحاولة مرة أخرى لاحقاً.',
        ],
    ],
    'validations' => [
        'channel' => [
            // Code field
            'code_required' => 'رمز القناة مطلوب.',
            'code_unique'   => 'يجب أن يكون رمز القناة فريداً.',

            // Name field
            'name_required' => 'اسم القناة مطلوب.',

            // Locale fields
            'default_locale_id_required' => 'اللغة الافتراضية مطلوبة.',
            'default_locale_id_exists'   => 'اللغة الافتراضية المحددة غير موجودة.',
            'locales_required'           => 'يجب تحديد لغة واحدة على الأقل.',
            'locales_exists'             => 'لغة واحدة أو أكثر من اللغات المحددة غير موجودة.',

            // Inventory sources
            'inventory_sources_required' => 'يجب تحديد مصدر مخزون واحد على الأقل.',
            'inventory_sources_exists'   => 'مصدر مخزون واحد أو أكثر من مصادر المخزون المحددة غير موجودة.',

            // Category
            'root_category_id_required' => 'فئة الجذر مطلوبة.',
            'root_category_id_exists'   => 'فئة الجذر المحددة غير موجودة.',

            // Currency fields
            'currencies_required'       => 'يجب تحديد عملة واحدة على الأقل.',
            'currencies_exists'         => 'عملة واحدة أو أكثر من العملات المحددة غير موجودة.',
            'base_currency_id_required' => 'العملة الأساسية مطلوبة.',
            'base_currency_id_exists'   => 'العملة الأساسية المحددة غير موجودة.',

            // Meta fields
            'meta_title_required'       => 'عنوان البيانات الوصفية مطلوب.',
            'meta_keywords_required'    => 'كلمات البيانات الوصفية مطلوبة.',
            'meta_description_required' => 'وصف البيانات الوصفية مطلوب.',

            // Hostname
            'hostname_unique' => 'اسم المضيف يجب أن يكون فريداً.',
        ],
    ],
];
