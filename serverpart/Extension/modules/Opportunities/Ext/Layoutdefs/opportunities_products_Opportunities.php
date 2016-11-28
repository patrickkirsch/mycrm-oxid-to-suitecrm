<?php
// created: 2011-11-08 16:17:23
$layout_defs["Opportunities"]["subpanel_setup"]["opportunities_products"] = array (
  'order' => 100,
  'module' => 'Products',
  'subpanel_name' => 'ForAccounts',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_OPPORTUNITIES_PRODUCTS_FROM_PRODUCTS_TITLE',
  'get_subpanel_data' => 'opportunities_products',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopButtonQuickCreate',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
  ),
);
