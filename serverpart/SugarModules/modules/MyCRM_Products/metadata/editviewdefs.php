<?php
$module_name = 'MyCRM_Products';
$viewdefs [$module_name] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
    ),
    'panels' => 
    array (
      'default' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'name',
            'label' => 'LBL_NAME',
          ),
          1 => 
          array (
            'name' => 'status',
            'label' => 'LBL_STATUS',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'serial_number',
            'label' => 'LBL_SERIAL_NUMBER',
          ),
          1 => 
          array (
            'name' => 'date_purchased',
            'label' => 'LBL_DATE_PURCHASED',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'created_by_name',
            'label' => 'LBL_CREATED',
          ),
          1 => 
          array (
            'name' => 'date_support_starts',
            'label' => 'LBL_DATE_SUPPORT_STARTS',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'date_support_expires',
            'label' => 'LBL_DATE_SUPPORT_EXPIRES',
          ),
        ),
      ),
      'lbl_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'list_price',
            'label' => 'LBL_LIST_PRICE',
          ),
          1 => 
          array (
            'name' => 'currency_id',
            'label' => 'LBL_CURRENCY',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'discount_price',
            'label' => 'LBL_DISCOUNT_PRICE',
          ),
          1 => 
          array (
            'name' => 'quantity',
            'label' => 'LBL_QUANTITY',
          ),
        ),
      ),
      'lbl_panel2' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'website',
            'label' => 'LBL_URL',
          ),
          1 => 
          array (
            'name' => 'tax_class',
            'label' => 'LBL_TAX_CLASS',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'mft_part_num',
            'label' => 'LBL_MFT_PART_NUM',
          ),
          1 => 
          array (
            'name' => 'weight',
            'label' => 'LBL_WEIGHT',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'vendor_part_num',
            'label' => 'LBL_VENDOR_PART_NUM',
          ),
          1 => 
          array (
            'name' => 'support_contact',
            'label' => 'LBL_SUPPORT_CONTACT',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'support_name',
            'label' => '	LBL_SUPPORT_NAME',
          ),
          1 => 
          array (
            'name' => 'support_term',
            'label' => 'LBL_SUPPORT_TERM',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'support_description',
            'label' => 'LBL_SUPPORT_DESCRIPTION',
          ),
          1 => NULL,
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'description',
            'label' => 'LBL_DESCRIPTION',
          ),
        ),
      ),
    ),
  ),
);
?>
