<?php
class sp_cdm_fileview {
	
	
function view(){
	
	
	global $wpdb;
	
	
	
	echo '<h2>'.__("User Files","sp-cdm").'</h2>'.sp_client_upload_nav_menu().''.__("Choose a user below to view their files","sp-cdm").'<p>
	
	<script type="text/javascript">
	jQuery(document).ready(function() {
	
	jQuery("#user_uid").change(function() {
	
	window.location = "admin.php?page=sp-client-document-manager-fileview&id=" + jQuery("#user_uid").val();
	
	
	})
	});
	</script>
	<form>';
	wp_dropdown_users(array('name' => 'user_uid', 'show_option_none' => 'Choose a user', 'selected'=>$_GET['id']));
	 
	echo '</form>';
	if($_GET['id'] != ""){
		
		if($_GET['dlg-delete-file'] != ""){
	
		$r = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where  id = ".$_GET['dlg-delete-file']."", ARRAY_A);
		
		
		@unlink(''.SP_CDM_PLUGIN_DIR.''.$user_id.'/'.$r[0]['file'].'');
	
		$wpdb->query("
	DELETE FROM ".$wpdb->prefix."sp_cu WHERE id = ".$_GET['dlg-delete-file']."
	");
	

}

		
		
	
if( $_POST['submit-admin'] == 'Upload'){



	
	$data = $_POST;
	$files = $_FILES;
	$a['uid'] = $_GET['id'];
	$a['name'] = $data['dlg-upload-name'];
	$a['pid'] = $data['pid'];
	$a['cid'] = $data['cid'];
	$a['tags'] = $data['tags'];
	$a['notes'] = $data['dlg-upload-notes'];
	
		
	$dir = ''.SP_CDM_UPLOADS_DIR.''.$a['uid'].'/';
	if(!is_dir($dir)){
	
		mkdir($dir, 0777);
	}

	

	if($files['dlg-upload-file']['name'] != ""){
	
	
	
	$a['file'] = sp_Admin_uploadFile($files,$a['uid']);
	


    $wpdb->insert(  "".$wpdb->prefix."sp_cu", $a );
	$file_id = $wpdb->insert_id;

	 if (CU_PREMIUM == 1){ 
	  
	process_sp_cdm_form_vars($data['custom_forms'],$file_id );
	 
	 }
	
	
	$user_info = get_userdata($a['uid']);

	
	

	    if (get_option('sp_cu_admin_user_email') != "") {
      
					$subject =  sp_cu_process_email($file_id, get_option('sp_cu_admin_user_email_subject'));
                    $message = sp_cu_process_email($file_id, get_option('sp_cu_admin_user_email'));
                    $to      = $current_user->user_email;
                   
			$message = apply_filters('spcdm_user_email_message',$message,$post, $uid);
			$to = apply_filters('spcdm_user_email_to',$to,$post, $uid);
			$subject = apply_filters('spcdm_user_email_subject',$subject,$post, $uid);
			$attachments = apply_filters('spcdm_user_email_attachments',$attachments,$post, $uid);
			$user_headers = apply_filters('spcdm_user_email_headers',$user_headers,$post, $uid);
			
						 add_filter( 'wp_mail_content_type', 'set_html_content_type' );
                    wp_mail($to, $subject,stripslashes( $message), $user_headers, $attachments);
					 remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
                }
	
		echo  '<script type="text/javascript">


jQuery(document).ready(function() {
 sp_cu_dialog("#sp_cu_thankyou",400,200);
});
</script>';
}else{
	
	 '<p style="color:red">'.__("Please upload a file!","sp-cdm").'</p>';
	
}
}

		
		
		
		
		
		echo '
	<script type="text/javascript">
	
	function cdm_ajax_search(){
		
	var cdm_search = jQuery("#search_files").val();
	jQuery("#cmd_file_thumbs").load("'.SP_CDM_PLUGIN_URL.'ajax.php?function=file-list&uid='.$_GET['id'].'&search=" + cdm_search);		
		
	}
	</script>
	
	';
	
	
	
	
		echo '  <div style="display:none">
  <div id="cp_cdm_upload_form">
  '.$this->display_sp_upload_form().'
  </div>
  </div>
  
';
		
	$r = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where uid = ".$_GET['id']."  order by date desc", ARRAY_A);
	

	
	if(class_exists('cdmPremiumUploader') && get_option('sp_cu_free_uploader') != 1){
		global $premium_add_file_link;
		$link = $premium_add_file_link;
			global $cdmPremiumUploader;
	echo $cdmPremiumUploader->construct($_GET['id']);
		
	}else{
		$link = 'javascript:sp_cu_dialog(\'#cp_cdm_upload_form\',700,600)';
			}

echo'<div id="cdm_nav_buttons">';
			if (get_option('sp_cu_user_disable_search') == 1) {
            $hide_search = ';display:none;';
			}
			// do_action('cdm_add_hidden_html');
          
			 echo '<div style="padding:10px'.$hide_search.'">Search: <input  onkeyup="cdm_ajax_search()" type="text" name="search" id="search_files"></div>';
			
		   
		   
		   
                if (class_exists('cdmPremiumUploader') && get_option('sp_cu_free_uploader') != 1) {
                    global $premium_add_file_link;
                    $link = $premium_add_file_link;
                 
                } else {
                    $link = 'javascript:sp_cu_dialog(\'#cp_cdm_upload_form\',700,600)';
                }
                echo '  <a href="' . $link . '"  class="sp_cdm_add_file">' . __("Add File", "sp-cdm") . '</a> ';
               
			    echo'  <a href="#folder" class="sp_cdm_add_folder">' . __("Add Folder", "sp-cdm") . '</a> </span> ';
			
				$morebuttons = '';
                $morebuttons .= apply_filters('sp_cdm_more_buttons', $morebuttons);
               echo $morebuttons;
			   
			   
               echo '   <a href="javascript:cdm_ajax_search()"  class="sp_cdm_refresh">' . __("Refresh", "sp-cdm") . '</a> ';
            
			echo  '<div style="clear:both"></div></div>';
           echo '<div>';
echo $this->display_sp_thumbnails2($r );
	echo '</div>';
		 echo '</div>';
		  
		 


	}

	
	
	
}


function display_sp_thumbnails2($r){
	
	global $wpdb,$current_user;
	 
	 $user_ID = $_GET['id'];
	if(get_option('sp_cu_wp_folder') == ''){
	$wp_con_folder = '/';	
	}else{
		$wp_con_folder = get_option('sp_cu_wp_folder') ;
	}
	

	
	$content .='
	
	<script type="text/javascript">';
	
	
	$content .='
	
	function sp_cdm_sort(sort,pid){
	if(pid != ""){
		var pidurl = "&pid=" + pid;
	}else{
		var pidurl = "&cid=" + pid;	
	}
		jQuery("#cmd_file_thumbs").load("'.SP_CDM_PLUGIN_URL.'ajax.php?function=file-list&uid='.$user_ID.'&sort=" + sort + "" + pidurl);	
}

	
	function sp_cdm_loading_image(){
		jQuery("#cmd_file_thumbs").html(\'<div style="padding:100px; text-align:center"><img src="'.SP_CDM_PLUGIN_URL.'images/loading.gif"></div>\');		
	}
	function sp_cdm_load_file_manager(){
		sp_cdm_loading_image();
	jQuery("#cmd_file_thumbs").load("'.SP_CDM_PLUGIN_URL.'ajax.php?function=file-list&uid='.$user_ID.'");	
	cdm_ajax_search();
	}
	
	jQuery(document).ready( function() {
		
		';
		
		if($_GET['id'] != '' && $_COOKIE['uid'] != $_GET['id']){
			
		$content .='jQuery.cookie("uid", "'.$_GET['id'].'", { expires: 7 });
					jQuery.removeCookie("pid"); 
					';	
		
		
		}
		
		
		$content .='
		
			
			
	var pid = jQuery.cookie("pid");

	if(pid != 0){
	sp_cdm_load_project(pid)
	}else{
	sp_cdm_load_file_manager();	
	}
		

			
		});
		
		
	function sp_cdm_load_project(pid){

			sp_cdm_loading_image();
	jQuery("#cdm_current_folder").val(pid);
	
	jQuery(".cdm_premium_pid_field").attr("value", pid);
	jQuery.cookie("pid", pid, { expires: 7 });
	
			if(pid != 0 && jQuery("#cdm_premium_sub_projects").val() != 1){
				jQuery(".cdm_add_folder_button").hide();	
			
				}else{
				jQuery(".cdm_add_folder_button").show();
			
				}
	    cdm_check_folder_perms(pid);
		  cdm_check_file_perms(pid);
		  jQuery(".cdm_premium_pid_field").val(pid);
		  
		  jQuery("#sub_category_parent").val(pid);
		jQuery("#cmd_file_thumbs").load("' . SP_CDM_PLUGIN_URL . 'ajax.php?function=file-list&uid=' . $user_ID . '&pid=" + pid);	

		

		}

		
		function sp_cdm_showFile(file){
			
		  var url = "'.SP_CDM_PLUGIN_URL.'admin/ajax.php?function=view-file&id=" + file;
		  
		 
            // show a spinner or something via css
            var dialog = jQuery(\'<div style="display:none" class="loading viewFileDialog"></div>\').appendTo(\'body\');
          
		  

     var fileArray = new Array();      
	 var obj_file_info =   jQuery.getJSON("'.SP_CDM_PLUGIN_URL.'admin/ajax.php?function=get-file-info&type=name&id=" + file, function(data) {
   

	
		
  	fileArray[name] =data.name;
	var final_title = fileArray[name];
       });
		 

		 
		 var final_title = fileArray[name];
		
		      dialog.dialog({
               
                close: function(event, ui) {
                    // remove div with all data and events
                    dialog.remove();
                },
                modal: true,
				height:"auto",
				width:850,
				title: final_title 
            });
			
			 // load remote content
            dialog.load(
                url, 
                {}, // omit this param object to issue a GET request instead a POST request, otherwise you may provide post parameters within the object
                function (responseText, textStatus, XMLHttpRequest) {
                    // remove the loading class
                    dialog.removeClass(\'loading\');
                }
            );
			
			
		

		}
	</script>';
	$content .='<form id="cdm_wrapper_form">';
	
	$extra_js = apply_filters('sp_cdm_uploader_above_admin',$extra_js);
	$content .=''.$extra_js.'<input type="hidden" value="'.SP_CDM_PLUGIN_URL.'ajax.php" id="sp_cu_ajax_url" class="sp_cu_ajax_url" name="sp_cu_ajax_url"><div id="cdm_wrapper">';
	$extra_js = '';



	$content .='
	<div id="cmd_file_thumbs">
	<div style="padding:100px; text-align:center"><img src="'.SP_CDM_PLUGIN_URL.'images/loading.gif"></div>	
	
	</div>
	<div style="clear:both"></div>
	</div></form>
	';
	return $content;
	
}










function display_sp_upload_form(){
	
global $wpdb,$current_user;



$html .='
<script type="text/javascript">


function sp_cdm_change_indicator(){
	

jQuery(".sp_change_indicator_button").hide();
jQuery(".sp_change_indicator").slideDown();



jQuery(\'.sp_change_indicator\').html(\'<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0"  width="204" height="16"  id="mymoviename"><param name="movie" value="'.SP_CDM_PLUGIN_URL.'image_138464.swf" /><param name="quality" value="high" /><param name="bgcolor" value="#ffffff" /><embed src="'.SP_CDM_PLUGIN_URL.'image_138464.swf" quality="high" bgcolor="#ffffff" width="204" height="16" name="mymoviename" align="" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed></object><br><em>Please wait, your file is currently uploading! </em>\');
document.forms["sp_upload_form"].submit();
return true;

}


jQuery(document).ready(function() {
	jQuery("#upload_form").simpleValidate({
	  errorElement: "em",
	  ajaxRequest: false,
	  errorText: "'.__("Required","sp-cdm").'",
	   completeCallback: function() {
      
	  sp_cdm_change_indicator();
	  }
	});
});


function sp_cu_add_project(){
	
	jQuery.ajax({
   type: "POST",
   url: "'.SP_CDM_PLUGIN_URL.'ajax.php?function=save-category",
   data: "name=" + jQuery("#sub_category_name").val() + "&uid=" +  jQuery("#sub_category_uid").val()+ "&parent=" +  jQuery("#sub_category_parent").val(),
   success: function(msg){
  
   jQuery("#sp_cu_add_project").dialog("close");
 
 jQuery(".pid_select").append(jQuery("<option>", { 
    value: msg, 
    text : jQuery("#sub_category_name").val(),
	selected : "selected"
 	 }
	 ));
 
 
 location.reload();
	
  
   }
 });
}
</script>
<div style="display:none">';


	


	


$html .=''.$add_project .'

<div id="sp_cu_confirm_delete">
<p>'.get_option('sp_cu_delete').'</p>
</div>

<div id="sp_cu_thankyou">
<p>'.get_option('sp_cu_thankyou').'</p>
</div>


		

</div>

<form  action="'.$_SERVER['REQUEST_URI'].'" method="post" enctype="multipart/form-data" id="upload_form" name="sp_upload_form" >
<input type="hidden" name="admin-uploader" value="1">
';




			$html .='<div>
			<p>'.stripslashes(get_option('sp_cu_form_instructions')).'</p>
				  <table width="100%" cellpadding="2" cellspacing="2" style="border:none;padding:0px;margin:0px;">
  <tr>
    <td>'.__("File Name:","sp-cdm").'</td>
    <td><input  type="text" name="dlg-upload-name" class="required"></td>
  </tr>
  
  ';
  
 $html .= $this->sp_cdm_display_projects(); 
  
 if (CU_PREMIUM == 1){ 

 $html .= sp_cdm_display_categories(); 
 }
 

 
  $html .= '
  <tr>
    <td>'.__("File:","sp-cdm").'</td>
    <td>	<div id="cdm_upload_fields">    <input id="file_upload" name="dlg-upload-file[]" type="file" class="required">
<div id="upload_list"></div></div>
							</td>
  </tr>';
  
    if (CU_PREMIUM == 1){ 
	
	if( get_option('sp_cu_enable_tags') ==1){
   $html .= '
  <tr>
    <td>'.__("Tags:","sp-cdm").'</td>
    <td><textarea id="tags" name="tags"  style="width:90%;height:30px"></textarea></td>
  </tr>';
  
	}
  
  $html .=display_sp_cdm_form();
  
  }else{
	  
  $html .='<tr>
    <td>'.__("Notes:","sp-cdm").'</td>
    <td><textarea style="width:90%;height:50px" name="dlg-upload-notes"></textarea></td>
  </tr>
  ';
  }
  $html .='
  <tr>
    <td>&nbsp;</td>
    <td>
						<div class="sp_change_indicator_button"><input id="dlg-upload" onclick="sp_change_indicator()" type="submit" name="submit-admin" value="Upload" ></div>
						<div class="sp_change_indicator" ></div>	
							</td>
  </tr>';

  
  $html .='
</table></div>';




$html .='

	</form>
	
	
	';
	
	return $html;
}


function display_sp_client_upload($atts){

	global $wpdb ;
	global $user_ID;
	global $current_user;
     get_currentuserinfo();
 if ( is_user_logged_in() ) { 



if($_GET['dlg-delete-file'] != ""){
	
	
	
		$wpdb->query("
	DELETE FROM ".$wpdb->prefix."sp_cu WHERE id = ".$_GET['dlg-delete-file']."
	");
	
		 
}

if($_POST['submit-admin'] != ""){



	
	$data = $_POST;
	$files = $_FILES;
	$a['uid'] = $user_ID;
	$a['name'] =$data['dlg-upload-name'];
	$a['pid'] = $data['pid'];
	$a['cid'] = $data['cid'];
	$a['tags'] = $data['tags'];
	$a['notes'] = $data['dlg-upload-notes'];
	
	
	check_folder_sp_client_upload();
	

	if($files['dlg-upload-file']['name'] != ""){
	
	print_r($a);exit;
	
	$a['file'] = sp_uploadFile($files);
    $wpdb->insert(  "".$wpdb->prefix."sp_cu", $a );
	$file_id = $wpdb->insert_id;
	
	add_user_meta(  $user_ID, 'last_project', $a['pid']);
	 if (CU_PREMIUM == 1){ 
	  
	 process_sp_cdm_form_vars($data['custom_forms'],$wpdb->insert_id);
	 
	 }
	 
	$to = get_option('admin_email');

	
	
		
		
		    if (get_option('sp_cu_admin_user_email') != "") {
      
					$subject =  sp_cu_process_email($file_id, get_option('sp_cu_admin_user_email_subject'));
                    $message = sp_cu_process_email($file_id, get_option('sp_cu_admin_user_email'));
                    $to      = $current_user->user_email;
                   
			$message = apply_filters('spcdm_user_email_message',$message,$post, $uid);
			$to = apply_filters('spcdm_user_email_to',$to,$post, $uid);
			$subject = apply_filters('spcdm_user_email_subject',$subject,$post, $uid);
			$attachments = apply_filters('spcdm_user_email_attachments',$attachments,$post, $uid);
			$user_headers = apply_filters('spcdm_user_email_headers',$user_headers,$post, $uid);
			
						 add_filter( 'wp_mail_content_type', 'set_html_content_type' );
                    wp_mail($to, stripslashes($subject),stripslashes( $message), $user_headers, $attachments);
					 remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
                }

	    if (get_option('sp_cu_user_email') != "") {
      
					$subject =  sp_cu_process_email($file_id, get_option('sp_cu_admin_user_email_subject'));
                    $message = sp_cu_process_email($file_id, get_option('sp_cu_admin_user_email'));
                    $to      = $current_user->user_email;
                   
			$message = apply_filters('spcdm_user_email_message',$message,$post, $uid);
			$to = apply_filters('spcdm_user_email_to',$to,$post, $uid);
			$subject = apply_filters('spcdm_user_email_subject',$subject,$post, $uid);
			$attachments = apply_filters('spcdm_user_email_attachments',$attachments,$post, $uid);
			$user_headers = apply_filters('spcdm_user_email_headers',$user_headers,$post, $uid);
			
						 add_filter( 'wp_mail_content_type', 'set_html_content_type' );
                    wp_mail($to, stripslashes($subject),stripslashes( $message), $user_headers, $attachments);
					 remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
                }
		$html .= '<script type="text/javascript">


jQuery(document).ready(function() {
 sp_cu_dialog("#sp_cu_thankyou",400,200);
});
</script>';
}else{
	
	$html .= '<p style="color:red">'.__("Please upload a file!","sp-cdm").'</p>';
	
}
}





$r = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where uid = $user_ID  order by date desc", ARRAY_A);




//show uploaded documents
  $html .= '
  

  
  
    <div style="display:none">
  <div id="cp_cdm_upload_form">
  '.display_sp_upload_form().'
  </div>
  </div>
  


	
	<div >

 
';

if(get_option('sp_cu_user_projects_thumbs') == 1){

	
		$html .='
	<script type="text/javascript">
	
	function cdm_ajax_search(){
		
	var cdm_search = jQuery("#search_files").val();
	jQuery("#cmd_file_thumbs").load("'.SP_CDM_PLUGIN_URL.'admin/ajax.php?function=thumbnails&uid='.$user_ID.'&search=" + cdm_search);		
		
	}
	</script>
	
	';
	
}else{
	$html .='
	<script type="text/javascript">
	
	function cdm_ajax_search(){
		
	var cdm_search = jQuery("#search_files").val();
	jQuery("#cmd_file_thumbs").load("'.SP_CDM_PLUGIN_URL.'ajax.php?function=file-list&uid='.$user_ID.'&search=" + cdm_search);		
		
	}
	</script>
	
	';
}
	
	
	$html .='Search: <input  onkeyup="cdm_ajax_search()" type="text" name="search" id="search_files">';
	
	if(get_option('sp_cu_user_uploads_disable') != 1){
	$html .='  <a href="javascript:sp_cu_dialog(\'#cp_cdm_upload_form\',700,600)"><img src="'.SP_CDM_PLUGIN_URL.'images/add.png"> '.__("Add File","sp-cdm").'</a>  ';
	}
if(get_option('sp_cu_user_projects_thumbs') == 1 && CU_PREMIUM == 1){
		$html .=display_sp_cdm_thumbnails($r );
}else{
		$html .=display_sp_thumbnails2($r );
}
		$html .='</div>';





 
  } else{
	  
	  return '<script type="text/javascript">
<!--
window.location = "'. get_bloginfo('url').'/login/?redirect_to='.urlencode($_SERVER['REQUEST_URI']).'"
//-->
</script>';
	 
  }
  
return $html;
  
}
function sp_cdm_display_projects(){
	
	
	global $wpdb,$current_user;

if($_POST['add-project'] != ""){
	
			$insert['name'] = $_POST['project-name'];
			$insert['uid'] = $_GET['id'];
	$wpdb->insert( "".$wpdb->prefix . "sp_cu_project",$insert );
}


if (CU_PREMIUM == 1){  	
		$find_groups = cdmFindGroups($_GET['id'],'_project');
			 }

  $projects = $wpdb->get_results("SELECT *
	
									 FROM ".$wpdb->prefix."sp_cu_project
									WHERE  ( uid = '".$_GET['id']."' ".$find_groups.") 
									 ", ARRAY_A);	


  if(count($projects) > 0 or get_option('sp_cu_user_projects') == 1){
	  $html .= ' <tr>
    <td>'.sp_cdm_folder_name() .': 
	

	
	
	</td>
    <td>
	<select name="pid" id="pid_select">';
	
	if(get_option('sp_cu_user_projects_required') == 0){	
	$html .='<option name="" selected="selected">'.__("No","sp-cdm").' '.sp_cdm_folder_name() .'</option>';	
	}
		for($i=0; $i<count($projects); $i++){
								
		if($current_user->last_project == $projects[$i]['id'] ){	
			$required = ' selected="selected" '	;
		}else{
			$required = ''	;
		}
	  $html .= '<option value="'.$projects[$i]['id'].'" '.$required.'>'.stripslashes($projects[$i]['name']).'</option>';	
		}
	
	$html .='</select>';
	
	if(get_option('sp_cu_user_projects') == 1  or current_user_can( 'manage_options' )){
		
		$html .= '<a href="javascript:sp_cu_dialog(\'#sp_cu_add_project\',550,130)" class="button" style="margin-left:15px">'.__("Add","sp-cdm").' '.sp_cdm_folder_name() .'</a>
		
	
		
		';
		
	}
	$html .='</td>
  </tr>';
	  
  }

	return $html;
	
}
}

?>