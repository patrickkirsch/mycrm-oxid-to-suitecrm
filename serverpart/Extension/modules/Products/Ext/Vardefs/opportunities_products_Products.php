<?php
// created: 2011-11-08 16:17:23
$dictionary["Product"]["fields"]["opportunities_products"] = array (
  'name' => 'opportunities_products',
  'type' => 'link',
  'relationship' => 'opportunities_products',
  'source' => 'non-db',
  'vname' => 'LBL_OPPORTUNITIES_PRODUCTS_FROM_OPPORTUNITIES_TITLE',
);
$dictionary["Product"]["fields"]["opportunies_products_name"] = array (
  'name' => 'opportunies_products_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_OPPORTUNITIES_PRODUCTS_FROM_OPPORTUNITIES_TITLE',
  'save' => true,
  'id_name' => 'opportu1180ties_ida',
  'link' => 'opportunities_products',
  'table' => 'opportunities',
  'module' => 'Opportunities',
  'rname' => 'name',
);
$dictionary["Product"]["fields"]["opportu1180ties_ida"] = array (
  'name' => 'opportu1180ties_ida',
  'type' => 'link',
  'relationship' => 'opportunities_products',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_OPPORTUNITIES_PRODUCTS_FROM_PRODUCTS_TITLE',
);
