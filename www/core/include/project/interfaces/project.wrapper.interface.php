<?php
/**
 * @package project
 * @version 0.4.0.0
 * @author Roman Konertz
 * @copyright (c) 2008-2010 by Roman Konertz
 * @license GPLv3
 * 
 * This file is part of Open-LIMS
 * Available at http://www.open-lims.org
 * 
 * This program is free software;
 * you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation;
 * version 3 of the License.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
 * See the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, see <http://www.gnu.org/licenses/>.
 */
 

/**
 * Project Wrapper Interface
 * @package project
 */ 		 
interface Project_WrapperInterface
{
	public static function list_project_status($order_by, $order_method, $start, $end);
	public static function count_list_project_status();
	public static function list_project_templates($order_by, $order_method, $start, $end);
	public static function count_list_project_templates();
	public static function list_project_template_categories($order_by, $order_method, $start, $end);
	public static function count_list_project_template_categories();
	public static function count_user_projects($user_id);
	public static function count_user_running_projects($user_id);
	public static function count_user_finished_projects($user_id);
	public static function list_user_related_projects($user_id, $order_by, $order_method, $start, $end);
	public static function count_list_user_related_projects($user_id);
}
?>