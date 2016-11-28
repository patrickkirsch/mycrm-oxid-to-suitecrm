<?PHP
/************************************************
 *
 * MyCRM OXID-to-Sugar Connector
 *
 * The Program is provided AS IS, without warranty. You can redistribute it and/or modify it under the terms of the GNU
 * Affero General Public License Version 3 as published by the Free Software Foundation.
 *
 * For contact:
 * MyCRM GmbH
 * Hirschlandstrasse 150
 * 73730 Esslingen
 * Germany
 *
 * www.mycrm.de
 * info@mycrm.de
 ****************************************************/
/*
 * Modifications for Oxid 5.2.6 and SuiteCRM
 * 2016, Patrick Kirsch <pk@wellonga.de>
 *
 */
$manifest = [
    'acceptable_sugar_flavors'  => [
        0 => 'CE',
        1 => 'PRO',
        2 => 'ENT',
        3 => 'DEV',
    ],
    'acceptable_sugar_versions' => [
        'regex_matches' => [
            0 => "5\.*\.*.*",
            1 => "6\.*\.*.*",
        ],
    ],

    'name'             => 'SugarCRMOxid',
    'description'      => 'SugarCRM to Oxid Connector',
    'author'           => 'MyCRM',
    'published_date'   => '2011/11/13',
    'version'          => '1.07',
    'type'             => 'module',
    'icon'             => '',
    'is_uninstallable' => true,
    'silent'           => true,
];


