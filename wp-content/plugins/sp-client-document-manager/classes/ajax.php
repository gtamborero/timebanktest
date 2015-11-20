<?php
class spdm_ajax
{
	
	
	function order_by(){
		
		$orderby = 'name';
		
		$orderby = apply_filters('sp_cdm_order_by_ajax',$orderby);
		
		return $orderby ;
	}
	
	function project_dropdown(){
		
		if(class_exists('spdm_sub_projects')){
		echo spdm_sub_projects::project_dropdown_replace();
		}else{
		echo sp_cdm_replace_project_select();	
		}
		
		
		
	}
    function view_file()
    {
        global $wpdb, $current_user, $cdm_comments, $cdm_google, $cdm_log;
        $r = $wpdb->get_results($wpdb->prepare("SELECT *  FROM " . $wpdb->prefix . "sp_cu   where id = %d order by date desc", $_GET['id']), ARRAY_A);
		
        $html .= '<div id="view_file_refresh">

		



	';
        $html .= '<div class="sp_cu_manage">';
        
		$html = apply_filters('sp_cdm_view_file_first_add_button',$html,$r);
		
		if (CU_PREMIUM == 1 && get_option('sp_cu_user_uploads_disable') != 1 && get_option('sp_cu_user_disable_revisions') != 1  && cdm_file_permissions($r[0]['pid']) == 1) {
            $html .= sp_cdm_revision_button();
        }
        if (class_exists('cdmProductivityUser')) {
            $html .= '<span id="cdm_comment_button_holder">' . $cdm_comments->button() . '</span>';
        }
		
		$html = apply_filters('sp_cdm_view_file_add_button',$html,$r);
		
        if (class_exists('cdmProductivityGoogle')) {
            $html .= '<span id="cdm_shortlink_button_holder">' . $cdm_google->short_link_button($r[0]['id'], '' . SP_CDM_PLUGIN_URL . 'download.php?fid=' .base64_encode($r[0]['id'].'|'.$r[0]['date'].'|'.$r[0]['file']). '') . '</span>';
        }
        if (get_option('sp_cu_js_redirect') == 1) {
            $target = 'target="_blank"';
        } else {
            $target = ' ';
        }
       
	    $download_url = '<a ' . $target . ' href="' . SP_CDM_PLUGIN_URL . 'download.php?fid=' .base64_encode($r[0]['id'].'|'.$r[0]['date'].'|'.$r[0]['file']) . '" title="Download" style="margin-right:15px"  ><img src="' . SP_CDM_PLUGIN_URL . 'images/download.png"> ' . __("Download File", "sp-cdm") . '</a> ';
		$download_url = apply_filters('sp_cdm_viewfile_download_url', $download_url, $r);
		$html .= $download_url;
		
		
        if ( cdm_user_can_delete($current_user->ID) == true && cdm_delete_permission($r[0]['pid']) == 1) {
            $html .= '

	<a href="javascript:sp_cu_confirm_delete(\'' . get_option('sp_cu_delete') . '\',200,\'' . SP_CDM_PLUGIN_URL . 'ajax.php?function=delete-file&dlg-delete-file=' . $r[0]['id'] . '\');" title="Delete" ><img src="' . SP_CDM_PLUGIN_URL . 'images/delete.png">' . __("Delete File", "sp-cdm") . '</a>';
        }
        $html .= '
<div style="clear:both"></div>
	 <em>' . date('F jS Y h:i A', strtotime($r[0]['date'])) . ' &bull; File ID: #' . $r[0]['id'] . '</em>

				</div>';
        $html .= '
<script type="text/javascript">
jQuery(".viewFileTabs").responsiveTabs({
 startCollapsed: false
});
</script>




		<div class="viewFileTabs">

	<ul>

		<li><a href="#cdm-file-main">'.__("File Info","sp-cdm").'</a></li>';
		$html = apply_filters('sp_cdm_view_file_after_file_info_tab',$html,$r);
		
        if (function_exists('sp_cdm_revision_add') && get_option('sp_cu_user_disable_revisions') != 1) {
            $html .= '<li><a href="#cdm-file-revisions">'.__("Revisions","sp-cdm").'</a></li>';
        }
        if (class_exists('cdmProductivityUser')) {
            $html .= '<li><a href="#cdm-file-comments">'.__("Comments","sp-cdm").'</a></li>';
        }
        if (class_exists('cdmProductivityLog')) {
			if((get_option('sp_cu_log_admin_only') == 1 && current_user_can('manage_options') )
	or (get_option('sp_cu_log_admin_only') == 0 or get_option('sp_cu_log_admin_only') == '')
	){
            $html .= '<li><a href="#cdm-file-log">'.__("Download Log","sp-cdm").'</a></li>';
	}
        }
		
			$html = apply_filters('sp_cdm_view_file_tab',$html,$r);
		
        $html .= '</ul>

	';
		$html = apply_filters('sp_cdm_view_file_content',$html,$r);
	
        if (function_exists('sp_cdm_revision_add') && get_option('sp_cu_user_disable_revisions') != 1) {
            $html .= '<div id="cdm-file-revisions"><div id="cdm_comments"><h4>' . __("Revision History", "sp-cdm") . '</h4>

' . sp_cdm_file_history($r[0]['id']) . '</div></div>';
        }
        if (class_exists('cdmProductivityUser')) {
            $html .= '<div id="cdm-file-comments"><div id="cdm_comments_container">' . $cdm_comments->view($r[0]['id']) . '</div></div>';
        }
        if (class_exists('cdmProductivityLog')) {
            $html .= '<div id="cdm-file-log">' . $cdm_log->view($r[0]['id']) . '</div>';
        }
        $html .= '<div id="cdm-file-main">';
        if (get_option('sp_cu_wp_folder') == '') {
            $wp_con_folder = '/';
        } else {
            $wp_con_folder = get_option('sp_cu_wp_folder');
        }
        //print_r($r);
        $ext = substr(strrchr($r[0]['file'], '.'), 1);
        if ($r[0]['pid'] != 0) {
            $projecter     = $wpdb->get_results("SELECT *

	

									 FROM " . $wpdb->prefix . "sp_cu_project

									 WHERE id = '" . $r[0]['pid'] . "'

									 ", ARRAY_A);
            $project_title = '' . stripslashes($projecter[0]['name']) . '';
        } else {
            $project_title = '' . __("None", "sp-cdm") . '';
        }
        if ($ext == 'png' or $ext == 'jpg' or $ext = 'jpeg' or $ext = 'gif') {
            $icon = '<td width="160"><img src="' . SP_CDM_UPLOADS_DIR_URL . '' . $r[0]['uid'] . '/' . $r[0]['file'] . '" width="150"></td>';
        } else {
            $icon = '';
        }
        $ext        = preg_replace('/^.*\./', '', $r[0]['file']);
        $images_arr = array(
            "jpg",
            "png",
            "jpeg",
            "gif",
            "bmp"
        );
      
	  
	  
			if(get_option('sp_cu_user_projects_thumbs_pdf') == 1 && class_exists('imagick')){
	
			$info = new Imagick();
			$formats = $info->queryFormats();
			
			}else{
				$formats = array();
			}
	  
	  
	  
	    if (in_array(strtolower($ext), $images_arr)) {
            if (get_option('sp_cu_overide_upload_path') != '' && get_option('sp_cu_overide_upload_url') == '') {
                $img = '<img src="' . SP_CDM_PLUGIN_URL . 'images/package_labled.png">';
            } else {
                $img = '<img src="' . sp_cdm_thumbnail('' . SP_CDM_UPLOADS_DIR_URL . '' . $r[0]['uid'] . '/' . $r[0]['file'] . '', 250) . '">';
            }
        } elseif ($ext == 'xls' or $ext == 'xlsx') {
            $img = '<img src="' . SP_CDM_PLUGIN_URL . 'images/microsoft_office_excel.png">';
        } elseif ($ext == 'doc' or $ext == 'docx') {
            $img = '<img src="' . SP_CDM_PLUGIN_URL . 'images/microsoft_office_word.png">';
        } elseif ($ext == 'pub' or $ext == 'pubx') {
            $img = '<img src="' . SP_CDM_PLUGIN_URL . 'images/microsoft_office_publisher.png">';
        } elseif ($ext == 'ppt' or $ext == 'pptx') {
            $img = '<img src="' . SP_CDM_PLUGIN_URL . 'images/microsoft_office_powerpoint.png">';
        } elseif ($ext == 'adb' or $ext == 'accdb') {
            $img = '<img src="' . SP_CDM_PLUGIN_URL . 'images/microsoft_office_access.png">';
        } elseif (in_array(strtoupper($ext),$formats)) {
            if (file_exists('' . SP_CDM_UPLOADS_DIR . '' . $r[0]['uid'] . '/' . $r[0]['file'] . '_big.png')) {
                $img = '<img src="' . SP_CDM_UPLOADS_DIR_URL . '' . $r[0]['uid'] . '/' . $r[0]['file'] . '_big.png" width="250">';
            } else {
                $img = '<img src="' . SP_CDM_PLUGIN_URL . 'images/adobe.png">';
            }
        } elseif ($ext == 'pdf' or $ext == 'xod') {
            $img = '<img src="' . SP_CDM_PLUGIN_URL . 'images/adobe.png">';
        } else {
            $img = '<img src="' . SP_CDM_PLUGIN_URL . 'images/package_labled.png">';
        }
		
		$img = apply_filters('sp_cdm_viewfile_image', $img,$r[0]);
        $html .= '

				

				<div id="sp_cu_viewfile">

				

				

				

				<div class="sp_cu_item">

				

		<div class="cdm-two-column">		

<div class="l-column">
<a ' . $target . ' href="' . SP_CDM_PLUGIN_URL . 'download.php?fid=' .base64_encode($r[0]['id'].'|'.$r[0]['date'].'|'.$r[0]['file']) . '" title="Download" style="margin-right:15px"  >

' . $img . '

</a>

</div>
<div class="r-column">

<div class="sp_su_project">

<strong>' . __("File Name", "sp-cdm") . ': </strong> ' . stripslashes($r[0]['name']) . '<br>


</div>
<div class="sp_su_project">

<strong>' .sp_cdm_folder_name()  . ': </strong>' . $project_title . '

</div>

<div class="sp_su_project">

<strong>' . __("File Type ", "sp-cdm") . ': </strong>' . $ext . '

</div>
<div class="sp_su_project">

<strong>' . __("File Size ", "sp-cdm") . ': </strong>' . cdm_file_size(''.SP_CDM_UPLOADS_DIR . '' . $r[0]['uid'] . '/' . $r[0]['file'] . '') . ' 

</div>
';

$extra_file_info = '';
$html .= apply_filters('sp_cdm_file_view_info', $extra_file_info,$r[0]);


  if (CU_PREMIUM == 1) {
	 
	 if($r[0]['cid'] != '' && $r[0]['cid'] != 0){
		 
	    
	
	 }	
	  
  }
        if ($r[0]['tags'] != "") {
            $html .= '

<div class="sp_su_notes">

<strong>' . __("Tags ", "sp-cdm") . ': </strong> ' . stripslashes($r[0]['tags']) . '

</div>';
        }
		
		if ($r[0]['notes'] != "") {
            $html .= '

<div class="sp_su_notes">

<strong>' . __("Notes ", "sp-cdm") . ': </strong> ' . stripslashes($r[0]['notes']) . '

</div>';
        }
		
        if (CU_PREMIUM == 1) {
         
		 if(sp_cdm_get_form_fields($r[0]['id']) != ''){
		    $html .= '

<div class="sp_su_notes">

' . sp_cdm_get_form_fields($r[0]['id']) . '

</div>';
		 }
        } else {
            if ($r[0]['notes'] != "") {
                $html .= '

<div class="sp_su_notes">

<strong>' . __("Notes: ", "sp-cdm") . ':</strong> <em>' . stripslashes($r[0]['notes']) . '</em>

</div>';
            }
        }
       
	   
	   $html = apply_filters('sp_cdm_view_file_notes', $html,$r);
	    $html .= '

	




</div><div style="clear:both"></div>

  </div></div></div></div>

  

 

  </div>

  

  

  

  </div>

  ';
        return $html;
    }
    function delete_file($file_id = NULL)
    {
        global $wpdb, $current_user;
		
		if($file_id != NULL){
		$file_id = $file_id;	
		}else{
		$file_id = $_GET['dlg-delete-file'];	
		}
        $r = $wpdb->get_results($wpdb->prepare("SELECT *  FROM " . $wpdb->prefix . "sp_cu   where id = %d  order by date desc",$file_id), ARRAY_A);
       
	   
	    if ((($current_user->ID == $r[0]['uid'] or cdmFindLockedGroup($current_user->ID, $r[0]['uid']) == true) && get_option('sp_cu_user_delete_disable') != 1) or current_user_can('manage_options')) {
            $wpdb->query($wpdb->prepare("
	DELETE FROM " . $wpdb->prefix . "sp_cu WHERE id = %d ",$file_id)

	);
            unlink('' . SP_CDM_UPLOADS_DIR . '' . $r[0]['uid'] . '/' . $r[0]['file'] . '');
			        $ext        = preg_replace('/^.*\./', '', $r[0]['file']);
					$small = '' . SP_CDM_UPLOADS_DIR . '' . $r[0]['uid'] . '/'.$r[0]['file'].'_small.png';
					$big = '' . SP_CDM_UPLOADS_DIR . '' . $r[0]['uid'] . '/'.$r[0]['file'].'_big.png';
			@unlink($small);
			@unlink($big);
			sp_cdm_user_logs::write('Deleted file: '.$r[0]['name'].'');
			do_action('sp_cdm_delete_file',  $r[0]); 
        }
    }
    function get_file_info()
    {
        global $wpdb, $current_user;
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');
        $r = $wpdb->get_results($wpdb->prepare("SELECT *  FROM " . $wpdb->prefix . "sp_cu   where id = %d",  $_GET['id']), ARRAY_A);
        return str_replace(array(
            '[',
            ']'
        ), '', htmlspecialchars(json_encode($r[0]), ENT_NOQUOTES));
    }
	

    function remove_cat($project_id= NULL)
    {
        global $wpdb, $current_user;
		
		if($project_id != NULL){
		$project_id = $project_id;	
		}else{
		$project_id = $_REQUEST['id'];	
		}
         $r = $wpdb->get_results($wpdb->prepare("SELECT *  FROM " . $wpdb->prefix . "sp_cu_project   where id = %d ",$project_id), ARRAY_A);
       
	   
		if ((($current_user->ID == $r[0]['uid'] or cdmFindLockedGroup($current_user->ID, $r[0]['uid']) == true) && get_option('sp_cu_user_delete_disable') != 1) or current_user_can('manage_options') or cdm_folder_permissions($project_id) == 1) {
		  
					
     
				 
					  #delete this projects files
						$f = $wpdb->get_results($wpdb->prepare("SELECT *  FROM " . $wpdb->prefix . "sp_cu where pid = %d ",$project_id), ARRAY_A);
							
							for ($j = 0; $j < count($f); $j++) {
							
								$this->delete_file($f[$j]['id']);
								
								#$this->remove_cat($id);
							}
					
						#find and remove sub folders
						$p = $wpdb->get_results($wpdb->prepare("SELECT *  FROM " . $wpdb->prefix . "sp_cu_project where parent = %d ",$project_id), ARRAY_A);
						for ($i = 0; $i < count($p); $i++) {
							
							$this->remove_cat($p[$i]['id']);
						}
					#delete the project
					sp_cdm_user_logs::write('Deleted folder: '.$r[0]['name'].'');
					$wpdb->query($wpdb->prepare("DELETE FROM " . $wpdb->prefix . "sp_cu_project WHERE id = %d",$project_id ));							
						
				
		}else{
		sp_cdm_user_logs::write('Error: Failed removing folder: '.$r[0]['name'].'');	
		}
    }
    function save_cat()
    {
        global $wpdb, $current_user;
        $insert['name'] = $_POST['name'];
        if ($_POST['id'] != "") {
            $where['id'] = $_POST['id'];
            $wpdb->update("" . $wpdb->prefix . "sp_cu_project", $insert, $where);
            echo '' . __("Updated Category Name", "sp-cdm") . ': ' . $insert['name'] . '';
            exit;
        } else {
            $insert['uid']    = $_POST['uid'];
            $insert['parent'] = $_POST['parent'];
            $wpdb->insert("" . $wpdb->prefix . "sp_cu_project", $insert);
            echo $wpdb->insert_id;
            exit;
        }
        echo 'Error!';
    }
    function file_list()
    {
        global $wpdb, $current_user;
         if (function_exists('cdmFindGroups')) {
            $find_groups = cdmFindGroups($_GET['uid'], 1);
        }
		
		
		
		
		
        if ($_REQUEST['search'] != "") {
            $search_project .= " AND " . $wpdb->prefix . "sp_cu_project.name LIKE '%" . $_REQUEST['search'] . "%' ";
        }else{
        if ($_GET['pid'] == '') {
            $search_project .= " AND " . $wpdb->prefix . "sp_cu_project.parent = '0' ";
        } else {
            $search_project .= " AND " . $wpdb->prefix . "sp_cu_project.parent = '" . $_GET['pid'] . "' ";
        }
		}
          if (get_option('sp_cu_hide_project') == 1) {
			
			
			$r_projects_query = "SELECT " . $wpdb->prefix . "sp_cu.name,

												 " . $wpdb->prefix . "sp_cu.id,

												 " . $wpdb->prefix . "sp_cu.pid ,

												 " . $wpdb->prefix . "sp_cu.uid,

												 " . $wpdb->prefix . "sp_cu.parent,

												 " . $wpdb->prefix . "sp_cu_project.name AS project_name,

												 " . $wpdb->prefix . "sp_cu_project.parent

												 

										FROM " . $wpdb->prefix . "sp_cu   

										LEFT JOIN " . $wpdb->prefix . "sp_cu_project  ON " . $wpdb->prefix . "sp_cu.pid = " . $wpdb->prefix . "sp_cu_project.id

										WHERE (" . $wpdb->prefix . "sp_cu.uid = '" . $_GET['uid'] . "'  " . $find_groups . ")

										AND pid != 0

										AND  " . $wpdb->prefix . "sp_cu.parent = 0 

										" . $sub_projects . "";
										
								if($_GET['pid'] == 0 or $_GET['pid'] == ''){
									$r_projects_query = apply_filters('sp_cdm_projects_query', $r_projects_query ,$_GET['uid']);	
										}
									
									$r_projects_query .="	" . $search_project . "
										
										GROUP BY pid

										ORDER by date desc";
				if(get_option('sp_cu_release_the_kraken') == 1){
								unset($r_projects_query);								
								$r_projects_query =	 "SELECT 										 
													" . $wpdb->prefix . "sp_cu_project.id,

												" . $wpdb->prefix . "sp_cu_project.id AS pid,

												" . $wpdb->prefix . "sp_cu_project.uid,

												 " . $wpdb->prefix . "sp_cu_project.name AS project_name,

												  " . $wpdb->prefix . "sp_cu_project.parent
										FROM " . $wpdb->prefix . "sp_cu_project
										WHERE id != ''
										
										" . $search_project . " ORDER by name
";
								}
			
            $r_projects = $wpdb->get_results($r_projects_query, ARRAY_A);
        } else {
			
			
									$r_projects_groups_addon = apply_filters('sp_cdm_projects_query', $r_projects_groups_addon ,$_GET['uid']);	
					
			$r_projects_query = "SELECT 

												" . $wpdb->prefix . "sp_cu_project.id,

												" . $wpdb->prefix . "sp_cu_project.id AS pid,

												" . $wpdb->prefix . "sp_cu_project.uid,

												 " . $wpdb->prefix . "sp_cu_project.name AS project_name,

												  " . $wpdb->prefix . "sp_cu_project.parent

												 

										FROM " . $wpdb->prefix . "sp_cu_project

										WHERE (" . $wpdb->prefix . "sp_cu_project.uid = '" . $_GET['uid'] . "'  " . $find_groups . " ".$r_projects_groups_addon.")										

										

										" . $search_project . "

										";
									
										$r_projects_query .="

										ORDER by name";
							
						
							
		if(get_option('sp_cu_release_the_kraken') == 1){
								unset($r_projects_query);								
								$r_projects_query =	 "SELECT 										 
													" . $wpdb->prefix . "sp_cu_project.id,

												" . $wpdb->prefix . "sp_cu_project.id AS pid,

												" . $wpdb->prefix . "sp_cu_project.uid,

												 " . $wpdb->prefix . "sp_cu_project.name AS project_name,

												  " . $wpdb->prefix . "sp_cu_project.parent
										FROM " . $wpdb->prefix . "sp_cu_project
										WHERE id != ''
										
										" . $search_project . " ORDER by name
";
								}
								
			 $r_projects_query = apply_filters('sp_cdm_project_query_final', $r_projects_query);					
            $r_projects = $wpdb->get_results($r_projects_query, ARRAY_A);
        }
        echo '<div id="dlg_cdm_file_list">

		<table border="0" cellpadding="0" cellspacing="0">

		<thead>';
        if ($_GET['pid'] == '') {
            $jscriptpid = "''";
        } else {
            $jscriptpid = "'" . $_GET['pid'] . "'";
        }
        echo '<tr>';
		
		do_action('spdm_file_list_column_before_sort');

		echo '<th></th>

		<th class="cdm_file_info" style="text-align:left"><a href="javascript:sp_cdm_sort(\'name\',' . $jscriptpid . ')">' . __("Name", "sp-cdm") . '</a></th>

		<th class="cdm_file_date"><a href="javascript:sp_cdm_sort(\'date\',' . $jscriptpid . ')">' . __("Date", "sp-cdm") . '</a></th>

	

		<th class="cdm_file_type">' . __("Type", "sp-cdm") . '</th>	

		</tr>	

		

		';
		
		
		
        if (($_GET['pid'] != "0" && $_GET['pid'] != '') && ((get_option('sp_cu_user_projects') == 1 and get_option('sp_cu_user_projects_modify') != 1) or current_user_can('manage_options'))) {
            $r_project_info = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "sp_cu_project where id = " . $_GET['pid'] . "", ARRAY_A);
          
		  if($r_project_info[0]['uid'] == $_GET['uid']){
		    echo '<tr>

	

		<th colspan="100%" style="text-align:right">

		<div style="padding-right:10px">

	<a href="javascript:sp_cu_dialog(\'#edit_category_' . $_GET['pid'] . '\',550,130)"><img src="' . SP_CDM_PLUGIN_URL . 'images/application_edit.png"> '. __("Edit", "sp-cdm").' '.sp_cdm_folder_name() .' '. __("Name", "sp-cdm").'</a>   <a href="javascript:sp_cu_remove_project()" style="margin-left:20px"> <img src="' . SP_CDM_PLUGIN_URL . 'images/delete_small.png">  '. __("Remove", "sp-cdm").' '.sp_cdm_folder_name().'</a>

		

		<div style="display:none">	

		

		

		<script type="text/javascript">

		

			

function sp_cu_edit_project(){

	

	

	

	if(jQuery("#edit_project_name_' . $_GET['pid'] . '").val() == ""){

		

		alert("' . __("Please enter a project name", "sp-cdm") . '");

	}else{

	jQuery.ajax({

   type: "POST",

   url: "' . SP_CDM_PLUGIN_URL . 'ajax.php?function=save-category",

   data: "name=" + jQuery("#edit_project_name_' . $_GET['pid'] . '").val() + "&id=" +  jQuery("#edit_project_id_' . $_GET['pid'] . '").val(),

   success: function(msg){

   jQuery("#cmd_file_thumbs").load("' . SP_CDM_PLUGIN_URL . 'ajax.php?function=file-list&uid=' . $_GET['uid'] . '&pid=' . $_GET['pid'] . '");

   jQuery("#edit_category").dialog("close");

   alert(msg);	

  

   }

 });

	}

}



function sp_cu_remove_project(){



	jQuery( "#delete_category_' . $_GET['pid'] . '" ).dialog({

			resizable: false,

			height:240,

			width:440,

			modal: true,

			buttons: {

				"Delete all items": function() {

						

							

						jQuery.ajax({

					   type: "POST",

					   url: "' . SP_CDM_PLUGIN_URL . 'ajax.php?function=remove-category",

					   data: "id=' . $_GET['pid'] . '" ,

					   success: function(msg){
						
							jQuery.removeCookie("pid");
						
					  cdm_ajax_search();

					 	

					 

					  

					   }

					 });

					 

					jQuery( this ).dialog( "close" );	

						

				},

				Cancel: function() {

					jQuery( this ).dialog( "close" );

				}

			}

		});

	

	

	



	

}



		</script>	

		<div id="delete_category_' . $_GET['pid'] . '" title="' . __("Delete Category?", "sp-cdm") . '">

	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>' . __("Are you sure you would like to delete this category? Doing so will remove all files related to this category.", "sp-cdm") . '</p>

		</div>



		

		

				<div id="edit_category_' . $_GET['pid'] . '">			

			

			<input type="hidden"  name="edit_project_id" id="edit_project_id_' . $_GET['pid'] . '" value="' . $_GET['pid'] . '">		

			'.sp_cdm_folder_name() .' ' . __("Name", "sp-cdm") . ': <input value="' . stripslashes($r_project_info[0]['name']) . '" id="edit_project_name_' . $_GET['pid'] . '" type="text" name="name"  style="width:200px !important"> 

			<input type="submit" value="' . __("Save", "sp-cdm") . ' '.sp_cdm_folder_name() .'" onclick="sp_cu_edit_project()">

			

			</div>

			

		

		

		</div>

		

		

		</th>

		

		</tr>	

		

		';
		  }
        }
        echo '</thead><tbody>';
        if ($_GET['pid'] != 0) {
            $query_project = $wpdb->get_results("SELECT *

	

									

									 FROM " . $wpdb->prefix . "sp_cu_project

									WHERE  id = '" . $_GET['pid'] . "'

									

									 ", ARRAY_A);
            echo '<tr >';
			
			do_action('spdm_file_list_column_before_folder_back');

		echo '<td class="cdm_file_icon ext_directory" onclick="sp_cdm_load_project(' . $query_project[0]['parent'] . ')"></td>

		<td class="cdm_file_info" onclick="sp_cdm_load_project(' . $query_project[0]['parent'] . ')">&laquo; ' . __("Go Back", "sp-cdm") . '</td>

		<td class="cdm_file_date" onclick="sp_cdm_load_project(' . $query_project[0]['parent'] . ')">&nbsp;</td>

		

		<td class="cdm_file_type" onclick="sp_cdm_load_project(' . $query_project[0]['parent'] . ')">' . __("Folder", "sp-cdm") . '</td>	

		</tr>	

		';
        }
        if (count($r_projects) > 0) {
            for ($i = 0; $i < count($r_projects); $i++) {
                if ($r_projects[$i]['project_name'] != "") {
                    echo '<tr >
';
do_action('spdm_file_list_column_before_folder', $r_projects[$i]['pid']);
echo '
		<td class="cdm_file_icon ext_directory" onclick="sp_cdm_load_project(' . $r_projects[$i]['pid'] . ')"></td>

		<td class="cdm_file_info" onclick="sp_cdm_load_project(' . $r_projects[$i]['pid'] . ')">' . stripslashes($r_projects[$i]['project_name']) . '</td>

		<td class="cdm_file_date" onclick="sp_cdm_load_project(' . $r_projects[$i]['pid'] . ')">&nbsp;</td>

		

		<td class="cdm_file_type">Folder</td>	

		</tr>	

		';
                }
            }
        }
        if ($_GET['sort'] == '') {
            $sort = $this->order_by();
		
        } else {
            $sort = $_GET['sort'];
        }

		
        if ($_GET['pid'] == "" or $_GET['pid'] == "0" or $_GET['pid'] == "undefined" or $_GET['pid'] == "null") {
            if ($_REQUEST['search'] != "") {
                $search_file .= " AND (name LIKE '%" . $_REQUEST['search'] . "%' or  tags LIKE '%" . $_REQUEST['search'] . "%')  ";
            } else {
                $search_file .= " AND pid = 0  AND parent = 0  ";
            }
			$search_file = apply_filters("sp_cdm_file_search_query", $search_file, $_GET['pid']);
            $r = $wpdb->get_results("SELECT *  FROM " . $wpdb->prefix . "sp_cu   where (uid = '" . $_GET['uid'] . "' " . $find_groups . ")  	 " . $search_file . " order by " . $sort . " ", ARRAY_A);
			
        } else {
            if ($_REQUEST['search'] != "") {
                $search_file .= " AND (name LIKE '%" . $_REQUEST['search'] . "%' or  tags LIKE '%" . $_REQUEST['search'] . "%')  ";
            } else {
                $search_file .= "  AND parent = 0   ";
            }
			$search_file = apply_filters("sp_cdm_file_search_query", $search_file, $_GET['pid']);
            $r = $wpdb->get_results("SELECT *  FROM " . $wpdb->prefix . "sp_cu   where (pid = '" . $_GET['pid'] . "') " . $search_file . "  order by " . $sort . "  ", ARRAY_A);
			
        }
		
		
		if(get_option('sp_cu_release_the_kraken') == 1){
		unset($r);
		if($_GET['pid'] == ''){
		
		$_GET['pid'] = 0;
		
		}
		
		
		
		 if ($_REQUEST['search'] == "") {
		
			 $search_file .= " AND (pid = '" . $_GET['pid'] . "') ";
		 }
		 $search_file = apply_filters("sp_cdm_file_search_query", $search_file, $_GET['pid']);
		 $query = "SELECT *  FROM " . $wpdb->prefix . "sp_cu  where id != ''   " . $search_file . "  order by " . $sort . "  ";
		//echo  $query ;
		 $r = $wpdb->get_results( $query , ARRAY_A);	
		 
		
		}
	
        for ($i = 0; $i < count($r); $i++) {
            $ext   = preg_replace('/^.*\./', '', $r[$i]['file']);
            $r_cat = $wpdb->get_results("SELECT name  FROM " . $wpdb->prefix . "sp_cu_cats   where id = '" . $r[$i]['cid'] . "' ", ARRAY_A);
            if ($r_cat[0]['name'] == '') {
                $cat = stripslashes($r_cat[0]['name']);
            } else {
                $cat = '';
            }
            if ($_REQUEST['search'] != "" && sp_cdm_get_project_name($r[$i]['pid']) != false) {
                $project_name = ' <em>('.sp_cdm_folder_name() .': ' . sp_cdm_get_project_name($r[$i]['pid']) . ')</em> ';
            } else {
                $project_name = '';
            }
            echo '<tr >
			';
			do_action('spdm_file_list_column_before_file',$r[$i]['id'] );
			
			
			if(get_option('sp_cu_file_direct_access') == 1){
			$file_link = 	'window.open(\'' . SP_CDM_PLUGIN_URL . 'download.php?fid=' .base64_encode($r[$i]['id'].'|'.$r[$i]['date'].'|'.$r[$i]['file']) . '\')'; ;
			}else{
			$file_link =  'cdmViewFile(' . $r[$i]['id'] . ')';	
			}
			if(cdm_file_permissions($r[$i]['pid']) == 1){
			echo '
				<td class="cdm_file_icon ext_' . $ext . '" onclick="cdmViewFile(' . $r[$i]['id'] . ')"></td>

		<td class="cdm_file_info" onclick="'.$file_link.'">' . stripslashes($r[$i]['name']) . ' ' . $project_name . '</td>

		<td class="cdm_file_date" onclick="'.$file_link.'">' . date("F Y g:i A", strtotime($r[$i]['date'])) . '</td>



		<td class="cdm_file_type" onclick="'.$file_link.'">' . $ext . '</td>	

		</tr>	

		';
			}
        }
        echo '</tbody></table><div style="clear:both"></div></div>';
    }
    function thumbnails()
    {
        global $wpdb, $current_user;
        if (function_exists('cdmFindGroups')) {
            $find_groups = cdmFindGroups($_GET['uid'], 1);
        }
        if ($_REQUEST['search'] != "") {
            $search_project .= " AND " . $wpdb->prefix . "sp_cu_project.name LIKE '%" . $_REQUEST['search'] . "%' ";
        }else{
        if ($_GET['pid'] == '') {
            $search_project .= " AND " . $wpdb->prefix . "sp_cu_project.parent = '0' ";
        } else {
            $search_project .= " AND " . $wpdb->prefix . "sp_cu_project.parent = '" . $_GET['pid'] . "' ";
        }
		}
          if (get_option('sp_cu_hide_project') == 1) {
			
			
			$r_projects_query = "SELECT " . $wpdb->prefix . "sp_cu.name,

												 " . $wpdb->prefix . "sp_cu.id,

												 " . $wpdb->prefix . "sp_cu.pid ,

												 " . $wpdb->prefix . "sp_cu.uid,

												 " . $wpdb->prefix . "sp_cu.parent,

												 " . $wpdb->prefix . "sp_cu_project.name AS project_name,

												 " . $wpdb->prefix . "sp_cu_project.parent

												 

										FROM " . $wpdb->prefix . "sp_cu   

										LEFT JOIN " . $wpdb->prefix . "sp_cu_project  ON " . $wpdb->prefix . "sp_cu.pid = " . $wpdb->prefix . "sp_cu_project.id

										WHERE (" . $wpdb->prefix . "sp_cu.uid = '" . $_GET['uid'] . "'  " . $find_groups . ")

										AND pid != 0

										AND  " . $wpdb->prefix . "sp_cu.parent = 0 

										" . $sub_projects . "";
										
								if($_GET['pid'] == 0 or $_GET['pid'] == ''){
									$r_projects_query = apply_filters('sp_cdm_projects_query', $r_projects_query ,$_GET['uid']);	
										}

									$r_projects_query .="	" . $search_project . "
										
										GROUP BY pid

										ORDER by date desc";
				if(get_option('sp_cu_release_the_kraken') == 1){
								unset($r_projects_query);								
								$r_projects_query =	 "SELECT 										 
													" . $wpdb->prefix . "sp_cu_project.id,

												" . $wpdb->prefix . "sp_cu_project.id AS pid,

												" . $wpdb->prefix . "sp_cu_project.uid,

												 " . $wpdb->prefix . "sp_cu_project.name AS project_name,

												  " . $wpdb->prefix . "sp_cu_project.parent
										FROM " . $wpdb->prefix . "sp_cu_project
										WHERE id != ''
										
										" . $search_project . " ORDER by name
";
								}
			 $r_projects_query = apply_filters('sp_cdm_project_query_final', $r_projects_query);
            $r_projects = $wpdb->get_results($r_projects_query, ARRAY_A);
									
	
        } else {
			
			
									$r_projects_groups_addon = apply_filters('sp_cdm_projects_query', $r_projects_groups_addon ,$_GET['uid']);	
					
			$r_projects_query = "SELECT 

												" . $wpdb->prefix . "sp_cu_project.id,

												" . $wpdb->prefix . "sp_cu_project.id AS pid,

												" . $wpdb->prefix . "sp_cu_project.uid,

												 " . $wpdb->prefix . "sp_cu_project.name AS project_name,

												  " . $wpdb->prefix . "sp_cu_project.parent

												 

										FROM " . $wpdb->prefix . "sp_cu_project

										WHERE (" . $wpdb->prefix . "sp_cu_project.uid = '" . $_GET['uid'] . "'  " . $find_groups . " ".$r_projects_groups_addon.")										

										

										" . $search_project . "

										";
									
										$r_projects_query .="

										ORDER by name";
							
	
		
			if(get_option('sp_cu_release_the_kraken') == 1){
								unset($r_projects_query);								
								$r_projects_query =	 "SELECT 										 
													" . $wpdb->prefix . "sp_cu_project.id,

												" . $wpdb->prefix . "sp_cu_project.id AS pid,

												" . $wpdb->prefix . "sp_cu_project.uid,

												 " . $wpdb->prefix . "sp_cu_project.name AS project_name,

												  " . $wpdb->prefix . "sp_cu_project.parent
										FROM " . $wpdb->prefix . "sp_cu_project
										WHERE id != ''
										
										" . $search_project . " ORDER by name
";
								}
            $r_projects = $wpdb->get_results($r_projects_query, ARRAY_A);
        }
        echo '<div id="dlg_cdm_thumbnails">';
        if ($_GET['pid'] != "") {
            $r_current_project = $wpdb->get_results("SELECT *  FROM " . $wpdb->prefix . "sp_cu_project  WHERE id = " . $_GET['pid'] . "", ARRAY_A);
        }
       if (($_GET['pid'] != "0" && $_GET['pid'] != '') && ((get_option('sp_cu_user_projects') == 1 and get_option('sp_cu_user_projects_modify') != 1) or current_user_can('manage_options'))) {
            $r_project_info = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "sp_cu_project where id = " . $_GET['pid'] . "", ARRAY_A);
            if($r_project_info[0]['uid'] == $_GET['uid']){
		    echo '

			<div style="padding-right:10px">

		<a href="javascript:sp_cu_dialog(\'#edit_category_' . $_GET['pid'] . '\',550,130)"><img src="' . SP_CDM_PLUGIN_URL . 'images/application_edit.png"> '. __("Edit", "sp-cdm").' '.sp_cdm_folder_name() .' '. __("Name", "sp-cdm").'</a>   <a href="javascript:sp_cu_remove_project()" style="margin-left:20px"> <img src="' . SP_CDM_PLUGIN_URL . 'images/delete_small.png">  '. __("Remove", "sp-cdm").' '.sp_cdm_folder_name().'</a> 
';
 if(current_user_can('sp_cdm_show_folders_as_nav') or current_user_can('sp_cdm_projects') ){
		echo '<a href="'.admin_url('admin.php?page=sp-client-document-manager-projects&function=edit&id='.$_GET['pid'].'' ).'" style="margin-left:20px"> <img src="' . SP_CDM_PLUGIN_URL . 'images/application_edit.png">  '. __("Admin", "sp-cdm").'</a>';
 }
echo '
		

		<div style="display:none">	

		

		

	<script type="text/javascript">

		

		

function sp_cu_edit_project(){

	

	

	

	if(jQuery("#edit_project_name_' . $_GET['pid'] . '").val() == ""){

		

		alert("Please enter a project name");

	}else{

	jQuery.ajax({

   type: "POST",

   url: "' . SP_CDM_PLUGIN_URL . 'ajax.php?function=save-category",

   data: "name=" + jQuery("#edit_project_name_' . $_GET['pid'] . '").val() + "&id=" +  jQuery("#edit_project_id_' . $_GET['pid'] . '").val(),

   success: function(msg){

   jQuery("#cmd_file_thumbs").load("' . SP_CDM_PLUGIN_URL . 'ajax.php?function=file-list&uid=' . $_GET['uid'] . '&pid=' . $_GET['pid'] . '");

   jQuery("#edit_category").dialog("close");

   alert(msg);	

  

   }

 });

	}

}



function sp_cu_remove_project(){

	

	jQuery( "#delete_category_' . $_GET['pid'] . '" ).dialog({

			resizable: false,

			height:240,

			width:440,

			modal: true,

			buttons: {

				"Delete all items": function() {

						

							

						jQuery.ajax({

					   type: "POST",

					   url: "' . SP_CDM_PLUGIN_URL . 'ajax.php?function=remove-category",

					   data: "id=' . $_GET['pid'] . '" ,

					   success: function(msg){
						jQuery.removeCookie("pid");
					  cdm_ajax_search();

					 

					 

					  

					   }

					 });

					 

					jQuery( this ).dialog( "close" );	

						

				},

				Cancel: function() {

					jQuery( this ).dialog( "close" );

				}

			}

		});

	

	

	





	

}



		</script>	

		<div id="delete_category_' . $_GET['pid'] . '" title="' . __("Delete Category?", "sp-cdm") . '">

	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>' . __("Are you sure you would like to delete this category? Doing so will remove all files related to this category.", "sp-cdm") . '</p>

		</div>



		

		

				<div id="edit_category_' . $_GET['pid'] . '">			

			

			<input type="hidden"  name="edit_project_id" id="edit_project_id_' . $_GET['pid'] . '" value="' . $_GET['pid'] . '">		

			'.sp_cdm_folder_name() .' ' . __("Name", "sp-cdm") . ': <input value="' . stripslashes($r_project_info[0]['name']) . '" id="edit_project_name_' . $_GET['pid'] . '" type="text" name="name"  style="width:200px !important"> 

			<input type="submit" value="' . __("Save", "sp-cdm") . ' '.sp_cdm_folder_name() .'" onclick="sp_cu_edit_project()">

			

			</div>

			

		

		

		</div>

		

	

		

		</div>	

		

		';
        }
		}
			echo '

	<div style="float:right">' . __("Sort by", "sp-cdm") . ': <a href="javascript:sp_cdm_sort(\'name\',' . $_GET['pid'] . ')">' . __("Name", "sp-cdm") . '</a>   <a href="javascript:sp_cdm_sort(\'date\',' . $_GET['pid']. ')">' . __("Date", "sp-cdm") . '</a></div>

	
		

		';
		
		
			do_action('spdm_file_list_column_before_sort_thumbs');
		
		echo '<div style="clear:both"></div>';
        if ($_GET['pid'] != 0) {
            $query_project = $wpdb->get_results("SELECT *

	

									

									 FROM " . $wpdb->prefix . "sp_cu_project

									WHERE  id = '" . $_GET['pid'] . "'

									

									 ", ARRAY_A);
            echo '

				<div class="dlg_cdm_thumbnail_folder">

				<a href="javascript:sp_cdm_load_project(' . $query_project[0]['parent'] . ')"><img src="' . SP_CDM_PLUGIN_URL . 'images/my_projects_folder.png">

				<div class="dlg_cdm_thumb_title">

				&laquo; ' . __("Go Back", "sp-cdm") . '

				</div>

				</a>

				</div>

		

			

		

		';
        }
        if (count($r_projects) > 0) {
            for ($i = 0; $i < count($r_projects); $i++) {
                if ($r_projects[$i]['project_name'] != "") {
            
				
				
				//if(cdm_has_permission($_GET['uid'],$r_projects[$i]['uid'],$r_projects[$i]['pid'],'folder') ==1 ){
			
			        echo '

		<div class="dlg_cdm_thumbnail_folder">

				<a href="javascript:sp_cdm_load_project(' . $r_projects[$i]['pid'] . ')"><img src="' . SP_CDM_PLUGIN_URL . 'images/my_projects_folder.png">

				<div class="dlg_cdm_thumb_title">

				' . stripslashes($r_projects[$i]['project_name']) . '

				</div>

				</a>';
				
				do_action('spdm_file_thumbs_column_before_folder', $r_projects[$i]['pid']);
				echo '

				</div>

		

		';
				//}
                }
            }
        }
        //
        if ($_GET['sort'] == '') {
            $sort = $this->order_by();
        } else {
            $sort = $_GET['sort'];
        }
        if ($_GET['pid'] == "" or $_GET['pid'] == "0" or $_GET['pid'] == "undefined" or $_GET['pid'] == "null") {
            if ($_REQUEST['search'] != "") {
                $search_file .= " AND (name LIKE '%" . $_REQUEST['search'] . "%' or  tags LIKE '%" . $_REQUEST['search'] . "%')  ";
            } else {
                $search_file .= " AND pid = 0  AND parent = 0  ";
            }
			$search_file = apply_filters("sp_cdm_file_search_query", $search_file, $_GET['pid']);
            $r = $wpdb->get_results("SELECT *  FROM " . $wpdb->prefix . "sp_cu   where (uid = '" . $_GET['uid'] . "' " . $find_groups . ")  	 " . $search_file . " order by " . $sort . " ", ARRAY_A);
        } else {
            if ($_REQUEST['search'] != "") {
                $search_file .= " AND (name LIKE '%" . $_REQUEST['search'] . "%' or  tags LIKE '%" . $_REQUEST['search'] . "%')  ";
            } else {
                $search_file .= "  AND parent = 0   ";
            }
			$search_file = apply_filters("sp_cdm_file_search_query", $search_file, $_GET['pid']);
            $r = $wpdb->get_results("SELECT *  FROM " . $wpdb->prefix . "sp_cu   where (pid = '" . $_GET['pid'] . "') " . $search_file . "  order by " . $sort . "  ", ARRAY_A);
        }
		
		if(get_option('sp_cu_release_the_kraken') == 1){
		unset($r);
		if($_GET['pid'] == ''){
		
		$_GET['pid'] = 0;
		
		}
		
		
		
		 if ($_REQUEST['search'] == "") {
		
			 $search_file .= " AND (pid = '" . $_GET['pid'] . "') ";
		 }
		 $search_file = apply_filters("sp_cdm_file_search_query", $search_file, $_GET['pid']);
		 $query = "SELECT *  FROM " . $wpdb->prefix . "sp_cu  where id != ''   " . $search_file . "  order by " . $sort . "  ";
		// echo  $query ;
		 $r = $wpdb->get_results( $query , ARRAY_A);	
		 
		
		}
        for ($i = 0; $i < count($r); $i++) {
            $ext        = preg_replace('/^.*\./', '', $r[$i]['file']);
            $images_arr = array(
                "jpg",
                "png",
                "jpeg",
                "gif",
                "bmp"
            );
			
					if(get_option('sp_cu_user_projects_thumbs_pdf') == 1 && class_exists('imagick')){
	
			$info = new Imagick();
			$formats = $info->queryFormats();
			
			}else{
				$formats = array();
			}
	  
			
            if (in_array(strtolower($ext), $images_arr)) {
                if (get_option('sp_cu_overide_upload_path') != '' && get_option('sp_cu_overide_upload_url') == '') {
                    $img = '<img src="' . SP_CDM_PLUGIN_URL . 'images/package_labled.png">';
                } else {
                    $img = '<img src="' . sp_cdm_thumbnail('' . SP_CDM_UPLOADS_DIR_URL . '' . $r[$i]['uid'] . '/' . $r[$i]['file'] . '',NULL, 70) . '">';
				
                }
            } elseif ($ext == 'xls' or $ext == 'xlsx') {
                $img = '<img src="' . SP_CDM_PLUGIN_URL . 'images/microsoft_office_excel.png">';
            } elseif ($ext == 'doc' or $ext == 'docx') {
                $img = '<img src="' . SP_CDM_PLUGIN_URL . 'images/microsoft_office_word.png">';
            } elseif ($ext == 'pub' or $ext == 'pubx') {
                $img = '<img src="' . SP_CDM_PLUGIN_URL . 'images/microsoft_office_publisher.png">';
            } elseif ($ext == 'ppt' or $ext == 'pptx') {
                $img = '<img src="' . SP_CDM_PLUGIN_URL . 'images/microsoft_office_powerpoint.png">';
            } elseif ($ext == 'adb' or $ext == 'accdb') {
                $img = '<img src="' . SP_CDM_PLUGIN_URL . 'images/microsoft_office_access.png">';
            } elseif (in_array(strtoupper($ext),$formats)) {
                if (file_exists('' . SP_CDM_UPLOADS_DIR . '' . $r[$i]['uid'] . '/' . $r[$i]['file'] . '_small.png')) {
                    $img = '<img src="' . sp_cdm_thumbnail('' . SP_CDM_UPLOADS_DIR_URL . '' . $r[$i]['uid'] . '/' . $r[$i]['file'] . '_small.png',NULL, 70).'">';
				
                } else {
                    $img = '<img src="' . SP_CDM_PLUGIN_URL . 'images/adobe.png">';
                }
            } elseif ($ext == 'pdf') {
                $img = '<img src="' . SP_CDM_PLUGIN_URL . 'images/adobe.png">';
            } else {
                $img = '<img src="' . SP_CDM_PLUGIN_URL . 'images/package_labled.png">';
            }
            if ($_REQUEST['search'] != "" && sp_cdm_get_project_name($r[$i]['pid']) != false) {
                $project_name = ' <br><em>('.sp_cdm_folder_name() .': ' . sp_cdm_get_project_name($r[$i]['pid']) . ')</em> ';
            } else {
                $project_name = '';
            }
			
			$img = apply_filters('sp_cdm_viewfile_image', $img,$r[$i]);
			if(get_option('sp_cu_file_direct_access') == 1){
			$file_link = 	'window.open(\'' . SP_CDM_PLUGIN_URL . 'download.php?fid=' .base64_encode($r[$i]['id'].'|'.$r[$i]['date'].'|'.$r[$i]['file']) . '\'); void(0)'; ;
			}else{
			$file_link =  'cdmViewFile(' . $r[$i]['id'] . ')';	
			}
			if(cdm_file_permissions($r[$i]['pid']) == 1){
            echo '<div class="dlg_cdm_thumbnail_folder">

			<div class="dlg_cdm_thumbnail_image">

				<a href="javascript:'.$file_link.'" ><div class="cdm_img_container">' . $img . '</div>

				<div class="dlg_cdm_thumb_title">

				' . stripslashes($r[$i]['name']) . '' . $project_name . '

				</div>

				</a>

				</div>';
				do_action('spdm_file_thumbs_column_before_file', $r[$i]['id']);
				echo '

				</div>';
			}
        }
        echo '<div style="clear:both"></div></div>';
    }
    function download_project()
    {
        global $wpdb, $current_user;
        $user_ID     = $_GET['id'];
        $r           = $wpdb->get_results($wpdb->prepare("SELECT *  FROM " . $wpdb->prefix . "sp_cu   where pid = %d order by date desc,",$user_ID ), ARRAY_A);
        $r_project   = $wpdb->get_results($wpdb->prepare("SELECT *  FROM " . $wpdb->prefix . "sp_cu_project where id = %d  ",$user_ID ), ARRAY_A);
        $return_file = "" . preg_replace('/[^\w\d_ -]/si', '', stripslashes($r_project[0]['name'])) . ".zip";
        $zip         = new Zip();
        $dir         = '' . SP_CDM_UPLOADS_DIR . '' . $r_project[0]['uid'] . '/';
        $path        = '' . SP_CDM_UPLOADS_DIR_URL . '' . $r_project[0]['uid'] . '/';
        //@unlink($dir.$return_file);
        for ($i = 0; $i < count($r); $i++) {
            $zip->addFile(file_get_contents($dir . $r[$i]['file']), $r[$i]['file'], filectime($dir . $r[$i]['file']));
        }
        $zip->finalize(); // as we are not using getZipData or getZipFile, we need to call finalize ourselves.
        $zip->setZipFile($dir . $return_file);
        header("Location: " . $path . $return_file . "");
    }
    function download_archive()
    {
        global $wpdb, $current_user;
        $user_ID     = $_GET['id'];
        $dir         = '' . SP_CDM_UPLOADS_DIR . '' . $user_ID . '/';
        $path        = '' . SP_CDM_UPLOADS_DIR_URL . '' . $user_ID . '/';
        $return_file = "Account.zip";
        $zip         = new Zip();
        $r           = $wpdb->get_results($wpdb->prepare("SELECT *  FROM " . $wpdb->prefix . "sp_cu   where uid = %d  order by date desc",$user_ID), ARRAY_A);
        //@unlink($dir.$return_file);
        for ($i = 0; $i < count($r); $i++) {
            $zip->addFile(file_get_contents($dir . $r[$i]['file']), $r[$i]['file'], filectime($dir . $r[$i]['file']));
        }
        $zip->finalize(); // as we are not using getZipData or getZipFile, we need to call finalize ourselves.
        $zip->setZipFile($dir . $return_file);
        header("Location: " . $path . $return_file . "");
    }
    function email_vendor()
    {
        global $wpdb, $current_user;
        if (count($_POST['vendor_email']) == 0) {
            echo '<p style="color:red;font-weight:bold">' . __("Please select at least one file!", "sp-cdm") . '</p>';
        } else {
           
		   
		    $files = $_POST['vendor_email'];    
		 	$how_many = count($files);
			$placeholders = array_fill(0, $how_many, '%d');
			$format = implode(', ', $placeholders);
			$query = $wpdb->prepare("SELECT *  FROM " . $wpdb->prefix . "sp_cu  WHERE id IN ($format)", $files);
		 
			$r     = $wpdb->get_results($query, ARRAY_A);
      
            for ($i = 0; $i < count($r); $i++) {
                if ($r[$i]['name'] == "") {
                    $name = $r[$i]['file'];
                } else {
                    $name = $r[$i]['name'];
                }
				
				if($r[$i]['name'] == ''){
				$filename = $r[$i]['file'];
				}else{
				$filename = $r[$i]['name'];	
				}
                $attachment_links .= '<a href="' . SP_CDM_PLUGIN_URL . 'download.php?fid=' .base64_encode($r[$i]['id'].'|'.$r[$i]['date'].'|'.$r[$i]['file']). '">'.$filename . '</a><br>';
                $attachment_array[$i] = '' . SP_CDM_UPLOADS_DIR . '' . $r[$i]['uid'] . '/' . $r[$i]['file'] . '';
            }
         
        
       
            if ($_POST['vendor_attach'] == 3) {
                $attachments = $attachment_array;
                $links.= $attachment_links;
            } elseif ($_POST['vendor_attach'] == 1) {
                $attachments = $attachment_array;
            } else {
                $links .= $attachment_links;
            }
     
		 	$message =get_option('sp_cu_vendor_email');      
             $subject = get_option('sp_cu_vendor_email_subject');
               $message   = str_replace('[file]', $links, $message);	 
			   $message   = str_replace('[notes]',  $_POST['vendor-message'], $message);		
				$message = wpautop($message);
			//$headers = apply_filters('spcdm_admin_email_headers',$headers,$post, $uid);
			 if (get_option('sp_cu_vendor_email') != "") {
			 add_filter( 'wp_mail_content_type', 'set_html_content_type' );
		     wp_mail( $_POST['vendor'], stripslashes($subject),stripslashes( $message), $headers, $attachments);
			 remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
          
      		  }	
		  
		   
            echo '<p style="color:green;font-weight:bold">' . __("Files Sent to", "sp-cdm") . ' ' . $_POST['vendor'] . '</p>';
        }
    }
}
$spcdm_ajax = new spdm_ajax;
?>