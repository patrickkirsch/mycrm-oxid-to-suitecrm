<?php
// created: 2011-11-08 16:17:23
$dictionary["opportunities_products"] = array (
  'true_relationship_type' => 'one-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'opportunities_products' => 
    array (
      'lhs_module' => 'Opportunities',
      'lhs_table' => 'opportunities',
      'lhs_key' => 'id',
      'rhs_module' => 'Products',
      'rhs_table' => 'products',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'opportunities_products_c',
      'join_key_lhs' => 'opportu1180ties_ida',
      'join_key_rhs' => 'opportud36aucts_idb',
    ),
  ),
  'table' => 'opportunities_products_c',
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
      'name' => 'opportu1180ties_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'opportud36aucts_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'opportunities_productsspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'opportunities_products_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'opportu1180ties_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'opportunities_products_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'opportud36aucts_idb',
      ),
    ),
  ),
);
?>