$installdefs = [

    'id'             => 'SugarCRMOxid',
    'copy'           => [
        [
            'from' => '<basepath>/root/sync_oxid/',
            'to'   => 'sync_oxid',
        ],
        [
            'from' => '<basepath>/root/sync_oxid.php',
            'to'   => 'sync_oxid.php',
        ],
        [
            'from' => '<basepath>/root/sugarcrm.wsdl',
            'to'   => 'sugarcrm.wsdl',
        ],
        [
            'from' => '<basepath>/root/check_sync_oxid.php',
            'to'   => 'check_sync_oxid.php',
        ],
        [
            'from' => '<basepath>/modules/Administration/DetailViewOxidSync.html',
            'to'   => 'modules/Administration/DetailViewOxidSync.html',
        ],
        [
            'from' => '<basepath>/modules/Administration/DetailViewOxidSync.php',
            'to'   => 'modules/Administration/DetailViewOxidSync.php',
        ],
        [
            'from' => '<basepath>/modules/Administration/EditViewOxidSync.html',
            'to'   => 'modules/Administration/EditViewOxidSync.html',
        ],
        [
            'from' => '<basepath>/modules/Administration/EditViewOxidSync.php',
            'to'   => 'modules/Administration/EditViewOxidSync.php',
        ],
        [
            'from' => '<basepath>/modules/Administration/SaveOxidSync.php',
            'to'   => 'modules/Administration/SaveOxidSync.php',
        ],


    ],
    'menu'           => [
        ['from'      => '<basepath>/modules/Users/MenufilenameSugarOxid.php',
         'to_module' => 'Users',],
    ],
    'administration' => [
        ['from' => '<basepath>/modules/Administration/administration_oxid.ext.php',
        ],
    ],

    'language' =>
        [
            0 =>
                [
                    'from'      => '<basepath>/modules/Administration/language/en_us.sugaroxid.lang.php',
                    'to_module' => 'Administration',
                    'language'  => 'de_de',
                ],
            [
                'from'      => '<basepath>/SugarModules/modules/Accounts/en_us.lang.ext.php',
                'to_module' => 'Accounts',
                'language'  => 'de_de',
            ],

            [
                'from'      => '<basepath>/SugarModules/modules/Contacts/en_us.lang.ext.php',
                'to_module' => 'Contacts',
                'language'  => 'de_de',
            ],
            [
                'from'      => '<basepath>/SugarModules/modules/Leads/en_us.lang.ext.php',
                'to_module' => 'Leads',
                'language'  => 'de_de',
            ],
        ],

    'custom_fields' =>
        [
            'Productsoxid_c' =>
                [
                    'id'              => 'Productsoxid_c',
                    'name'            => 'oxid_c',
                    'label'           => 'LBL_OXID_C',
                    'comments'        => null,
                    'help'            => null,
                    'module'          => 'Products',
                    'type'            => 'varchar',
                    'max_size'        => '255',
                    'require_option'  => '0',
                    'default_value'   => null,
                    'date_modified'   => '2011-09-23 18:28:15',
                    'deleted'         => '0',
                    'audited'         => '0',
                    'mass_update'     => '0',
                    'duplicate_merge' => '0',
                    'reportable'      => '1',
                    'importable'      => 'true',
                    'ext1'            => null,
                    'ext2'            => null,
                    'ext3'            => null,
                    'ext4'            => null,
                ],


            'Accountsvat_id_c'                  =>
                [
                    'id'              => 'Accountsvat_id_c',
                    'name'            => 'vat_id_c',
                    'label'           => 'LBL_VAT_ID_C',
                    'comments'        => null,
                    'help'            => null,
                    'module'          => 'Accounts',
                    'type'            => 'varchar',
                    'max_size'        => '25',
                    'require_option'  => '0',
                    'default_value'   => null,
                    'date_modified'   => '2009-07-25 10:29:52',
                    'deleted'         => '0',
                    'audited'         => '0',
                    'mass_update'     => '0',
                    'duplicate_merge' => '0',
                    'reportable'      => '0',
                    'importable'      => 'true',
                    'ext1'            => null,
                    'ext2'            => null,
                    'ext3'            => null,
                    'ext4'            => null,
                ],
            //array (
            'Contactsvat_id_c'                  =>
                [
                    'id'              => 'Contactsvat_id_c',
                    'name'            => 'vat_id_c',
                    'label'           => 'LBL_VAT_ID_C',
                    'comments'        => null,
                    'help'            => null,
                    'module'          => 'Contacts',
                    'type'            => 'varchar',
                    'max_size'        => '25',
                    'require_option'  => '0',
                    'default_value'   => null,
                    'date_modified'   => '2009-07-25 10:29:52',
                    'deleted'         => '0',
                    'audited'         => '0',
                    'mass_update'     => '0',
                    'duplicate_merge' => '0',
                    'reportable'      => '0',
                    'importable'      => 'true',
                    'ext1'            => null,
                    'ext2'            => null,
                    'ext3'            => null,
                    'ext4'            => null,
                ],
            // array (
            'Leadsvat_id_c'                     =>
                [
                    'id'              => 'Leadsvat_id_c',
                    'name'            => 'vat_id_c',
                    'label'           => 'LBL_VAT_ID_C',
                    'comments'        => null,
                    'help'            => null,
                    'module'          => 'Leads',
                    'type'            => 'varchar',
                    'max_size'        => '25',
                    'require_option'  => '0',
                    'default_value'   => null,
                    'date_modified'   => '2009-07-25 10:29:52',
                    'deleted'         => '0',
                    'audited'         => '0',
                    'mass_update'     => '0',
                    'duplicate_merge' => '0',
                    'reportable'      => '0',
                    'importable'      => 'true',
                    'ext1'            => null,
                    'ext2'            => null,
                    'ext3'            => null,
                    'ext4'            => null,
                ],
            'Contactsoxid_last_change_c'        =>
                [
                    'id'              => 'Contactsoxid_last_change_c',
                    'name'            => 'oxid_last_change_c',
                    'label'           => 'LBL_OXID_LAST_CHANGE_C',
                    'comments'        => null,
                    'help'            => null,
                    'module'          => 'Contacts',
                    'type'            => 'datetime',
                    'max_size'        => null,
                    'require_option'  => '0',
                    'default_value'   => null,
                    'date_modified'   => '2009-07-04 09:11:34',
                    'deleted'         => '0',
                    'audited'         => '0',
                    'mass_update'     => '0',
                    'duplicate_merge' => '0',
                    'reportable'      => '0',
                    'importable'      => 'true',
                    'ext1'            => null,
                    'ext2'            => null,
                    'ext3'            => null,
                    'ext4'            => null,
                ],
            'Contactsoxid_c_last_change_c'      =>
                [
                    'id'              => 'Contactsoxid_c_last_change_c',
                    'name'            => 'oxid_c_last_change_c',
                    'label'           => 'LBL_OXID_LAST_CHANGE_C',
                    'comments'        => null,
                    'help'            => null,
                    'module'          => 'Contacts',
                    'type'            => 'varchar',
                    'max_size'        => '35',
                    'require_option'  => '0',
                    'default_value'   => null,
                    'date_modified'   => '2009-07-04 09:11:34',
                    'deleted'         => '0',
                    'audited'         => '0',
                    'mass_update'     => '0',
                    'duplicate_merge' => '0',
                    'reportable'      => '0',
                    'importable'      => 'true',
                    'ext1'            => null,
                    'ext2'            => null,
                    'ext3'            => null,
                    'ext4'            => null,
                ],
            'Leadsoxid_c_last_change_c'         =>
                [
                    'id'              => 'Leadsoxid_c_last_change_c',
                    'name'            => 'oxid_c_last_change_c',
                    'label'           => 'LBL_OXID_LAST_CHANGE_C',
                    'comments'        => null,
                    'help'            => null,
                    'module'          => 'Leads',
                    'type'            => 'varchar',
                    'max_size'        => '35',
                    'require_option'  => '0',
                    'default_value'   => null,
                    'date_modified'   => '2009-07-04 09:11:34',
                    'deleted'         => '0',
                    'audited'         => '0',
                    'mass_update'     => '0',
                    'duplicate_merge' => '0',
                    'reportable'      => '0',
                    'importable'      => 'true',
                    'ext1'            => null,
                    'ext2'            => null,
                    'ext3'            => null,
                    'ext4'            => null,
                ],
            'Contactsoxidsugar_last_change_c'   =>
                [
                    'id'              => 'Contactsoxidsugar_last_change_c',
                    'name'            => 'oxidsugar_last_change_c',
                    'label'           => 'LBL_OXIDSUGAR_LAST_CHANGE_C',
                    'comments'        => null,
                    'help'            => null,
                    'module'          => 'Contacts',
                    'type'            => 'datetime',
                    'max_size'        => null,
                    'require_option'  => '0',
                    'default_value'   => null,
                    'date_modified'   => '2009-07-04 09:12:32',
                    'deleted'         => '0',
                    'audited'         => '0',
                    'mass_update'     => '0',
                    'duplicate_merge' => '0',
                    'reportable'      => '0',
                    'importable'      => 'true',
                    'ext1'            => null,
                    'ext2'            => null,
                    'ext3'            => null,
                    'ext4'            => null,
                ],
            'Leadsoxid_c'                       =>
                [
                    'id'              => 'Leadsoxid_c',
                    'name'            => 'oxid_c',
                    'label'           => 'LBL_OXID_C',
                    'comments'        => null,
                    'help'            => null,
                    'module'          => 'Leads',
                    'type'            => 'varchar',
                    'max_size'        => '255',
                    'require_option'  => '0',
                    'default_value'   => null,
                    'date_modified'   => '2009-07-02 13:41:50',
                    'deleted'         => '0',
                    'audited'         => '0',
                    'mass_update'     => '0',
                    'duplicate_merge' => '0',
                    'reportable'      => '0',
                    'importable'      => 'true',
                    'ext1'            => null,
                    'ext2'            => null,
                    'ext3'            => null,
                    'ext4'            => null,
                ],
            'Leadsoxcustnr_c'                   =>
                [
                    'id'              => 'Leadsoxcustnr_c',
                    'name'            => 'oxcustnr_c',
                    'label'           => 'LBL_OXCUSTNR_C',
                    'comments'        => null,
                    'help'            => null,
                    'module'          => 'Leads',
                    'type'            => 'varchar',
                    'max_size'        => '255',
                    'require_option'  => '0',
                    'default_value'   => null,
                    'date_modified'   => '2009-07-02 13:42:02',
                    'deleted'         => '0',
                    'audited'         => '0',
                    'mass_update'     => '0',
                    'duplicate_merge' => '0',
                    'reportable'      => '0',
                    'importable'      => 'true',
                    'ext1'            => null,
                    'ext2'            => null,
                    'ext3'            => null,
                    'ext4'            => null,
                ],
            'Leadsoxcustid_c'                   =>
                [
                    'id'              => 'Leadsoxcustid_c',
                    'name'            => 'oxcustid_c',
                    'label'           => 'LBL_OXCUSTID_C',
                    'comments'        => null,
                    'help'            => null,
                    'module'          => 'Leads',
                    'type'            => 'varchar',
                    'max_size'        => '255',
                    'require_option'  => '0',
                    'default_value'   => null,
                    'date_modified'   => '2009-07-02 13:42:13',
                    'deleted'         => '0',
                    'audited'         => '0',
                    'mass_update'     => '0',
                    'duplicate_merge' => '0',
                    'reportable'      => '0',
                    'importable'      => 'true',
                    'ext1'            => null,
                    'ext2'            => null,
                    'ext3'            => null,
                    'ext4'            => null,
                ],
            'Leadsorder_count_c'                =>
                [
                    'id'              => 'Leadsorder_count_c',
                    'name'            => 'order_count_c',
                    'label'           => 'LBL_ORDER_COUNT_C',
                    'comments'        => null,
                    'help'            => null,
                    'module'          => 'Leads',
                    'type'            => 'int',
                    'max_size'        => '11',
                    'require_option'  => '0',
                    'default_value'   => null,
                    'date_modified'   => '2009-07-02 13:42:46',
                    'deleted'         => '0',
                    'audited'         => '0',
                    'mass_update'     => '0',
                    'duplicate_merge' => '0',
                    'reportable'      => '0',
                    'importable'      => 'true',
                    'ext1'            => null,
                    'ext2'            => null,
                    'ext3'            => null,
                    'ext4'            => null,
                ],
            'Leadsoxid_last_change_c'           =>
                [
                    'id'              => 'Leadsoxid_last_change_c',
                    'name'            => 'oxid_last_change_c',
                    'label'           => 'LBL_OXID_LAST_CHANGE_C',
                    'comments'        => null,
                    'help'            => null,
                    'module'          => 'Leads',
                    'type'            => 'date',
                    'max_size'        => null,
                    'require_option'  => '0',
                    'default_value'   => null,
                    'date_modified'   => '2009-07-04 09:11:17',
                    'deleted'         => '0',
                    'audited'         => '0',
                    'mass_update'     => '0',
                    'duplicate_merge' => '0',
                    'reportable'      => '0',
                    'importable'      => 'true',
                    'ext1'            => null,
                    'ext2'            => null,
                    'ext3'            => null,
                    'ext4'            => null,
                ],
            'Leadsoxidsugar_last_change_c'      =>
                [
                    'id'              => 'Leadsoxidsugar_last_change_c',
                    'name'            => 'oxidsugar_last_change_c',
                    'label'           => 'LBL_OXIDSUGAR_LAST_CHANGE_C',
                    'comments'        => null,
                    'help'            => null,
                    'module'          => 'Leads',
                    'type'            => 'date',
                    'max_size'        => null,
                    'require_option'  => '0',
                    'default_value'   => null,
                    'date_modified'   => '2009-07-04 09:12:15',
                    'deleted'         => '0',
                    'audited'         => '0',
                    'mass_update'     => '0',
                    'duplicate_merge' => '0',
                    'reportable'      => '0',
                    'importable'      => 'true',
                    'ext1'            => null,
                    'ext2'            => null,
                    'ext3'            => null,
                    'ext4'            => null,
                ],
            'Opportunitiesoxid_order_number_c'  =>
                [
                    'id'              => 'Opportunitiesoxid_order_number_c',
                    'name'            => 'oxid_order_number_c',
                    'label'           => 'LBL_OXID_ORDER_NUMBER_C',
                    'comments'        => null,
                    'help'            => null,
                    'module'          => 'Opportunities',
                    'type'            => 'varchar',
                    'max_size'        => '255',
                    'require_option'  => '0',
                    'default_value'   => null,
                    'date_modified'   => '2009-07-01 10:19:38',
                    'deleted'         => '0',
                    'audited'         => '0',
                    'mass_update'     => '0',
                    'duplicate_merge' => '0',
                    'reportable'      => '0',
                    'importable'      => 'true',
                    'ext1'            => null,
                    'ext2'            => null,
                    'ext3'            => null,
                    'ext4'            => null,
                ],
            'Opportunitiesoxid_order_id_c'      =>
                [
                    'id'              => 'Opportunitiesoxid_order_id_c',
                    'name'            => 'oxid_order_id_c',
                    'label'           => 'LBL_OXID_ORDER_ID_C',
                    'comments'        => null,
                    'help'            => null,
                    'module'          => 'Opportunities',
                    'type'            => 'varchar',
                    'max_size'        => '255',
                    'require_option'  => '0',
                    'default_value'   => null,
                    'date_modified'   => '2009-07-01 10:19:52',
                    'deleted'         => '0',
                    'audited'         => '0',
                    'mass_update'     => '0',
                    'duplicate_merge' => '0',
                    'reportable'      => '0',
                    'importable'      => 'true',
                    'ext1'            => null,
                    'ext2'            => null,
                    'ext3'            => null,
                    'ext4'            => null,
                ],
            'Opportunitiescurrency_rate_c'      =>
                [
                    'id'              => 'Opportunitiescurrency_rate_c',
                    'name'            => 'currency_rate_c',
                    'label'           => 'LBL_CURRENCY_RATE_C',
                    'comments'        => null,
                    'help'            => null,
                    'module'          => 'Opportunities',
                    'type'            => 'float',
                    'max_size'        => '18',
                    'require_option'  => '0',
                    'default_value'   => null,
                    'date_modified'   => '2009-07-01 13:34:30',
                    'deleted'         => '0',
                    'audited'         => '0',
                    'mass_update'     => '0',
                    'duplicate_merge' => '0',
                    'reportable'      => '0',
                    'importable'      => 'true',
                    'ext1'            => '4',
                    'ext2'            => null,
                    'ext3'            => null,
                    'ext4'            => null,
                ],
            'Opportunitiesfirst_contact_name_c' =>
                [
                    'id'              => 'Opportunitiesfirst_contact_name_c',
                    'name'            => 'first_contact_name_c',
                    'label'           => 'LBL_FIRST_CONTACT_NAME',
                    'comments'        => null,
                    'help'            => null,
                    'module'          => 'Opportunities',
                    'type'            => 'varchar',
                    'max_size'        => '25',
                    'require_option'  => '0',
                    'default_value'   => null,
                    'date_modified'   => '2009-07-21 10:43:27',
                    'deleted'         => '0',
                    'audited'         => '0',
                    'mass_update'     => '0',
                    'duplicate_merge' => '0',
                    'reportable'      => '0',
                    'importable'      => 'true',
                    'ext1'            => null,
                    'ext2'            => null,
                    'ext3'            => null,
                    'ext4'            => null,
                ],

            'Contactsoxid_newsletter_c' =>
                [
                    'id'              => 'Contactsoxid_newsletter_c',
                    'name'            => 'oxid_newsletter_c',
                    'label'           => 'LBL_OXID_NEWSLETTER_C',
                    'comments'        => null,
                    'help'            => null,
                    'module'          => 'Contacts',
                    'type'            => 'bool',
                    'max_size'        => '255',
                    'require_option'  => '0',
                    'default_value'   => '0',
                    'date_modified'   => '2011-10-27 14:13:42',
                    'deleted'         => '0',
                    'audited'         => '0',
                    'mass_update'     => '0',
                    'duplicate_merge' => '0',
                    'reportable'      => '1',
                    'importable'      => 'true',
                    'ext1'            => null,
                    'ext2'            => null,
                    'ext3'            => null,
                    'ext4'            => null,
                ],
            'Leadsoxid_newsletter_c'    =>
                [
                    'id'              => 'Leadsoxid_newsletter_c',
                    'name'            => 'oxid_newsletter_c',
                    'label'           => 'LBL_OXID_NEWSLETTER_C',
                    'comments'        => null,
                    'help'            => null,
                    'module'          => 'Leads',
                    'type'            => 'bool',
                    'max_size'        => '255',
                    'require_option'  => '0',
                    'default_value'   => '0',
                    'date_modified'   => '2011-10-27 14:13:17',
                    'deleted'         => '0',
                    'audited'         => '0',
                    'mass_update'     => '0',
                    'duplicate_merge' => '0',
                    'reportable'      => '1',
                    'importable'      => 'true',
                    'ext1'            => null,
                    'ext2'            => null,
                    'ext3'            => null,
                    'ext4'            => null,
                ],

        ],

    'layoutfields' =>
        [
            [
                'additional_fields' =>
                    ['Contacts' => 'oxid_newsletter_c', 'Leads' => 'oxid_newsletter_c',],
            ],
        ],


    'beans' => [
    ],

];


