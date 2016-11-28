<?php
/************************************************

MyCRM OXID-to-Sugar Connector

The Program is provided AS IS, without warranty. You can redistribute it and/or modify it under the terms of the GNU Affero General Public License Version 3 as published by the Free Software Foundation.

For contact:
MyCRM GmbH
Hirschlandstrasse 150
73730 Esslingen
Germany

www.mycrm.de
info@mycrm.de

****************************************************/
$dictionary['MyCRM_Products'] = array(
	'table'=>'mycrm_products',
	'audited'=>true,
	'fields'=>array (
  'mft_part_num' => 
  array (
    'required' => false,
    'name' => 'mft_part_num',
    'vname' => 'LBL_MFT_PART_NUM',
    'type' => 'varchar',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => 0,
    'reportable' => 0,
    'len' => '50',
  ),
  'vendor_part_num' => 
  array (
    'required' => false,
    'name' => 'vendor_part_num',
    'vname' => 'LBL_VENDOR_PART_NUM',
    'type' => 'varchar',
    'massupdate' => 0,
    'comments' => 'Vendor part number',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => 0,
    'reportable' => 0,
    'len' => '50',
  ),
  'discount_price' => 
  array (
    'required' => false,
    'name' => 'discount_price',
    'vname' => 'LBL_DISCOUNT_PRICE',
    'type' => 'currency',
    'massupdate' => 0,
    'comments' => 'Discounted price (&quot;Unit Price&quot; in Quote)',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => 1,
    'reportable' => 0,
  ),
  'currency_id' => 
  array (
    'required' => false,
    'name' => 'currency_id',
    'vname' => 'LBL_CURRENCY',
    'type' => 'id',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => 0,
    'audited' => 0,
    'reportable' => 0,
    'len' => '255',
    'studio' => 'visible',
    'function' => 
    array (
      'name' => 'getCurrencyDropDown',
      'returns' => 'html',
    ),
  ),
  'discount_usdollar' => 
  array (
    'required' => false,
    'name' => 'discount_usdollar',
    'vname' => 'LBL_DISCOUNT_USDOLLAR',
    'type' => 'currency',
    'massupdate' => 0,
    'comments' => 'Discount price expressed in USD',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => 0,
    'reportable' => 0,
  ),
  'tax_class' => 
  array (
    'required' => false,
    'name' => 'tax_class',
    'vname' => 'LBL_TAX_CLASS',
    'type' => 'enum',
    'massupdate' => 0,
    'default' => 'Taxable',
    'comments' => 'Tax classification (ex: Taxable, Non-taxable)',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => 0,
    'reportable' => 0,
    'len' => 100,
    'options' => 'tax_class_dom',
    'studio' => 'visible',
  ),
  'quantity' => 
  array (
    'required' => false,
    'name' => 'quantity',
    'vname' => 'LBL_QUANTITY',
    'type' => 'int',
    'massupdate' => 0,
    'comments' => 'Quantity in use',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => 0,
    'reportable' => 0,
    'len' => '5',
    'disable_num_format' => '',
  ),
  'support_contact' => 
  array (
    'required' => false,
    'name' => 'support_contact',
    'vname' => 'LBL_SUPPORT_CONTACT',
    'type' => 'varchar',
    'massupdate' => 0,
    'comments' => '	Contact for support purposes',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => 0,
    'reportable' => 0,
    'len' => '50',
  ),
  'date_support_starts' => 
  array (
    'required' => false,
    'name' => 'date_support_starts',
    'vname' => 'LBL_DATE_SUPPORT_STARTS',
    'type' => 'date',
    'massupdate' => 0,
    'comments' => 'Support start date',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => 0,
    'reportable' => 0,
  ),
  'serial_number' => 
  array (
    'required' => false,
    'name' => 'serial_number',
    'vname' => 'LBL_SERIAL_NUMBER',
    'type' => 'varchar',
    'massupdate' => 0,
    'comments' => '	Serial number of product in use',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => 0,
    'reportable' => 0,
    'len' => '50',
  ),
  'book_value_date' => 
  array (
    'required' => false,
    'name' => 'book_value_date',
    'vname' => 'LBL_BOOK_VALUE_DATE',
    'type' => 'date',
    'massupdate' => 0,
    'comments' => 'Date of book value for product in use',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => 0,
    'reportable' => 0,
  ),
  'date_purchased' => 
  array (
    'required' => false,
    'name' => 'date_purchased',
    'vname' => 'LBL_DATE_PURCHASED',
    'type' => 'date',
    'massupdate' => 0,
    'comments' => 'Date product purchased',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => 0,
    'reportable' => 0,
  ),
  'list_price' => 
  array (
    'required' => false,
    'name' => 'list_price',
    'vname' => 'LBL_LIST_PRICE',
    'type' => 'currency',
    'massupdate' => 0,
    'comments' => 'List price of product (&quot;List&quot; in Quote)',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => 1,
    'reportable' => 0,
  ),
  'list_usdollar' => 
  array (
    'required' => false,
    'name' => 'list_usdollar',
    'vname' => 'LBL_LIST_USDOLLAR',
    'type' => 'currency',
    'massupdate' => 0,
    'comments' => 'List price expressed in USD',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => 0,
    'reportable' => 0,
  ),
  'website' => 
  array (
    'required' => false,
    'name' => 'website',
    'vname' => 'LBL_URL',
    'type' => 'varchar',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => 0,
    'reportable' => 0,
    'len' => '255',
  ),
  'support_name' => 
  array (
    'required' => false,
    'name' => 'support_name',
    'vname' => '	LBL_SUPPORT_NAME',
    'type' => 'varchar',
    'massupdate' => 0,
    'comments' => '	Name of product for support purposes',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => 0,
    'reportable' => 0,
    'len' => '50',
  ),
  'support_term' => 
  array (
    'required' => false,
    'name' => 'support_term',
    'vname' => 'LBL_SUPPORT_TERM',
    'type' => 'varchar',
    'massupdate' => 0,
    'comments' => 'Term (length) of support contract',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => 0,
    'reportable' => 0,
    'len' => '25',
  ),
  'pricing_formula' => 
  array (
    'required' => false,
    'name' => 'pricing_formula',
    'vname' => 'LBL_PRICING_FORMULA',
    'type' => 'varchar',
    'massupdate' => 0,
    'comments' => 'Pricing formula (ex: Fixed, Markup over Cost)',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => 0,
    'reportable' => 0,
    'len' => '25',
  ),
  'asset_number' => 
  array (
    'required' => false,
    'name' => 'asset_number',
    'vname' => 'LBL_ASSET_NUMBER',
    'type' => 'varchar',
    'massupdate' => 0,
    'comments' => 'Asset tag number of product in use',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => 0,
    'reportable' => 0,
    'len' => '50',
  ),
  'cost_price' => 
  array (
    'required' => false,
    'name' => 'cost_price',
    'vname' => 'LBL_COST_PRICE',
    'type' => 'currency',
    'massupdate' => 0,
    'comments' => 'Product cost (&quot;Cost&quot; in Quote)',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => 1,
    'reportable' => 0,
  ),
  'cost_usdollar' => 
  array (
    'required' => false,
    'name' => 'cost_usdollar',
    'vname' => 'LBL_COST_USDOLLAR',
    'type' => 'currency',
    'massupdate' => 0,
    'comments' => 'Cost expressed in USD',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => 0,
    'reportable' => 0,
  ),
  'status' => 
  array (
    'required' => false,
    'name' => 'status',
    'vname' => 'LBL_STATUS',
    'type' => 'enum',
    'massupdate' => 0,
    'default' => 'Quotes',
    'comments' => 'Product status (ex: Quoted, Ordered, Shipped)',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => 1,
    'reportable' => 0,
    'len' => 100,
    'options' => 'product_status_dom',
    'studio' => 'visible',
  ),
  'weight' => 
  array (
    'required' => false,
    'name' => 'weight',
    'vname' => 'LBL_WEIGHT',
    'type' => 'float',
    'massupdate' => 0,
    'comments' => 'Weight of the product',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => 0,
    'reportable' => 0,
    'len' => '12',
    'precision' => '2',
  ),
  'support_description' => 
  array (
    'required' => false,
    'name' => 'support_description',
    'vname' => 'LBL_SUPPORT_DESCRIPTION',
    'type' => 'varchar',
    'massupdate' => 0,
    'comments' => 'Description of product for support purposes',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => 0,
    'reportable' => 0,
    'len' => '255',
  ),
  'date_support_expires' => 
  array (
    'required' => false,
    'name' => 'date_support_expires',
    'vname' => 'LBL_DATE_SUPPORT_EXPIRES',
    'type' => 'date',
    'massupdate' => 0,
    'comments' => 'Support expiration date',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => 0,
    'reportable' => 0,
  ),
  'pricing_factor' => 
  array (
    'required' => false,
    'name' => 'pricing_factor',
    'vname' => 'LBL_PRICING_FACTOR',
    'type' => 'int',
    'massupdate' => 0,
    'comments' => 'Variable pricing factor depending on pricing_formula',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => 0,
    'reportable' => 0,
    'len' => '4',
    'disable_num_format' => '',
  ),
  'book_value' => 
  array (
    'required' => false,
    'name' => 'book_value',
    'vname' => 'LBL_BOOK_VALUE',
    'type' => 'float',
    'massupdate' => 0,
    'comments' => '	Book value of product in use',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => 0,
    'reportable' => 0,
    'len' => '26',
    'precision' => '6',
  ),
  'oxid' => 
  array (
    'required' => false,
    'name' => 'oxid',
    'vname' => 'LBL_OXID',
    'type' => 'varchar',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => 0,
    'reportable' => 0,
    'len' => '50',
  ),
),
	'relationships'=>array (
),
	'optimistic_lock'=>true,
);
require_once('include/SugarObjects/VardefManager.php');
VardefManager::createVardef('MyCRM_Products','MyCRM_Products', array('basic','assignable'));