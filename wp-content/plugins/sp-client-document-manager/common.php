<?php



function sp_cdm_show_file_linked($html){
	global $wpdb;
		$fid = $_GET['fid'];
		if($fid != ''){
		$fid = base64_decode($fid);
		$r  = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "sp_cu WHERE id = '" . $wpdb->escape($fid)  . "'", ARRAY_A);
		
		
		$html .='<script type="text/javascript">
					jQuery(document).ready(function() {
				
					';
					
					if($r[0]['pid'] != ''){
					
					$html .= 'sp_cdm_load_project('.$r[0]['pid'].')';	
						
					}
		$html .='	
		
						cdmViewFile(' . $r[0]['id'] . ');
					});
				</script>';
		}
	
	return $html;
}
add_filter('sp_cdm_upload_view','sp_cdm_show_file_linked'); 
function sp_cdm_file_link($fid){
			
				
		return ''.get_site_url().'/?sp-cdm-link='.base64_encode($fid).'';		
			
}
function sp_cdm_link_to_file(){
			
			if($_GET['sp-cdm-link'] != ''){		
			if ( (is_user_logged_in() && get_option('sp_cu_user_require_login_download') == 1 ) or (get_option('sp_cu_user_require_login_download') == '' or get_option('sp_cu_user_require_login_download') == 0 )){
				
			
			$url = cdm_shortcode_url('fid='.$_GET['sp-cdm-link'].'');
			if(get_option('sp_cu_dashboard_page') != ''){
				$url = get_permalink(get_option('sp_cu_dashboard_page') );	
				if ( get_option('permalink_structure') != '' ) { 	
				$url = ''.$url.'?fid='.$_GET['sp-cdm-link'].''	;		
				}else{				
				$url = ''.$url.'&fid='.$_GET['sp-cdm-link'].''	;				
				}
			}
			$url = apply_filters('sp_cdm_before_link_redirect',$url);
			wp_redirect($url);
			
	
			}else{

			auth_redirect();	
		
	
			}	
			}		
}
add_action('init', 'sp_cdm_link_to_file');

	function cdm_shortcode_url($and){
			global $wpdb;

	 $r = $wpdb->get_results("SELECT * FROM  " . $wpdb->prefix . "posts where post_content LIKE   '%[sp-client-document-manager]%' and post_type = 'page' and post_status = 'publish'", ARRAY_A);
			
		if($r[0]['ID'] == ""){
		return false;
		}else{
			
			$url = get_permalink( $r[0]['ID'] );	

				if ( get_option('permalink_structure') != '' ) { 
				
				
				return ''.$url.'?'.$and.''	;
				
				
				}else{
					
						return ''.$url.'&'.$and.''	;
					
					
				}
			
					
			
		
		}
	}
   function  cdm_document_ajax_url(){
	   global $current_user;
	   $pid = $_COOKIE['pid'];
		
		   if($pid == ''){
			$pid = 0;
		   }
		   
		   if($_GET['page'] == 'sp-client-document-manager-fileview'){
			$uid = $_GET['uid'];   
		   }else{
			$uid = $current_user->ID;   
		   }
		   
	   if(get_option('sp_cu_user_projects_thumbs') == 1){
			
		$url =  'jQuery("#cmd_file_thumbs").load("' . SP_CDM_PLUGIN_URL . 'ajax.php?function=file-list&uid=' . $uid . '&pid='.$pid.'").hide().fadeIn();';	   
   	   }
	   
	    if(get_option('sp_cu_free_file_list') == 1){
		$url =  'jQuery("#cmd_file_thumbs").load("' . SP_CDM_PLUGIN_URL . 'ajax.php?function=thumbnails&uid=' . $uid . '&pid='.$pid.'").hide().fadeIn();';	   
		}else{
			
		$url =  'jQuery("#cdm-responsive-view").load("'. plugins_url().'/sp-client-document-manager-premium/ajax.php?function=responsive-view&uid=' . $uid . '&pid='.$pid.'").hide().fadeIn();';	   
   	    }
		
		$url = apply_filters('sp_document_view_ajax_url', $url);
	
		return $url;
		}
	
	

function cdm_file_size($file)
{
	$size = @filesize($file);
	
	
	if($size > 1048576){
		  $filesize = ($size * .0009765625) * .0009765625; // bytes to MB
			$type = 'MB';
	}else{
	 $filesize = $size* .0009765625; // bytes to KB	
	 $type = 'KB';
	}
 
   if($filesize <= 0){
      return $filesize = 'Unknown file size';}
   else{return round($filesize, 2).' '.$type;}
}