$installdefs_p = [
    'beans'         =>
        [
            0 =>
                [
                    'module' => 'MyCRM_Products',
                    'class'  => 'MyCRM_Products',
                    'path'   => 'modules/MyCRM_Products/MyCRM_Products.php',
                    'tab'    => true,
                ],
        ],
    'layoutdefs'    =>
        [
            0 =>
                [
                    'from'      => '<basepath>/SugarModules/relationships/layoutdefs/Opportunities.php',
                    'to_module' => 'Opportunities',
                ],
            1 =>
                [
                    'from'      => '<basepath>/SugarModules/relationships/layoutdefs/MyCRM_Products.php',
                    'to_module' => 'MyCRM_Products',
                ],
        ],
    'relationships' =>
        [
            0 =>
                [
                    'meta_data' => '<basepath>/SugarModules/relationships/relationships/mycrm_products_opportunitiesMetaData.php',
                ],
        ],
    'image_dir'     => '<basepath>/icons',
    'copy'          =>
        [
            0 =>
                [
                    'from' => '<basepath>/SugarModules/modules/MyCRM_Products',
                    'to'   => 'modules/MyCRM_Products',
                ],
        ],
    'language'      =>
        [
            0 =>
                [
                    'from'      => '<basepath>/SugarModules/relationships/language/Opportunities.php',
                    'to_module' => 'Opportunities',
                    'language'  => 'de_de',
                ],
            1 =>
                [
                    'from'      => '<basepath>/SugarModules/relationships/language/MyCRM_Products.php',
                    'to_module' => 'MyCRM_Products',
                    'language'  => 'de_de',
                ],
            2 =>
                [
                    'from'      => '<basepath>/SugarModules/language/application/en_us.lang.php',
                    'to_module' => 'application',
                    'language'  => 'de_de',
                ],

            [
                'from'      => '<basepath>/SugarModules/Products/prod_lang.php',
                'to_module' => 'Products',
                'language'  => 'de_de',
            ],

        ],
    'vardefs'       =>
        [
            0 =>
                [
                    'from'      => '<basepath>/SugarModules/relationships/vardefs/Opportunities.php',
                    'to_module' => 'Opportunities',
                ],
            1 =>
                [
                    'from'      => '<basepath>/SugarModules/relationships/vardefs/MyCRM_Products.php',
                    'to_module' => 'MyCRM_Products',
                ],
        ],
    'layoutfields'  =>
        [
            [
                'additional_fields' =>
                    ['Accounts' => 'vat_id_c',],
            ],
        ],

];

