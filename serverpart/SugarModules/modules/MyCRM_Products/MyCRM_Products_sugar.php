<?PHP
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

class MyCRM_Products_sugar extends Basic {
	var $new_schema = true;
	var $module_dir = 'MyCRM_Products';
	var $object_name = 'MyCRM_Products';
	var $table_name = 'mycrm_products';
	var $importable = false;

	var $disable_row_level_security = true ; // to ensure that modules created and deployed under CE will continue to function under team security if the instance is upgraded to PRO

		var $id;
		var $name;
		var $date_entered;
		var $date_modified;
		var $modified_user_id;
		var $modified_by_name;
		var $created_by;
		var $created_by_name;
		var $description;
		var $deleted;
		var $created_by_link;
		var $modified_user_link;
		var $assigned_user_id;
		var $assigned_user_name;
		var $assigned_user_link;
		var $mft_part_num;
		var $vendor_part_num;
		var $discount_price;
		var $currency_id;
		var $discount_usdollar;
		var $tax_class;
		var $quantity;
		var $support_contact;
		var $date_support_starts;
		var $serial_number;
		var $book_value_date;
		var $date_purchased;
		var $list_price;
		var $list_usdollar;
		var $website;
		var $support_name;
		var $support_term;
		var $pricing_formula;
		var $asset_number;
		var $cost_price;
		var $cost_usdollar;
		var $status;
		var $weight;
		var $support_description;
		var $date_support_expires;
		var $pricing_factor;
		var $book_value;
		var $oxid;
	




	function MyCRM_Products_sugar(){	
		parent::Basic();
	}
	
	function bean_implements($interface){
		switch($interface){
			case 'ACL': return true;
		}
		return false;
}
		
}
?>