function cdm_file_permissions($pid){
			global $wpdb, $current_user;
			$permission = 0;
		
				$uid = $current_user->ID;
				//if an admin
				if(current_user_can('manage_options')){
				$permission = 1;	
				}
				
				//if owner of the folder
				$owner  = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "sp_cu_project WHERE id = '" . $wpdb->escape($pid)  . "'", ARRAY_A);
				if($uid == $owner[0]['uid']){
				$permission = 1;	
				}
				//cdm premium groups
					if($pid >0 && is_numeric($pid)){
					$groups_premium  = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "sp_cu_groups_assign WHERE uid = '" . $wpdb->escape($owner[0]['uid'])  . "'", ARRAY_A);
					  for ($i = 0; $i < count( $groups_premium); $i++) {
						  
						  $groups_part_of[] = $groups_premium[$i]['gid'];
					  }
					if(count($groups_part_of) >0){
					foreach($groups_part_of as $key=>$value){
					$groups_premium_find  = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "sp_cu_groups_assign WHERE gid = '" . $wpdb->escape($value)  . "' AND uid = '".$current_user->ID."'", ARRAY_A);
						if($groups_premium_find > 0){
						$permission = 1;	
						}
					}
					}
					}
					//if given permission for groups addon
					if(class_exists('sp_cdm_groups_addon')){
						$sp_cdm_groups_perm =  new sp_cdm_groups_addon;
						//can delete folder
							if(get_option('sp_cdm_groups_addon_project_add_folders_' . $pid . '') == 1){
								$permission = 1;
							}	
							
							
							
							//check to see if user is part of a buddy press group that has access to this folder
							  if ($sp_cdm_groups_perm->buddypress == true) {
								
								
								
							  $folder_perm =sp_cdm_groups_addon_projects::get_permissions('' .$sp_cdm_groups_perm->namesake . '_buddypress_permission_add_' . $pid . '');
							 
							  $query = "SELECT user_id,group_id,name," . $wpdb->prefix . "bp_groups.id FROM  " . $wpdb->prefix . "bp_groups_members  
	   									   LEFT JOIN " . $wpdb->prefix . "bp_groups ON " . $wpdb->prefix . "bp_groups_members.group_id = " . $wpdb->prefix . "bp_groups.id  where user_id = '".$uid."'";
										
	  						  $groups_info = $wpdb->get_results($query, ARRAY_A);
	   
	  
									   if(count($groups_info) > 0){
										   for ($i = 0; $i < count(  $groups_info); $i++) {
											 
												if (@in_array($groups_info[$i]['id'],$folder_perm )) {
												 $permission = 1;
												 }	
											  
										   }
									   }
											
							  }//end buddypress
							  
							  
							  //check roles permission
							     $folder_perm_roles =sp_cdm_groups_addon_projects::get_permissions('' .$sp_cdm_groups_perm->namesake . '_role_permission_add_' . $pid . '');
								$user_roles = $current_user->roles;
							
	 			 				 if(count($user_roles) > 0){
										   foreach ($user_roles as $key =>$role) {
											 
												if (@in_array($role, $folder_perm_roles)) {
												 $permission = 1;
												 }	
											  
										   }
									   }
	 
	 		  
							  //end roles permission
						    
							//global setting
							if(get_option('sp_cdm_groups_addon_project_add_' . $pid . '') == 1){
								$permission = 1;
							}		
								
					}//end grioups addon
					
				//is part of premium group
		
				if($pid == 0 or $pid == ''){
					$permission = 1;
					
				}
				
				
			$permission = apply_filters('cdm_file_permissions',$permission,$pid,$uid);
				
		return $permission;
	}
	

