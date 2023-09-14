<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => false,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'app_roles_structure' => [
        "Access Controll" => [
            'users' => 'c,r,u,d'
        ],

        "Core" => [
            'media' => 'c,d',
            'countries' => 'c,r,u,d',
            'states' => 'c,r,u,d',
            'cities' => 'c,r,u,d',
            'departments' => 'c,r,u,d',
            'teams' => 'c,r,u,d',
            'currencies' => 'c,r,u,d',
            'classes' => 'c,r,u,d',
            'activitylogs' => 'c,r,u,d',
        ],

        'Inventory' => [
            'stores'  => 'c,r,u,d',
            'transfers'  => 'c,r,u,d',
            'items'  => 'c,r,u,d',
            'brands'  => 'c,r,u,d',
            'categories'  => 'c,r,u,d',
            'quantities'  => 'c,r,u,d',
            'units'  => 'c,r,u,d',
            'taxes'  => 'c,r,u,d',
        ],

        'Cms' => [
            'categories' => 'c,r,u,d',
            'services' => 'c,r,u,d',
            'galleries' => 'c,r,u,d',
            'sliders' => 'c,r,u,d',
            'faqs' => 'c,r,u,d',
            'pages' => 'c,r,u,d',
            'solutions' => 'c,r,u,d',
            'partners' => 'c,r,u,d',
            'jobs' => 'c,r,u,d',
            'posts' => 'c,r,u,d',
            'comments' => 'r,d',
            'products' => 'c,r,u,d',
            'messages' => 'r,d',
            'newsletters' => 'r,d',
            'media' => 'c,r,u,d',
            'seo_setting' => 'r,u',
        ],


        'Crm' => [
            'leadstages'  => 'c,r,u,d',
            'sources'  => 'c,r,u,d',
            'leads'  => 'c,r,u,d',
            'clients' => 'c,r,u,d',
            'opportunities' => 'c,r,u,d',
            'quotations' => 'c,r,u,d',
            'salesorders' => 'c,r,u,d',
            'salesinvoices' => 'c,r,u,d',
            'activities' => 'c,r,u,d',
            'contacts' => 'c,r,u,d',
            'paymentterms' => 'c,r,u,d',


        ],

    ],

    'roles_structure' => [
        'super_admin' => [
            'users' => 'c,r,u,d',

            'core_media' => 'c,d',
            'core_countries' => 'c,r,u,d',
            'core_states' => 'c,r,u,d',
            'core_cities' => 'c,r,u,d',
            'core_departments' => 'c,r,u,d',
            'core_teams' => 'c,r,u,d',
            'core_currencies' => 'c,r,u,d',
            'core_classes' => 'c,r,u,d',
            'core_activitylogs' => 'c,r,u,d',

            'inventory_stores'  => 'c,r,u,d',
            'inventory_transfers'  => 'c,r,u,d',
            'inventory_items'  => 'c,r,u,d',
            'inventory_brands'  => 'c,r,u,d',
            'inventory_categories'  => 'c,r,u,d',
            'inventory_quantities'  => 'c,r,u,d',
            'inventory_units'  => 'c,r,u,d',
            'inventory_taxes'  => 'c,r,u,d',

            'cms_categories' => 'c,r,u,d',
            'cms_services' => 'c,r,u,d',
            'cms_galleries' => 'c,r,u,d',
            'cms_sliders' => 'c,r,u,d',
            'cms_faqs' => 'c,r,u,d',
            'cms_pages' => 'c,r,u,d',
            'cms_solutions' => 'c,r,u,d',
            'cms_partners' => 'c,r,u,d',
            'cms_jobs' => 'c,r,u,d',
            'cms_posts' => 'c,r,u,d',
            'cms_comments' => 'r,d',
            'cms_products' => 'c,r,u,d',
            'cms_messages' => 'r,d',
            'cms_newsletters' => 'r,d',
            'cms_media' => 'c,r,u,d',
            'cms_seo_setting' => 'r,u',
            'crm_leadstages'  => 'c,r,u,d',
            'crm_sources'  => 'c,r,u,d',
            'crm_leads'  => 'c,r,u,d',
            'crm_clients' => 'c,r,u,d',
            'crm_opportunities' => 'c,r,u,d',
            'crm_quotations' => 'c,r,u,d',
            'crm_salesorders' => 'c,r,u,d',
            'crm_salesinvoices' => 'c,r,u,d',
            'crm_activities' => 'c,r,u,d',
            'crm_contacts' => 'c,r,u,d',
            'crm_paymentterms' => 'c,r,u,d',

        ],

        'crm_admin' => [
            'core_media' => 'c,d',
            'core_countries' => 'c,r,u,d',
            'core_states' => 'c,r,u,d',
            'core_cities' => 'c,r,u,d',
            'core_departments' => 'c,r,u,d',
            'core_teams' => 'c,r,u,d',
            'core_currencies' => 'c,r,u,d',
            'core_classes' => 'c,r,u,d',
            'core_activitylogs' => 'c,r,u,d',

        ],

        'crm_user' => [
            'cms_categories' => 'c,r,u,d',
            'cms_services' => 'c,r,u,d',
            'cms_galleries' => 'c,r,u,d',
            'cms_sliders' => 'c,r,u,d',
            'cms_faqs' => 'c,r,u,d',
            'cms_pages' => 'c,r,u,d',
            'cms_solutions' => 'c,r,u,d',
            'cms_partners' => 'c,r,u,d',
            'cms_jobs' => 'c,r,u,d',
            'cms_posts' => 'c,r,u,d',
            'cms_comments' => 'r,d',
            'cms_products' => 'c,r,u,d',
            'cms_messages' => 'r,d',
            'cms_newsletters' => 'r,d',
            'cms_media' => 'c,r,u,d',
            'cms_seo_setting' => 'r,u',
            'crm_leadstages'  => 'c,r,u,d',
            'crm_sources'  => 'c,r,u,d',
            'crm_leads'  => 'c,r,u,d',
            'crm_clients' => 'c,r,u,d',
            'crm_opportunities' => 'c,r,u,d',
            'crm_quotations' => 'c,r,u,d',
            'crm_salesorders' => 'c,r,u,d',
            'crm_salesinvoices' => 'c,r,u,d',
            'crm_activities' => 'c,r,u,d',
            'crm_contacts' => 'c,r,u,d',
            'crm_paymentterms' => 'c,r,u,d',


        ]
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
