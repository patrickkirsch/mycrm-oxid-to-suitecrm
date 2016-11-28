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
$dictionary["mycrm_products_opportunities"] = array (
  'true_relationship_type' => 'many-to-many',
  'relationships' => 
  array (
    'mycrm_products_opportunities' => 
    array (
      'lhs_module' => 'MyCRM_Products',
      'lhs_table' => 'mycrm_products',
      'lhs_key' => 'id',
      'rhs_module' => 'Opportunities',
      'rhs_table' => 'opportunities',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'mycrm_produpportunities_c',
      'join_key_lhs' => 'mycrm_produm_products_ida',
      'join_key_rhs' => 'mycrm_produortunities_idb',
    ),
  ),
  'table' => 'mycrm_produpportunities_c',
  'fields' => 
  array (
    0 => 
    array (
      'name' => 'id',
      'type' => 'varchar',
      'len' => 36,
    ),
    1 => 
    array (
      'name' => 'date_modified',
      'type' => 'datetime',
    ),
    2 => 
    array (
      'name' => 'deleted',
      'type' => 'bool',
      'len' => '1',
      'default' => '0',
      'required' => true,
    ),
    3 => 
    array (
      'name' => 'mycrm_produm_products_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'mycrm_produortunities_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'mycrm_produ_opportunitiesspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'mycrm_produ_opportunities_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'mycrm_produm_products_ida',
        1 => 'mycrm_produortunities_idb',
      ),
    ),
  ),
);
?>