function cdm_folder_permissions($pid){
			global $wpdb, $current_user;
			$permission = 0;
		
				$uid = $current_user->ID;
				
				//if an admin
				if(current_user_can('manage_options')){
				$permission = 1;	
				}
				
				//if owner of the folder
				$owner  = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "sp_cu_project WHERE id = '" . $wpdb->escape($pid)  . "'", ARRAY_A);
				if($uid == $owner[0]['uid']){
				$permission = 1;	
				}
					
					//cdm premium groups
					if($pid >0 && is_numeric($pid)){
					$groups_premium  = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "sp_cu_groups_assign WHERE uid = '" . $wpdb->escape($owner[0]['uid'])  . "'", ARRAY_A);
					  for ($i = 0; $i < count( $groups_premium); $i++) {
						  
						  $groups_part_of[] = $groups_premium[$i]['gid'];
					  }
					if(count($groups_part_of)> 0){
					foreach($groups_part_of as $key=>$value){
					$groups_premium_find  = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "sp_cu_groups_assign WHERE gid = '" . $wpdb->escape($value)  . "' AND uid = '".$current_user->ID."'", ARRAY_A);
						if($groups_premium_find > 0){
						$permission = 1;	
						}
					}
					}
					}
				
							//if given permission for groups addon
					if(class_exists('sp_cdm_groups_addon')){
						$sp_cdm_groups_perm =  new sp_cdm_groups_addon;
						//can delete folder
							if(get_option('sp_cdm_groups_addon_project_add_folders_' . $pid . '') == 1){
								$permission = 1;
							}	
							
							
							
							//check to see if user is part of a buddy press group that has access to this folder
							  if ($sp_cdm_groups_perm->buddypress == true) {
								
								
								
							  $folder_perm =sp_cdm_groups_addon_projects::get_permissions('' .$sp_cdm_groups_perm->namesake . '_buddypress_permission_add_' . $pid . '');
							 
							  $query = "SELECT user_id,group_id,name," . $wpdb->prefix . "bp_groups.id FROM  " . $wpdb->prefix . "bp_groups_members  
	   									   LEFT JOIN " . $wpdb->prefix . "bp_groups ON " . $wpdb->prefix . "bp_groups_members.group_id = " . $wpdb->prefix . "bp_groups.id  where user_id = '".$uid."'";
										
	  						  $groups_info = $wpdb->get_results($query, ARRAY_A);
	   
	  
									   if(count($groups_info) > 0){
										   for ($i = 0; $i < count(  $groups_info); $i++) {
											 
												if (@in_array($groups_info[$i]['id'],$folder_perm )) {
												 $permission = 1;
												 }	
											  
										   }
									   }
											
							  }//end buddypress
							  
							  
							  //check roles permission
							     $folder_perm_roles =sp_cdm_groups_addon_projects::get_permissions('' .$sp_cdm_groups_perm->namesake . '_role_permission_add_' . $pid . '');
								$user_roles = $current_user->roles;
							
	 			 				 if(count($user_roles) > 0){
										   foreach ($user_roles as $key =>$role) {
											 
												if (@in_array($role, $folder_perm_roles)) {
												 $permission = 1;
												 }	
											  
										   }
									   }
	 
	 		  
							  //end roles permission
						    
							//global setting
							if(get_option('sp_cdm_groups_addon_project_add_folder_' . $pid . '') == 1){
								$permission = 1;
							}		
								
					}//end grioups addon
					
				//is part of premium group
		
				if($pid == 0 or $pid == ''){
					$permission = 1;
					
				}
				
			$permission = apply_filters('cdm_folder_permissions',$permission,$pid,$uid);	
		return $permission;
	}
