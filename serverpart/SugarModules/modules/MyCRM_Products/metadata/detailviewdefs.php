<?php
$module_name = 'MyCRM_Products';
$viewdefs [$module_name] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'buttons' => 
        array (
          0 => 'EDIT',
          1 => 'DUPLICATE',
          2 => 'DELETE',
        ),
      ),
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
            'name' => 'date_support_expires',
            'label' => 'LBL_DATE_SUPPORT_EXPIRES',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'quantity',
            'label' => 'LBL_QUANTITY',
          ),
          1 => 
          array (
            'name' => 'tax_class',
            'label' => 'LBL_TAX_CLASS',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'serial_number',
            'label' => 'LBL_SERIAL_NUMBER',
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
            'name' => 'asset_number',
            'label' => 'LBL_ASSET_NUMBER',
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
            'name' => 'currency_id',
            'label' => 'LBL_CURRENCY',
          ),
          1 => NULL,
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'cost_price',
            'label' => 'LBL_COST_PRICE',
          ),
          1 => NULL,
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'list_price',
            'label' => 'LBL_LIST_PRICE',
          ),
          1 => NULL,
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'discount_price',
            'label' => 'LBL_DISCOUNT_PRICE',
          ),
          1 => NULL,
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'support_name',
            'label' => 'LBL_SUPPORT_NAME',
          ),
          1 => NULL,
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'support_description',
            'label' => 'LBL_SUPPORT_DESCRIPTION',
          ),
          1 => NULL,
        ),
        10 => 
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