$installdefs_pro = [
    'beans' =>
        [

        ],
    'copy'  =>
        [

            4 =>
                [
                    'from' => '<basepath>/Extension/modules/Opportunities/Ext/Language/en_us.customopportunities_products_1.php',
                    'to'   => 'custom/Extension/modules/Opportunities/Ext/Language/en_us.customopportunities_products_1.php',
                ],

            22 =>
                [
                    'from' => '<basepath>/Extension/modules/Opportunities/Ext/Layoutdefs/opportunities_products_Opportunities.php',
                    'to'   => 'custom/Extension/modules/Opportunities/Ext/Layoutdefs/opportunities_products_Opportunities.php',
                ],
            23 =>
                [
                    'from' => '<basepath>/Extension/modules/Opportunities/Ext/Vardefs/opportunities_products_Opportunities.php',
                    'to'   => 'custom/Extension/modules/Opportunities/Ext/Vardefs/opportunities_products_Opportunities.php',
                ],
            [
                'from' => '<basepath>/Extension/modules/Products/Ext/Vardefs/opportunities_products_Products.php',
                'to'   => 'custom/Extension/modules/Products/Ext/Vardefs/opportunities_products_Products.php',
            ],
        ],

    'relationships' =>
        [
            0 =>
                [
                    'meta_data' => '<basepath>/SugarModules/relationships/relationships/opportunities_productsMetaData.php',
                ],
        ],
];