function cdm_delete_permission($pid){
	
	global $wpdb, $current_user;
			$permission = 0;
		
				$uid = $current_user->ID;
				//if an admin
				if(current_user_can('manage_options')){
				$permission = 1;	
				}
				
				//if owner of the folder
				$owner  = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "sp_cu_project WHERE id = '" . $wpdb->escape($pid)  . "'", ARRAY_A);
				if($uid == $owner[0]['uid']){
				$permission = 1;	
				}
							//if given permission for groups addon
					if(class_exists('sp_cdm_groups_addon')){
						$sp_cdm_groups_perm =  new sp_cdm_groups_addon;
						//can delete folder
							if(get_option('sp_cdm_groups_addon_project_delete_folders_' . $pid . '') == 1){
								$permission = 1;
							}	
							
							
							
							//check to see if user is part of a buddy press group that has access to this folder
							  if ($sp_cdm_groups_perm->buddypress == true) {
								
								
								
							  $folder_perm =sp_cdm_groups_addon_projects::get_permissions('' .$sp_cdm_groups_perm->namesake . '_buddypress_permission_delete_' . $pid . '');
							 
							  $query = "SELECT user_id,group_id,name," . $wpdb->prefix . "bp_groups.id FROM  " . $wpdb->prefix . "bp_groups_members  
	   									   LEFT JOIN " . $wpdb->prefix . "bp_groups ON " . $wpdb->prefix . "bp_groups_members.group_id = " . $wpdb->prefix . "bp_groups.id  where user_id = '".$uid."'";
										
	  						  $groups_info = $wpdb->get_results($query, ARRAY_A);
	   
	  
									   if(count($groups_info) > 0){
										   for ($i = 0; $i < count(  $groups_info); $i++) {
											 
												if (@in_array($groups_info[$i]['id'],$folder_perm )) {
												 $permission = 1;
												 }	
											  
										   }
									   }
											
							  }//end buddypress
							  
							  
							  //check roles permission
							     $folder_perm_roles =sp_cdm_groups_addon_projects::get_permissions('' .$sp_cdm_groups_perm->namesake . '_role_permission_delete_' . $pid . '');
								$user_roles = $current_user->roles;
							
	 			 				 if(count($user_roles) > 0){
										   foreach ($user_roles as $key =>$role) {
											 
												if (@in_array($role, $folder_perm_roles)) {
												 $permission = 1;
												 }	
											  
										   }
									   }
	 
	 		  
							  //end roles permission
						    
							//global setting
								
								
					}//end grioups addon
					
				//is part of premium group
		
				if($pid == 0 or $pid == ''){
					$permission = 1;
					
				}
				
			$permission = apply_filters('cdm_delete_permissions',$permission,$pid,$uid);	
		return $permission;
	
	
	
	
	
}
if(!function_exists('sp_cdm_category_value')){
function sp_cdm_category_value($id){
global $wpdb;

    $r_cat = $wpdb->get_results("SELECT *  FROM " . $wpdb->prefix . "sp_cu_cats   where id = '" . $id . "'", ARRAY_A);	 	
	return stripslashes($r_cat[0]['name']);
}
	
function sp_cdm_category_name(){
	
	if(get_option('sp_cu_cat_text') != ''){
		$cat= get_option('sp_cu_cat_text');
	}else{
		$cat =  __("Category", "sp-cdm");
	}
return $cat;	
}

}

if(!function_exists('set_html_content_type')){
function set_html_content_type() {

	return 'text/html';
}	
}

if(!function_exists('sp_cdm_folder_name')){


	
function sp_cdm_folder_name($type = 0){
	
	
		if($type == 1){
			
			if(get_option('sp_cu_folder_name_plural') == ''){
			return  __("Folders", "sp-cdm");	
			}else{
			return  stripslashes(get_option('sp_cu_folder_name_plural'));
			}
		}else{
			if(get_option('sp_cu_folder_name_single') == ''){
				return  __("Folder", "sp-cdm");
			}else{
			return  stripslashes(get_option('sp_cu_folder_name_single'));
			
			}
		}
				
	
}
}
function sp_cdm_thumbnail($url,$w = NULL,$h= NULL){
	global $wpdb;
	
	if($h != NULL){
	$settings['height'] = $h;		
	}
	if($w != NULL){
	$settings['width'] = $w;	
	}
	$settings['crop'] = false;


			return bfi_thumb($url, $settings);
}

function sp_cdm_get_current_user_role_name () {
    global $current_user;
    get_currentuserinfo();
    $user_roles = $current_user->roles;
    $user_role = array_shift($user_roles);
    return $user_role;
}


