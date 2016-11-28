<?php

<?php
// created: 2009-07-24 11:18:08
$layout_defs["MyCRM_Products"]["subpanel_setup"]["mycrm_products_opportunities"] = array (
  'order' => 100,
  'module' => 'Opportunities',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_MYCRM_PRODUCTS_OPPORTUNITIES_FROM_OPPORTUNITIES_TITLE',
  'get_subpanel_data' => 'mycrm_products_opportunities',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopCreateButton',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'popup_module' => 'Opportunities',
      'mode' => 'MultiSelect',
    ),
  ),
);
?>