global $current_user;
global $sugar_flavor;
if ($sugar_flavor == "CE") {
    $installdefs = array_merge_recursive($installdefs_p, $installdefs);
} else {
    $installdefs = array_merge_recursive($installdefs_pro, $installdefs);

}

if ($sugar_flavor == "CE1") {
    $installdefs['beans']         = [
        0 =>
            [
                'module' => 'MyCRM_Products',
                'class'  => 'MyCRM_Products',
                'path'   => 'modules/MyCRM_Products/MyCRM_Products.php',
                'tab'    => true,
            ],
    ];
    $installdefs['image_dir']     = '<basepath>/icons';
    $installdefs['copy'][]        = [
        'from' => '<basepath>/SugarModules/modules/MyCRM_Products',
        'to'   => 'modules/MyCRM_Products',
    ];
    $installdefs['relationships'] = [
        'meta_data' => '<basepath>/SugarModules/relationships/relationships/mycrm_products_opportunitiesMetaData.php',
    ];
    $installdefs['layoutdefs']    = [
        0 =>
            [
                'from'      => '<basepath>/SugarModules/relationships/layoutdefs/Opportunities.php',
                'to_module' => 'Opportunities',
            ],
        1 =>
            [
                'from'      => '<basepath>/SugarModules/relationships/layoutdefs/MyCRM_Products.php',
                'to_module' => 'MyCRM_Products',
            ],
    ];
    $installdefs['language'][]    = [
        'from'      => '<basepath>/SugarModules/relationships/language/Opportunities.php',
        'to_module' => 'Opportunities',
        'language'  => 'de_de',
    ];


    $installdefs['language'][] = [
        'from'      => '<basepath>/SugarModules/relationships/language/MyCRM_Products.php',
        'to_module' => 'MyCRM_Products',
        'language'  => 'de_de',
    ];
    $installdefs['language'][] = [
        'from'      => '<basepath>/SugarModules/language/application/en_us.lang.php',
        'to_module' => 'application',
        'language'  => 'de_de',
    ];

    $installdefs['vardefs'][] = [
        'from'      => '<basepath>/SugarModules/relationships/vardefs/Opportunities.php',
        'to_module' => 'Opportunities',
    ];

    $installdefs['vardefs'][] = [
        'from'      => '<basepath>/SugarModules/relationships/vardefs/MyCRM_Products.php',
        'to_module' => 'MyCRM_Products',
    ];


}


?>