function sp_cdm_get_project_name($id){

	

		global $wpdb;

		

			$r = $wpdb->get_results("SELECT *

	

									 FROM ".$wpdb->prefix."sp_cu_project

									 WHERE id = '".$id."'", ARRAY_A);	

									 

				if($r[0]['name'] != ""){

					return stripslashes($r[0]['name']);

				}else{

				return false;

}

}

function sp_cdm_get_current_user_role() {

global $current_user;



	$user_roles = $current_user->roles;

	print_r($user_roles);

	$user_role = array_shift($user_roles);



	return $user_role;

}





function sp_cdm_find_users_by_role($role) {

	global $wpdb;



 $wp_user_search = new WP_User_Query(array("role"=> $role));

 $role_data = $wp_user_search->get_results();

    foreach($role_data  as $item){

 $role_data_ids[] = $item->ID;

 }



 $ids = implode(',', $role_data_ids);

 $r = $wpdb->get_results("SELECT *   from ".$wpdb->prefix . "users where id IN(".$ids .")", ARRAY_A);







 for($i=0; $i<count($r); $i++){

$emails[$i] = $r[$i]['user_email'];

 }





 return $emails;

}

function sp_do_function_header($file){

	

	

}



function sp_client_upload_filename($user_id){

	global $wpdb;

	

	

	$r = $wpdb->get_results("SELECT*

									 FROM ".$wpdb->prefix."users  where id = $user_id", ARRAY_A);	

	

	

	

	$extra = get_option('sp_cu_filename_format') ;

	$extra = str_replace('%y',date('Y'), $extra);

	$extra = str_replace('%h',date('H'), $extra );

	$extra = str_replace('%min',date('i'), $extra );

	$extra = str_replace('%m',date('m'), $extra );

	$extra = str_replace('%d',date('d'), $extra);

	$extra = str_replace('%t',time(), $extra );

	$extra = str_replace('%uid',$user_id, $extra );

	

	$extra = str_replace('%u',$r[0]['display_name'], $extra );	

	$extra = str_replace('%r',rand(100000, 100000000000), $extra );

	return $extra;

	

}





function sp_array_remove_empty($arr){

    $narr = array();

    while(list($key, $val) = each($arr)){

        if (is_array($val)){

            $val = array_remove_empty($val);

            // does the result array contain anything?

            if (count($val)!=0){

                // yes :-)

                $narr[$key] = $val;

            }

        }

        else {

            if (trim($val) != ""){

                $narr[$key] = $val;

            }

        }

    }

    unset($arr);

    return $narr;

}



function sp_uploadFile($files, $history = NULL){

	

	global $wpdb ;

	global $user_ID;

	global $current_user;

	 
			

			$dir = ''.SP_CDM_UPLOADS_DIR.''.$user_ID.'/';

			$count = sp_array_remove_empty($files['dlg-upload-file']['name']);







			if($history == 1){
					$name = $files['dlg-upload-file']['name'][0];
				 $wp_filetype = wp_check_filetype( $name );
				   if ( ! $wp_filetype['ext'] && ! current_user_can( 'unfiltered_upload' ) ){
	                echo  __( 'Invalid file type' );exit;
					}
				

		$dir = ''.SP_CDM_UPLOADS_DIR.''.$current_user->ID.'/';

	

	$filename = ''.sp_client_upload_filename($current_user->ID) .''.$files['dlg-upload-file']['name'][0].'';

	$filename = strtolower($filename);

	$filename = sanitize_file_name($filename);
	$filename    = remove_accents($filename);
	$target_path = $dir .$filename; 

	

	move_uploaded_file($files['dlg-upload-file']['tmp_name'][0], $target_path);

	

	$ext = preg_replace('/^.*\./', '', $filename);

	if(get_option('sp_cu_user_projects_thumbs_pdf') == 1 && class_exists('imagick')){
	
	$info = new Imagick();
	$formats = $info->queryFormats();
		
		if(in_array(strtoupper($ext),$formats)){
		cdm_thumbPdf($target_path);
		}
	}

	

	return $filename;

}else{



	if(count($count)> 1 ){

	

	

	//echo $count;

	//	echo '<pre>';

	//print_r($files);exit;

	//echo '</pre>';



	

	

	



		

		

			$fileTime = date("D, d M Y H:i:s T");



				$zip = new Zip();

				

				

				

				for($i=0; $i<count($files['dlg-upload-file']['name']); $i++){
				  $name = $files['dlg-upload-file']['name'][$i];
				 $wp_filetype = wp_check_filetype( $name );
				   if ( ! $wp_filetype['ext'] && ! current_user_can( 'unfiltered_upload' ) ){
	                echo  __( 'Invalid file type' );exit;
					}
				

					if($files['dlg-upload-file']['error'][$i] == 0){

						

					

					

						$filename = ''.sp_client_upload_filename($user_ID) .''.$files['dlg-upload-file']['name'][$i].'';

						$filename = strtolower($filename);

						$filename = sanitize_file_name($filename);
						$filename    = remove_accents($filename);
						$target_path = $dir .$filename; 

						move_uploaded_file($files['dlg-upload-file']['tmp_name'][$i], $target_path);

				

					  $zip->addFile(file_get_contents($target_path), $filename , filectime($target_path));

					}

				}

		

		

$zip->finalize(); // as we are not using getZipData or getZipFile, we need to call finalize ourselves.

$return_file = "".rand(100000, 100000000000)."_Archive.zip";

$zip->setZipFile($dir.$return_file);

		

	return $return_file;	

		

		

	}else{

  $name = $files['dlg-upload-file']['name'][1];
				 $wp_filetype = wp_check_filetype( $name );
				   if ( ! $wp_filetype['ext'] && ! current_user_can( 'unfiltered_upload' ) ){
	              return  'upload_error';
					}

	$dir = ''.SP_CDM_UPLOADS_DIR.''.$current_user->ID.'/';

	

	$filename = ''.sp_client_upload_filename($current_user->ID) .''.$files['dlg-upload-file']['name'][1].'';

	$filename = strtolower($filename);

	$filename = sanitize_file_name($filename);
	$filename    = remove_accents($filename);
	$target_path = $dir .$filename; 

	

	move_uploaded_file($files['dlg-upload-file']['tmp_name'][1], $target_path);

	$ext = preg_replace('/^.*\./', '', $filename);

	if(get_option('sp_cu_user_projects_thumbs_pdf') == 1 && class_exists('imagick')){
	
	$info = new Imagick();
	$formats = $info->queryFormats();
		
		if(in_array(strtoupper($ext),$formats)){
		cdm_thumbPdf($target_path);
		}
	}


	return $filename;

	}

}

}

function sp_Admin_uploadFile($files,$user_ID){

	

	global $wpdb ;



	

			

			$dir = ''.SP_CDM_UPLOADS_DIR.''.$user_ID.'/';

			$count = sp_array_remove_empty($files['dlg-upload-file']['name']);







			if($history == 1){

		$dir = ''.SP_CDM_UPLOADS_DIR.''.$user_ID.'/';

	

	$filename = ''.sp_client_upload_filename($user_ID) .''.$files['dlg-upload-file']['name'][0].'';

	$filename = strtolower($filename);

	$filename = sanitize_file_name($filename);
	$filename    = remove_accents($filename);
	$target_path = $dir .$filename; 

	

	move_uploaded_file($files['dlg-upload-file']['tmp_name'][0], $target_path);

	

	$ext = preg_replace('/^.*\./', '', $filename);

	if(get_option('sp_cu_user_projects_thumbs_pdf') == 1 && class_exists('imagick')){
	
	$info = new Imagick();
	$formats = $info->queryFormats();
		
		if(in_array(strtoupper($ext),$formats)){
		cdm_thumbPdf($target_path);
		}
	}


	

	return $filename;

}else{









	if(count($count)> 1 ){

	

	

	//echo $count;

	//	echo '<pre>';

	//print_r($files);exit;

	//echo '</pre>';



	

	

	



		

		

			$fileTime = date("D, d M Y H:i:s T");



				$zip = new Zip();

				

				

				

				for($i=0; $i<count($files['dlg-upload-file']['name']); $i++){

				

					if($files['dlg-upload-file']['error'][$i] == 0){

						

					

					

						$filename = ''.sp_client_upload_filename($user_ID) .''.$files['dlg-upload-file']['name'][$i].'';

						$filename = strtolower($filename);

						$filename = sanitize_file_name($filename);
						$filename    = remove_accents($filename);
						$target_path = $dir .$filename; 

						move_uploaded_file($files['dlg-upload-file']['tmp_name'][$i], $target_path);

				

					  $zip->addFile(file_get_contents($target_path), $filename , filectime($target_path));

					}

				}

		

		

$zip->finalize(); // as we are not using getZipData or getZipFile, we need to call finalize ourselves.

$return_file = "".rand(100000, 100000000000)."_Archive.zip";

$zip->setZipFile($dir.$return_file);

		

	return $return_file;	

		

		

	}else{



	$dir = ''.SP_CDM_UPLOADS_DIR.''.$user_ID.'/';

	

	$filename = ''.sp_client_upload_filename($user_ID) .''.$files['dlg-upload-file']['name'][1].'';

	$filename = strtolower($filename);

	$filename = sanitize_file_name($filename);
	$filename    = remove_accents($filename);
	$target_path = $dir .$filename; 

	

	move_uploaded_file($files['dlg-upload-file']['tmp_name'][1], $target_path);

	$ext = preg_replace('/^.*\./', '', $filename);

	if(get_option('sp_cu_user_projects_thumbs_pdf') == 1 && class_exists('imagick')){
	
	$info = new Imagick();
	$formats = $info->queryFormats();
		
		if(in_array(strtoupper($ext),$formats)){
		cdm_thumbPdf($target_path);
		}
	}


	return $filename;

	}

}

}



?>