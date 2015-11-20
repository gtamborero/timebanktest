<?php
if (!function_exists('sp_client_upload_settings')) {
	function sp_cdm_remove_accents($string) {
    if ( !preg_match('/[\x80-\xff]/', $string) )
        return $string;

    $chars = array(
    // Decompositions for Latin-1 Supplement
    chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
    chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
    chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
    chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
    chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
    chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
    chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
    chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
    chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
    chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
    chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
    chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
    chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
    chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
    chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
    chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
    chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
    chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
    chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
    chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
    chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
    chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
    chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
    chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
    chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
    chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
    chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
    chr(195).chr(191) => 'y',
    // Decompositions for Latin Extended-A
    chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
    chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
    chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
    chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
    chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
    chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
    chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
    chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
    chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
    chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
    chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
    chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
    chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
    chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
    chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
    chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
    chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
    chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
    chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
    chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
    chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
    chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
    chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
    chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
    chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
    chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
    chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
    chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
    chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
    chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
    chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
    chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
    chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
    chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
    chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
    chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
    chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
    chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
    chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
    chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
    chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
    chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
    chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
    chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
    chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
    chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
    chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
    chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
    chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
    chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
    chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
    chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
    chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
    chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
    chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
    chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
    chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
    chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
    chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
    chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
    chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
    chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
    chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
    chr(197).chr(190) => 'z', chr(197).chr(191) => 's'
    );

    $string = strtr($string, $chars);

    return $string;
}
	
	function cdm_has_permission($uid,$file_uid,$id,$type){
			global $wpdb,$current_user;
		$view = 0; 
		

	
			
		if($file_uid == $uid){ $view = 1; }	
			
		$view = apply_filters('sp_cdm_has_permission',$view,$uid,$file_uid,$id,$type);	
	
		return $view;
		
		
		
	}
	
	
	function cdm_user_can_delete($uid){
		
		  if (
		  
		  ((
		  
		  ($current_user->ID == $r[0]['uid'] or cdmFindLockedGroup($current_user->ID, $r[0]['uid']) == true or get_option('' . $this->namesake . '_project_remove_' .  $r[0]['pid'] . '') == 1)
		   && 
		   get_option('sp_cu_user_delete_disable') != 1) or current_user_can('manage_options')) 
		   && 
		(get_option('sp_cdm_groups_addon_global_remove_roles_'.sp_cdm_get_current_user_role_name ().'') == '' 
		or get_option('sp_cdm_groups_addon_global_remove_roles_'.sp_cdm_get_current_user_role_name ().'') == 1 )
		) {
			return true;
			
		}else{
				return false;
		}
	}
	
	function cdm_user_can_add($uid){
		 if (get_option('sp_cu_user_uploads_disable') != 1  and( 
			(get_option('sp_cdm_groups_addon_global_add_roles_'.sp_cdm_get_current_user_role_name ().'') == '' or
			get_option('sp_cdm_groups_addon_global_add_roles_'.sp_cdm_get_current_user_role_name ().'') == 1 )
			)
			) {
				return true;
				
			}else{
				return false;	
			}
		
	}
    function cdmFindLockedGroup($uid, $creator_id)
    {
        global $wpdb;
        $r_group_user = $wpdb->get_results("SELECT " . $wpdb->prefix . "sp_cu_groups_assign.gid,

											  " . $wpdb->prefix . "sp_cu_groups_assign.uid,

											  " . $wpdb->prefix . "sp_cu_groups_assign.id AS asign_id,

											  " . $wpdb->prefix . "sp_cu_groups.name,

											

											  " . $wpdb->prefix . "sp_cu_groups.id AS group_id

											    FROM " . $wpdb->prefix . "sp_cu_groups_assign 

												LEFT JOIN   " . $wpdb->prefix . "sp_cu_groups ON " . $wpdb->prefix . "sp_cu_groups_assign.gid = " . $wpdb->prefix . "sp_cu_groups.id

												WHERE uid = '" . $uid . "' ", ARRAY_A);
        $serve        = 0;
        for ($i = 0; $i < count($r_group_user); $i++) {
            if ($r_group_user[$i]['gid'] != "") {
                $r_group_user_select[$i] = $wpdb->get_results("SELECT * FROM  " . $wpdb->prefix . "sp_cu_groups_assign  

										LEFT JOIN " . $wpdb->prefix . "sp_cu_groups ON  " . $wpdb->prefix . "sp_cu_groups_assign.gid = " . $wpdb->prefix . "sp_cu_groups.id 

										WHERE " . $wpdb->prefix . "sp_cu_groups_assign.uid = " . $creator_id . " AND " . $wpdb->prefix . "sp_cu_groups_assign.gid = " . $r_group_user[$i]['group_id'] . " ", ARRAY_A);
                if ($r_group_user_select[$i][0]['id'] != "" && $r_group_user_select[$i][0]['locked'] == 1) {
                    $serve += 1;
                }
            }
        }
        if ($serve > 0) {
            return true;
        } else {
            return false;
        }
    }
	
	
	
	function cdm_thumbPdf($pdf){
		
		if(class_exists('imagick')){
			
			$tmp    = SP_CDM_UPLOADS_DIR;
            $format = "png";
            $source = $pdf;
            $dest   = "" . $pdf . "_big.$format";
            $dest2   = "" . $pdf . "_small.$format";
			
			
			// read page 1 
$im = new imagick( ''.  $source.'[0]' ); 

// convert to jpg 
$im->setImageColorspace(255); 
$im->setImageFormat( $format); 

//resize 
$im->resizeImage(650, 650, imagick::FILTER_LANCZOS, 1);  

//write image on server 
$im->writeImage($dest); 

//resize 
$im->resizeImage(250, 250, imagick::FILTER_LANCZOS, 1);  

//write image on server 
$im->writeImage($dest2); 

$im->clear(); 
$im->destroy(); 
			
		}else{
			echo 'php-image-magick not installed. Please disable the pdf thumbnail options or install the php extention correctly.';exit;
		}
		
	
	}
    function __depcreated_cdm_thumbPdf($pdf)
    {
        try {
            $tmp    = SP_CDM_UPLOADS_DIR;
            $format = "png";
            $source = $pdf;
            $dest   = "" . $pdf . "_small.$format";
            $dest2  = "" . $pdf . "_big.$format";
            if (get_option('sp_cu_image_magick_path') != '') {
                $imageMagick_path = get_option('sp_cu_image_magick_path');
            } else {
                $imageMagick_path = '/usr/local/bin/convert';
            }
            $exec = "" . $imageMagick_path . " -scale 80x80 " . $source . "[0] $dest";
            $debug .= $exec . '<br>';
            exec($exec, $output, $result);
            if ($result != true) {
            } else {
                $debug .= '<br>Converted: ' . $result . '<br>';
            }
            $exec2 = "" . $imageMagick_path . " -scale 250x250 " . $source . "[0] $dest2";
            $debug .= $exec2 . '<br>';
            exec($exec2, $output, $result);
            if ($result != true) {
            } else {
                $debug .= '<br>Converted: ' . $result . '<br>';
            }
            $im = new Imagick($dest);
        }
        catch (Exception $e) {
            // echo $e->getMessage();
            $debug .= $e->getMessage() . '<br>';
        }
    }
    function sp_client_upload_settings()
    {
        global $wpdb;
        if (@$_POST['save_options'] != '') {
            foreach ($_POST as $key => $value) {
                update_option($key, $value);
            }
            if ($_POST['sp_cu_user_projects'] == "1") {
                update_option('sp_cu_user_projects', '1');
            } else {
                update_option('sp_cu_user_projects', '0');
            }
            if ($_POST['sp_cu_user_projects_required'] == "1") {
                update_option('sp_cu_user_projects_required', '1');
            } else {
                update_option('sp_cu_user_projects_required', '0');
            }
            if ($_POST['sp_cu_js_redirect'] == "1") {
                update_option('sp_cu_js_redirect', '1');
            } else {
                update_option('sp_cu_js_redirect', '0');
            }
            if ($_POST['sp_cu_user_uploads_disable'] == "1") {
                update_option('sp_cu_user_uploads_disable', '1');
            } else {
                update_option('sp_cu_user_uploads_disable', '0');
            }
            if ($_POST['sp_cu_user_delete_disable'] == "1") {
                update_option('sp_cu_user_delete_disable', '1');
            } else {
                update_option('sp_cu_user_delete_disable', '0');
            }
            if ($_POST['sp_cu_hide_project'] == "1") {
                update_option('sp_cu_hide_project', '1');
            } else {
                update_option('sp_cu_hide_project', '0');
            }
            if ($_POST['sp_cu_user_require_login_download'] == "1") {
                update_option('sp_cu_user_require_login_download', '1');
            } else {
                update_option('sp_cu_user_require_login_download', '0');
            }
			 if ($_POST['sp_cu_user_projects_modify'] == "1") {
                update_option('sp_cu_user_projects_modify', '1');
            } else {
                update_option('sp_cu_user_projects_modify', '0');
            }
			 if ($_POST['sp_cu_user_disable_search'] == "1") {
                update_option('sp_cu_user_disable_search', '1');
            } else {
                update_option('sp_cu_user_disable_search', '0');
            }
			
			
			
        }
		
		 if (get_option('sp_cu_user_disable_search') == 1) {
            $sp_cu_user_disable_search = ' checked="checked" ';
        } else {
            $sp_cu_user_disable_search= '  ';
        }
		
        if (get_option('sp_cu_user_projects_required') == 1) {
            $sp_cu_user_projects_required = ' checked="checked" ';
        } else {
            $sp_cu_user_projects_required = '  ';
        }
        if (get_option('sp_cu_user_projects') == 1) {
            $sp_cu_user_projects = ' checked="checked" ';
        } else {
            $sp_cu_user_projects = '  ';
        }
        if (get_option('sp_cu_js_redirect') == 1) {
            $sp_cu_js_redirect = ' checked="checked" ';
        } else {
            $sp_cu_js_redirect = '  ';
        }
        if (get_option('sp_cu_user_uploads_disable') == 1) {
            $sp_cu_user_uploads_disable = ' checked="checked" ';
        } else {
            $sp_cu_user_uploads_disable = '  ';
        }
        if (get_option('sp_cu_user_delete_disable') == 1) {
            $sp_cu_user_delete_disable = ' checked="checked" ';
        } else {
            $sp_cu_user_delete_disable = '  ';
        }
        if (get_option('sp_cu_hide_project') == 1) {
            $sp_cu_hide_project = ' checked="checked" ';
        } else {
            $sp_cu_hide_project = '  ';
        }
        if (get_option('sp_cu_user_require_login_download') == 1) {
            $sp_cu_user_require_login_download = ' checked="checked" ';
        } else {
            $sp_cu_user_require_login_download = '  ';
        }
		
		 if (get_option('sp_cu_user_projects_modify') == 1) {
            $sp_cu_user_projects_modify = ' checked="checked" ';
        } else {
            $sp_cu_user_projects_modify = '  ';
        }
		
	
        echo '' . sp_client_upload_nav_menu() . '';
     
	 if(current_user_can('sp_cdm_help')){
	    echo '

<div class="updated">';
        if (@CU_PREMIUM != 1) {
            echo '<h3>Upgrade to premium!</h3>

<p>If you would like to see the extra features and upgrade to premium please purchase the addon package by <a href="http://smartypantsplugins.com/sp-client-document-manager/" target="_blank">clicking here</a>. Once purchased you will receive a file, upload that file to your plugins directory or go to plugins > add new > upload and upload the zip file. Once you upload activate the plugin and let the fun begin!</p>';
        } else {
          }
        echo '





</div>';
	

 }
echo '
 <script>
  jQuery(function() {
    jQuery( "#cdm-settinsg-tabs" ).tabs();
	
	
	
  });
  </script>
<style type="text/css">
strong{font-weight:800}
</style>

	<form action="admin.php?page=sp-client-document-manager-settings&save_options=1" method="post">
	<div id="cdm-settinsg-tabs">
  <ul>
    <li><a href="#cdm-tab-settings">Main Settings</a></li>
    <li><a href="#cdm-tab-mail">Mail Settings</a></li>
     <li><a href="#cdm-tab-premium">Premium Settings</a></li>
	   <li><a href="#cdm-tab-advanced">Advanced Settings</a></li>
  
  </ul>
  <div id="cdm-tab-settings">
 ';
  if($_REQUEST['force_upgrades'] == 1){
	
	echo'

<div class="updated">
Database verified, you should be good to go!</a>
</div>';
}else{
	
echo'

<div class="update-nag">
Having problems? <a href="admin.php?page=sp-client-document-manager-settings&force_upgrade=1&force_upgrades=1">Click here to make sure your database structure is correct</a>
</div>';

}
  echo '
	 <table class="wp-list-table widefat fixed posts" cellspacing="0">

    <tr>

    <td width="300"><strong>Company Name</strong><br><em>This could be your name or your company name which will go in the "from" area in the vendor email.</em></td>

    <td><input type="text" name="sp_cu_company_name"  value="' . get_option('sp_cu_company_name') . '"  size=80"> </td>

  </tr>

		 <tr>

    <td width="300"><strong>Filename Format</strong><br><em>Use the below codes to determine the file format, whatever you put in the box will show up before the actual file name.If you keep this blank then you leave the risk to existing files. Please see the example to the right.</em><br><br>

	%y =  Year: yyyy<br> 

	%d =  Day:  dd<br>

	%m =  Month: mm<br>

	%h =  Hour: 24 hour format<br>

	%min = Minute<br>

	%u = Username<br>

	%uid = User ID<br>

	%t = Timstamp<br>

	%r = Random #<br>

	

	</td>

    <td><input type="text" name="sp_cu_filename_format"  value="' . get_option('sp_cu_filename_format') . '"  size=80"><br><div style="margin:5px;padding:5px;"> Example:<br><br>

	If the user uploads a file called example.pdf and you put<strong>  %y-%m-%d-</strong> the final file name  will be: <strong>' . date("Y") . '-' . date("m") . '-' . date("d") . '-example.pdf</strong></div></td>

  </tr>

      <tr>

    <td width="300"><strong>Thank you message</strong><br><em>This is the thank you text the user sees after they upload.</em></td>

    <td><input type="text" name="sp_cu_thankyou"  value="' . get_option('sp_cu_thankyou') . '"  size=80"> </td>

  </tr>

       <tr>

    <td width="300"><strong>Delete Message</strong><br><em>The confirmation screen asking the user if they want to delete the file.</em></td>

    <td><input type="text" name="sp_cu_delete"  value="' . get_option('sp_cu_delete') . '"  size=80"> </td>

  </tr>



      <tr>

    <td width="300"><strong>Disable User Uploads?</strong><br><em>Check this box to disable user uploads.</em></td>

    <td><input type="checkbox" name="sp_cu_user_uploads_disable"   value="1" ' . $sp_cu_user_uploads_disable . '> </td>

  </tr>

     <tr>

	   <tr>

    <td width="300"><strong>Disable User Deleting?</strong><br><em>Check this box to not allow user to delete file.</em></td>

    <td><input type="checkbox" name="sp_cu_user_delete_disable"   value="1" ' . $sp_cu_user_delete_disable . '> </td>

  </tr>

    
    <tr>

    <td width="300"><strong>Folders Name</strong><br><em>We call folders what they are "Folders", if you want to call them something else specify that here. Please give both the singular and plural word for the replacement.</em></td>

    <td>Singular: <input type="text" name="sp_cu_folder_name_single"   value="'.stripslashes(get_option('sp_cu_folder_name_single')).'"> Plural:  <input type="text" name="sp_cu_folder_name_plural"   value="'.stripslashes(get_option('sp_cu_folder_name_plural')).'"></td>

  </tr>
    <tr>

    <td width="300"><strong>Hide project if empty?</strong><br><em>Hide a project if there are no files on it.</em></td>

    <td><input type="checkbox" name="sp_cu_hide_project"   value="1" ' . $sp_cu_hide_project . '> </td>

  </tr>

    <tr>

    <td width="300"><strong>Allow users to create projects?</strong><br><em>If you want to allow the user to create projects check this box.</em></td>

    <td><input type="checkbox" name="sp_cu_user_projects"   value="1" ' . $sp_cu_user_projects . '> </td>

  </tr>
    <tr>

    <td width="300"><strong>Do not allow user to delete or edit projects</strong><br><em>Check this box if you do not want the users to edit or delete projects.</em></td>

    <td><input type="checkbox" name="sp_cu_user_projects_modify"   value="1" ' . $sp_cu_user_projects_modify . '> </td>

  </tr>


    <tr>

    <td width="300"><strong>Form Instructions</strong><br><em>Just a short statement that will go above the upload form, you can use html!</em></td>

    <td><textarea  name="sp_cu_form_instructions"  style="width:100%;height:60px" >' . stripslashes(get_option('sp_cu_form_instructions')) . '</textarea> </td>

  </tr>

  



   

  

  

  ';
        if (class_exists('cdmProductivityGoogle')) {
            echo '   <tr>

    <td width="300"><strong>Google API Key</strong><br><em>This is your google API if you are using the google shortlink addon in the productivity suite, this also may be used for future google services integration.</em></td>

    <td><input type="text" name="sp_cu_google_api_key"  value="' . get_option('sp_cu_google_api_key') . '"  size=80"> </td>

  </tr>';
        }
        echo '

    <tr>

    <td>&nbsp;</td>

    <td><input type="submit" name="save_options" value="Save Options"></td>

  </tr>

</table>

</div>


  <div id="cdm-tab-mail">


<h2>Admin Email</h2>
 <table class="wp-list-table widefat fixed posts" cellspacing="0">

 

   <tr>

    <td width="300"><strong></strong><br><em>If you have additional people that need to get a copy of the admin when a user uploads a file then list them here seperated by a comma. You can also specify a wordpress role that would receive the email, so for instance if you have a custom role called "Customer Service" the email would be sent to everyone in the "Customer Service" Role. Roles should be lower case.</em></td>

    <td><input style="width:100%" type="text" name="sp_cu_additional_admin_emails" value="' . stripslashes(get_option('sp_cu_additional_admin_emails')) . '" ></td>

  </tr>

     <tr>

    <td width="300"><strong>Admin Email</strong><br><em>This is the email that is dispatched to admin.</em><br><br>Template Tags:<br><br>

	

	[file] = Link to File<br>
	
	[file_name] = Actual File Name<br>
	
	[file_real_path] = Real Path URL to the file<br>
	[file_in_document_area] = Link to the file in document area<br>
	

	[notes] = Notes or extra fields<br>

	[user] = users name<br>
	
	[uid] = User ID<br>

	[project] = project<br>

	[category] = category<br>

	[user_profile] = Link to user profile<br>

	[client_documents] = Link to the client document manager

	</td>

    <td>Subject: <input style="width:100%" type="text" name="sp_cu_admin_email_subject" value="' . get_option('sp_cu_admin_email_subject') . '"><br>Body:<br>
		';
	echo wp_editor(  stripslashes(get_option('sp_cu_admin_email') ), 'sp_cu_admin_email' );
	echo '
	
 </td>

  </tr></table>
  
  
  
<h2>User Email</h2>
<table class="wp-list-table widefat fixed posts" cellspacing="0">
      <tr>

    <td width="300"><strong>Additional User Emails</strong><br><em>If you have additional people that need to get a copy of the email when a user uploads a file then list them here seperated by a comma.  You can also specify a wordpress role that would receive the email, so for instance if you have a custom role called "Customer Service" the email would be sent to everyone in the "Customer Service" Role. Roles should be lower case.</em></td>

    <td><input style="width:100%" type="text" name="sp_cu_additional_user_emails" value="' . stripslashes(get_option('sp_cu_additional_user_emails')) . '" ></td>

  </tr>

    <tr>

    <td width="300"><strong>User Email</strong><br><em>This is the email that is dispatched to user.</em><br><br>Template Tags:<br><br>

	

	[file] = Link to File<br>
	[file_name] = Actual File Name<br>
	
	[file_real_path] = Real Path URL to the file<br>
	[file_in_document_area] = Link to the file in document area<br>
	[notes] = Notes or extra fields<br>

	[user] = users name<br>
	
	[uid] = User ID<br>
	[project] = project<br>

	[category] = category<br>

	[user_profile] = Link to user profile<br>

	[client_documents] = Link to the client document manager</td>

    <td>Subject: <input style="width:100%" type="text" name="sp_cu_user_email_subject" value="' . get_option('sp_cu_user_email_subject') . '"><br>Body:<br>
	';
	echo wp_editor(  stripslashes(get_option('sp_cu_user_email')) , 'sp_cu_user_email' );
	echo ' </td>

  </tr></table>';
  
 
  
  echo '
  
  
  
<h2>Admin Upload to User Email</h2>
<table class="wp-list-table widefat fixed posts" cellspacing="0">



    <tr>

    <td width="300"><strong>Admin to user email</strong><br><em>This email is dispatched when an admin adds a file in the administration area to a user.</em><br><br>Template Tags:<br><br>

	

	[file] = Link to File<br>
	[file_in_document_area] = Link to the file in document area<br>
	[notes] = Notes or extra fields<br>

	[user] = users name<br>

	[project] = project<br>

	[category] = category<br>

	[user_profile] = Link to user profile<br>

	[client_documents] = Link to the client document manager</td>

    <td>Subject: <input style="width:100%" type="text" name="sp_cu_admin_user_email_subject" value="' . get_option('sp_cu_admin_user_email_subject') . '"><br>Body:<br>
		';
	echo wp_editor(  stripslashes(get_option('sp_cu_admin_user_email')) , 'sp_cu_admin_user_email' );
	echo '
	
	 </td>

  </tr>

 </table>

<h2>Vendor Email</h2>
<table class="wp-list-table widefat fixed posts" cellspacing="0">



    <tr>

    <td width="300"><strong>Vendor Email</strong><br><em>This email is dispatched when a user sends a file to a vendor.</em><br><br>Template Tags:<br><br>

	

	[file] = Link to File<br>

	[notes] = Notes<br></td>

    <td>Subject: <input style="width:100%" type="text" name="sp_cu_vendor_email_subject" value="' . get_option('sp_cu_vendor_email_subject') . '"><br>
	CC: <input style="width:100%" type="text" name="sp_cu_vendor_email_cc" value="' . get_option('sp_cu_vendor_email_cc') . '"><br>
	Body:<br>
	';
	echo wp_editor(  stripslashes(get_option('sp_cu_vendor_email')) , 'sp_cu_vendor_email' );
	echo '
	
	 </td>

  </tr>

 </table>';
 
  do_action('sp_cdm_settings_email');
 echo '
 <table class="wp-list-table widefat fixed posts" cellspacing="0">

<tr>

    <td width="300">&nbsp;</td>

    <td><input type="submit" name="save_options" value="Save Email Settings" style="padding:10px;font-size:1.5em"></td>

  </tr>
  </table>
</div>

  <div id="cdm-tab-advanced">





 <table class="wp-list-table widefat fixed posts" cellspacing="0">

 <tr>

    <td width="300"><strong>Alternate Uploads Folder</strong><br><em>If you would to store your uploads in another folder please enter the full path to the uploads with a trailing slash!. Please update the URL as well. Could be absolute or relative, if you fail to update the URL then your files will not be accessible. If you are using a path that is not web accessible then do not bother putting in the path URL. The script will strictly use fread() to serve the file and will not offer up the full URL. This is a complete secure solution so nobody can access your files. Also be sure to enable "Require login to download" if you want to stop remote linking to your files. Also remember thumbnails will not work wh<br><br> 

	This feature will not move your uploads folder, If you need to change your uploads folder and you already have existing files you must move the folder from its default path in /wp-content/uploads/.

	

	</td>';
        if (get_option('sp_cu_overide_upload_path') != "" && !is_dir(get_option('sp_cu_overide_upload_path'))) {
            $does_not_exist = '<span style="color:red">Uploads Directory does not exist, please remove the custom upload path or create the folder!';
        }
        echo '

    <td><span style="width:120px">System Path:</span> <input type="text" name="sp_cu_overide_upload_path"  value="' .stripslashes( get_option('sp_cu_overide_upload_path')) . '"  size=80"><br>

	<em><strong>Example: </strong><br>linux: /home/mysite/public_html/uploads/ <br>windows: C:\websites\mysite\uploads\</em><br><br><br>

	   <span style="width:120px"> Direct URL:</span> <input type="text" name="sp_cu_overide_upload_url"  value="' .stripslashes( get_option('sp_cu_overide_upload_url')) . '"  size=80"><br>

	   	<em><strong>Example:</strong><br> http://mywebsites/uploads/</em>

	   

	    </td>

  </tr> 

  

  

    <tr>

    <td width="300"><strong>Require Login to Download?</strong><br><em>Check this option to require the user to login to download a file, this can only be used securely if you are not using the javascript downloads</em></td>

    <td><input type="checkbox" name="sp_cu_user_require_login_download"   value="1" ' . $sp_cu_user_require_login_download . '> </td>

  </tr>
    <tr>

    <td width="300"><strong>Disable Searching?</strong><br><em>Checking this will disable the search box on the front end.</em></td>

    <td><input type="checkbox" name="sp_cu_user_disable_search"   value="1" ' . $sp_cu_user_disable_search . '> </td>

  </tr>
  <tr>

  

    <td width="300"><strong>Javascript Redirect?</strong><br><em>If your on a windows system you need to use javascript redirection as FastCGI does not allow force download files.</em></td>

    <td><input type="checkbox" name="sp_cu_js_redirect"   value="1" ' . $sp_cu_js_redirect . '> </td>

  </tr>

      <tr>

    <td width="300"><strong>Mandatory '.sp_cdm_folder_name(1).'?</strong><br><em>If you want to require that a user select a project then check this box.</em></td>

    <td><input type="checkbox" name="sp_cu_user_projects_required"   value="1" ' . $sp_cu_user_projects_required . '> </td>

  </tr>   <tr>

    <td width="300"><strong>WP Folder</strong><br><em>Use this option only if your wp installation is in a sub folder of your url. For instance if your site is www.example.com/blog/ then put /blog/ in the field. This helps find the uploads directory.</em></td>

    <td><input type="text" name="sp_cu_wp_folder"  value="' . stripslashes(get_option('sp_cu_wp_folder')) . '"  size=80"> </td>

  </tr>  <tr>

  

 

    <tr>

    <td>&nbsp;</td>

    <td><input type="submit" name="save_options" value="Save Options"></td>

  </tr></table>
  </div>';
  
   if (@CU_PREMIUM == 1) {
 echo ' <div id="cdm-tab-premium">  ';
        do_action('cdm_premium_settings');
		
		echo '</div>';
		
   }
   
        echo '</div>





</form>

	

	';
        echo $content;
    }
    if (!function_exists('sp_client_upload_help')) {
        function sp_client_upload_help()
        {
            echo '' . sp_client_upload_nav_menu() . '


 <table class="wp-list-table widefat fixed posts" cellspacing="0">

 <tr>
<td>
	<h1>Smarty Pants Client Document Manager</h1>
<ol>
  <li>Upload the plugin to your plugins folder</li>
  <li>Activate the plugin</li>
  <li>Create a new page and enter the shortcode [sp-client-document-manager]</li>
  <li>Go to the plugin admin page and click settings to configure the plugin (VERY IMPORTANT!)</li>
  <li>If you\'re using the premium version please upload the zip archive in the settings area.</li>
</ol>
<h2>Short Codes</h2>
<p>x = configurable area</p>
<h3>Main shortcode</h3>
<div class="updated"><strong>[sp-client-document-manager]</strong></div>
<h3>Link to a file</h3>
<div class="updated"><strong>[cdm-link file="x" date="1" real="1"]</strong></div>
<ul>
  <li>file = required, this is the file id. You can find the file id in admin under files or by clicking on a file. The ID is listed next to the date.</li>
  <li>date = (set to 1) optional, show the date of a file</li>
  <li>real = (set to 1) optional, generate the real url for the file, the link tags are not generated and only the url is returned. This is good for custom links and image url\'s</li>
</ul>
<h4>examples</h4>
<ul>
  <li>[cdm-link file="53" date="1"]</li>
  <li>Will generate a link with the file name and date</li>
</ul>
<p>\'&lt; img src="[cdm-link file="53" real="1"]" width="100"&gt;</p>
<p>Will generate a full url for use in an image</p>
<h3>Link to a project</h3>
<div class="updated"><strong>[cdm-project project="x" date="1" order="x" direction="x" limit="x" ]</strong></div>
<p>This shortlink will display a unordered list of files, it is a basic html ul so you can use css to display it however you want.</p>
<ul>
  <li>project = required, this is the project id which you can get in admin under the projects tab.</li>
  <li>date = optional, put\'s the date of the file next to the file name</li>
  <li>order = (name,date,id,file) optional, use one of the fields to order the list by</li>
  <li>direction = (asc,desc) optional, Only to be used with order, use asc for ascending order or desc for decending order</li>
  <li>limit = optional, use to limit the amount of results shown.</li>
</ul>
<h4>examples</h4>
<ul>
  <li>[cdm-project project="1" date="1" ]</li>
  <li>[cdm-project project="1" date="1" order="name" direction="asc" limit="10" ]</li>
  </ul>
<h3>Public view (premium only)</h3>
<div class="updated"><strong>[cdm_public_view]</strong></div>
</h3>
<p>This is a shortcode for premium members only, it displays the file list to the public. This shortcode lists all the files from all users.
<h2>User Role Capabilities</h2>
<p>If you use "User Role Editor" plugin and want to assign CDM capabilities to another role then please use the following custom captabilities. All are automatically set for administrator</p>
<ul>
  <li>sp_cdm = You need this role to view the plugin, this is a very minimal role. You can view files, edit and delete.</li>
  <li>sp_cdm_settings = Show Settings tab</li>
  <li>sp_cdm_vendors = Show vendors tab</li>
  <li>sp_cdm_projects = Show projects tab</li>
  <li>sp_cdm_uploader = Use the uploader (add files) </li>
  <li>sp_cdm_categories = Show the categories tab (premium only)</li>
  <li>sp_cdm_forms = Show the forms tab (premium only)</li>
  <li>sp_cdm_help = Show the help tab and display branding (premium only)</li>
  <li>sp_cdm_logs = Show the user logs (premium only)</li>
  <li>sp_cdm_show_folders_as_nav = Show the folders as its own nav (premium only)</li>
  <li>sp_cdm_top_menu = Show or hide the top menu (premium only)</li>
  <li>sp_cdm_uploader = Show the uploader tab (premium only)</li>
</ul>
<p><strong></p>
<h3>Premium Users</h3>
<p>*Premium users must have free + premium version installed. The premium extends the free version.</p>
</td></tr></table>
';
        }
    }
    if (!function_exists('sp_client_upload_nav_menu')) {
        function sp_client_upload_nav_menu($nav = NULL)
        {
			global $wpdb,$current_user;
			$content ='';
            global $cu_ver, $sp_client_upload, $sp_cdm_ver;
    
	 if (current_user_can('sp_cdm_top_menu')) {
	        $content .= '

	<script type="text/javascript">

    jQuery(document).ready(function(){

        jQuery("#menu1").ptMenu();

    });

</script>

	

	<ul id="menu1" style="margin-top:20px;margin-bottom:10px;">';
            if (current_user_can('sp_cdm')) {
                $content .= '<li><a href="admin.php?page=sp-client-document-manager" >Home</a></li>';
            }
            if (current_user_can('sp_cdm_settings')) {
                $content .= '<li><a href="admin.php?page=sp-client-document-manager-settings" >' . __("Settings", "sp-cdm") . '</a><ul>';
				    $content .= '<li><a href="admin.php?page=sp-client-document-manager-settings" >' . __("Global Settings", "sp-cdm") . '</a></li>';
                if (current_user_can('sp_cdm_vendors')) {
                    $content .= '<li><a href="admin.php?page=sp-client-document-manager-vendors" >' . __("Vendors", "sp-cdm") . '</a></li>';
                }
                if (current_user_can('sp_cdm_projects')) {
                    $content .= '<li><a href="admin.php?page=sp-client-document-manager-projects" >' .sp_cdm_folder_name(1) . '</a></li>';
                }
					
                if (@CU_PREMIUM == 1) {
                    if (current_user_can('sp_cdm_groups')) {
                        $content .= '<li><a href="admin.php?page=sp-client-document-manager-groups" >' . __("Share Spaces", "sp-cdm") . '</a></li>';
					}
					 if (current_user_can('sp_cdm_forms')) {
                        $content .= '<li><a href="admin.php?page=sp-client-document-manager-forms">' . __("Forms", "sp-cdm") . '</a></li>';
					 }
					  if (current_user_can('sp_cdm_categories')) {
                        $content .= '<li><a href="admin.php?page=sp-client-document-manager-categories" >' . __("Categories", "sp-cdm") . '</a></li>';
                    }
                }
                $extra_menus = '';
                $extra_menus .= apply_filters('sp_client_upload_nav_menu', $extra_menus);
                $content .= '' . $extra_menus . '</ul></li>';
            }
            if (current_user_can('sp_cdm_uploader')) {
                $content .= '<li><a href="admin.php?page=sp-client-document-manager-fileview" >' . __("User Files / Uploader", "sp-cdm") . '</a></li>

			';
            }
			 if (current_user_can('sp_cdm_help')) {
            $content .= '	
	<li><a href="admin.php?page=sp-client-document-manager-help" >' . __("Instructions", "sp-cdm") . '</a></li>';
			 }
	
	 $extra_top_menus = '';
       $extra_top_menus .= apply_filters('sp_client_upload_top_menu',  $extra_top_menus);
	$content .=''. $extra_top_menus.'

	</ul>';
	
	 }
			 if (current_user_can('sp_cdm_help')) {
            if (@CU_PREMIUM == 1) {
                $ver = $sp_cdm_ver;
            } else {
                $ver = $sp_client_upload;
            }
            $content .= '<div style="text-align:right"><strong style="margin-right:10px">Version:</strong> ' . get_option('sp_client_upload') . '';
            if (@CU_PREMIUM == 1) {
                $content .= ' <strong style="margin-left:50px;margin-right:10px;">Premium Version:</strong> ' . get_option('sp_client_upload_premium') . '';
            
			 }
            $content .= '</div>';
			}
            if (@$_GET['sphidemessage'] == 1) {
                $content .= '		

			<script type="text/javascript">

				jQuery(document).ready( function() {

				 sp_cu_dialog("#sp_cdm_ignore",400,200);

			 

				});

			</script>



			<div style="display:none">

			

			<div id="sp_cdm_ignore">

			<h2>It\'s OK!</h2>

			<p>Hey no hard feelings, we hate nag messages too! If you change your mind and want to give us some love checkout the settings page for a link to the our website!</p>

			</div>

		    </div>';
                update_option("sp_cdm_ignore", 1);
            }
            if (@$_GET['sphidemessage'] == '2') {
                update_option("sp_cdm_ignore", 0);
            }
            if (@CU_PREMIUM != 1 && get_option("sp_cdm_ignore") != 1) {
                $content .= '	

	<div class="updated">

	<p><strong>Upgrade to the premium version today to get enhanced features and support. Features include: File versioning system, Categories for files, Thumbnails for files, auto generation of thumbnails from PDF and PSD, Additional fields form builder, Support and many more enhanced settings!</strong> <br />

<br />

<a href="http://smartypantsplugins.com/sp-client-document-manager/" target="_blank" class="button">Click here to upgrade! </a> <a style="margin-left:10px" href="http://www.youtube.com/watch?feature=player_embedded&v=m6szdA3r-1Q" target="_blank" class="button">View the youtube video</a> <a style="margin-left:10px" href="http://smartypantsplugins.com/donate/" target="_blank" class="button">Click here to donate</a> <a href="admin.php?page=sp-client-document-manager&sphidemessage=1"  class="button" style="margin-left:10px">Click here to ignore us!</a></p>

	</div>';
            }
			
			
			 $r = $wpdb->get_results("SELECT * FROM  " . $wpdb->prefix . "posts where post_content LIKE   '%[sp-client-document-manager]%' and post_type = 'page'", ARRAY_A);
							
	if (@$_GET['ignore'] == 'shortcode') {
                add_option('cdm_ignore_shortcode', 1);
            }
			
		if($r[0]['ID'] == ""  && get_option('cdm_ignore_shortcode') != 1){
                $content .= '<div class="sp_cdm_error" style="margin-bottom:20px">It looks like you do not have a page with the shortcode <strong>[sp-client-document-manager]</strong> on it. Please create one or use the form below and we will create one for you!';
				
				
				if($_POST['page-name-cdm'] != ''){
				
				// Create post object
			$my_post = array(
			  'post_title'    => $_POST['page-name-cdm'],
			  'post_content'  => '[sp-client-document-manager]',
			  'post_type'   => 'page',
			  'post_status'   => 'publish',
			  'post_author'   => $current_user->ID,
			 
			);

	
			$post = wp_insert_post( $my_post );
			$content .= '<div style="margin:10px;font-size:1.3em" class="sp_cdm_success"><strong>'.$_POST['page-name-cdm'].'</strong> Page Created! <a href="'.get_page_link($post).'" target="_blank">Click here to preview the page</a></div>';	
				}else{
				$content .='<form action="admin.php?page=sp-client-document-manager" method="post">
				Page Name: <input type="text" name="page-name-cdm" value=""> <input type="submit" name="add-shortcode-page" value="Add">
				</form>
				<div style="text-align:right">
				<a href="admin.php?page=sp-client-document-manager-settings&ignore=shortcode" class="button">click here to ignore this message</a>
				</div>
				';
					
				}
				$content .='</div>';
            }
			
            if (@$_GET['ignore'] == 'tml') {
                add_option('cdm_ignore_tml', 1);
            }
            if (!function_exists('theme_my_login') && get_option('cdm_ignore_tml') != 1) {
                $content .= '<div class="sp_cdm_error">This plugin works great with the "Theme My Login" plugin which allows you to use your own template for login and registration. <strong>Please remember to turn on registration in your wordpress settings if you need to have users registering</strong>.<div style="padding:10px"> <a href="plugin-install.php?tab=search&s=theme+my+login&plugin-search-input=Search+Plugins" class="button">Click here to get theme my login.</a>	<div style="text-align:right"><a href="admin.php?page=sp-client-document-manager-settings&ignore=tml" class="button">click here to ignore this message</a></div></div></div>';
            }
            echo $content;
            do_action('sp_cdm_errors');
        }
        add_action('cdm_nav_menu', 'sp_client_upload_nav_menu');
    }
    if (!function_exists('sp_client_upload_admin')) {
        function sp_client_upload_admin()
        {
            global $wpdb;
			$html = '';
            $user_id = @$_REQUEST['user_id'];
            if (@$_GET['dlg-delete-file'] != "") {
                $r = $wpdb->get_results("SELECT *  FROM " . $wpdb->prefix . "sp_cu   where  id = " . $_GET['dlg-delete-file'] . "", ARRAY_A);
                @unlink('' . SP_CDM_UPLOADS_DIR . '' . $r[0]['uid']. '/' . $r[0]['file'] . '');
                $wpdb->query("

	DELETE FROM " . $wpdb->prefix . "sp_cu WHERE id = " . $_GET['dlg-delete-file'] . "

	");
            }
            if ($user_id != "") {
                echo '<h2>' . __("User Uploads", "sp-cdm") . '</h2><a name="downloads"></a>';
                $r             = $wpdb->get_results("SELECT *  FROM " . $wpdb->prefix . "sp_cu   where uid = $user_id  and parent = 0 order by date desc", ARRAY_A);
                $delete_page   = 'user-edit.php?user_id=' . $user_id . '';
                $download_user = '<a href="' . SP_CDM_PLUGIN_URL . 'ajax.php?function=download-archive&id=' . $user_id . '" class="button">' . __("Click to download all files", "sp-cdm") . '</a>';
            } else {
                $r = $wpdb->get_results("SELECT *  FROM " . $wpdb->prefix . "sp_cu   where  parent = 0 order by id desc LIMIT 150", ARRAY_A);
                $html .= '<form id="your-profile">';
                $delete_page   = 'admin.php?page=sp-client-document-manager';
                $download_user = '';
            }
            if ($r == FALSE) {
                $html .= '<p style="color:red">' . __("No Uploads Exist!", "sp-cdm") . '</p>';
            } else {
                //show uploaded documents
                $html .= '

<script type="text/javascript">



function sp_client_upload_email_vendor(){

	



    	jQuery.ajax({

			 

		  type: "POST",

		  url:  "' . SP_CDM_PLUGIN_URL . 'ajax.php?function=email-vendor" ,

		 

		 data:  jQuery("#your-profile" ).serialize(),

		  success: function(msg){

   								jQuery("#updateme").empty();

								jQuery("#updateme").append( msg);

								

							  }

 		});	

	

	return false;

}



function sp_cdm_showFile(file){

			

		  var url = "' . SP_CDM_PLUGIN_URL . 'ajax.php?function=view-file&id=" + file;

		  

		 

            // show a spinner or something via css

            var dialog = jQuery(\'<div style="display:none" class="loading viewFileDialog"></div>\').appendTo(\'body\');

          

		  



     var fileArray = new Array();      

	 var obj_file_info =   jQuery.getJSON("' . SP_CDM_PLUGIN_URL . 'ajax.php?function=get-file-info&type=name&id=" + file, function(data) {

   



	

		

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

</script>

' . $download_user . '

  <table class="wp-list-table widefat fixed posts" cellspacing="0">

	<thead>

	<tr>

	<th style="width:30px">' . __("ID", "sp-cdm") . '</th>	

<th style="width:80px">' . __("Thumbnail", "sp-cdm") . '</th>	

<th>' . __("File Name", "sp-cdm") . '</th>

<th>' . __("User", "sp-cdm") . '</th>

<th>' . __("Date", "sp-cdm") . '</th>

<th>' . __("Download", "sp-cdm") . '</th>

<th>' . __("Email", "sp-cdm") . '</th>

</tr>

	</thead>





';
                for ($i = 0; $i < count($r); $i++) {
                    if ($r[$i]['name'] == "") {
                        $name = $r[$i]['file'];
                    } else {
                        $name = $r[$i]['name'];
                    }
                    $r_user = $wpdb->get_results("SELECT *  FROM " . $wpdb->prefix . "users where ID = " . $r[$i]['uid'] . "", ARRAY_A);
                    if (get_option('sp_cu_js_redirect') == 1) {
                        $target = 'target="_blank"';
                    } else {
                        $target = ' ';
                    }
                    $ext        = preg_replace('/^.*\./', '', $r[$i]['file']);
                    $images_arr = array(
                        "jpg",
                        "png",
                        "jpeg",
                        "gif",
                        "bmp"
                    );
                    if (in_array(strtolower($ext), $images_arr)) {
                        if (get_option('sp_cu_overide_upload_path') != '' && get_option('sp_cu_overide_upload_url') == '') {
                            $img = '<img src="' . SP_CDM_PLUGIN_URL . 'images/package_labled.png">';
                        } else {
                            $img = '<img src="' . sp_cdm_thumbnail('' . SP_CDM_UPLOADS_DIR_URL . '' . $r[$i]['uid'] . '/' . $r[$i]['file'] . '', 80, 80) . '">';
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
                    } elseif (($ext == 'pdf' or $ext == 'psd' or $ext == 'html' or $ext == 'eps') && get_option('sp_cu_user_projects_thumbs_pdf') == 1) {
                        if (file_exists('' . SP_CDM_UPLOADS_DIR . '' . $r[$i]['uid'] . '/' . $r[$i]['file'] . '_small.png')) {
                            $img = '<img src="' . SP_CDM_UPLOADS_DIR_URL . '' . $r[$i]['uid'] . '/' . $r[$i]['file'] . '_small.png">';
                        } else {
                            $img = '<img src="' . SP_CDM_PLUGIN_URL . 'images/adobe.png">';
                        }
                    } elseif ($ext == 'pdf') {
                        $img = '<img src="' . SP_CDM_PLUGIN_URL . 'images/adobe.png">';
                    } else {
                        $img = '<img src="' . SP_CDM_PLUGIN_URL . 'images/package_labled.png">';
                    }
					$img = apply_filters('sp_cdm_viewfile_image', $img,$r[$i]);
                    $html .= '

	

 <tr>

 <td>' . $r[$i]['id'] . '</td>

 <td>' . $img . '</td>

    <td ><strong>' . stripslashes($name) . '</strong>';
                    if (@CU_PREMIUM == 1) {
                        $html .= sp_cdm_get_form_fields($r[$i]['id']);
                    } else {
                        $html .= '<br><em>' . __("Notes: ", "sp-cdm") . ' ' . stripslashes($r[$i]['notes']) . '</em>';
                    }
                    if ($r[$i]['tags'] != "") {
                        $html .= '<br><strong>' . __("Tags ", "sp-cdm") . '</strong><em>: ' . $r[$i]['tags'] . '</em>';
                    }
                    $html .= '

	

	

	</td>

	<td><a href="user-edit.php?user_id=' . $r[$i]['uid'] . '">' . $r_user[0]['display_name'] . '</a></td>

	 <td >' . date('F jS Y h:i A', strtotime($r[$i]['date'])) . '</td>

   

    <td><a style="margin-right:15px" href="javascript:sp_cdm_showFile(' . $r[$i]['id'] . ')" >' . __("View", "sp-cdm") . '</a> <a href="' . $delete_page . '&dlg-delete-file=' . $r[$i]['id'] . '#downloads">' . __("Delete", "sp-cdm") . '</a> </td>

<td><input type="checkbox" name="vendor_email[]" value="' . $r[$i]['id'] . '"></td>	</tr>





  

  ';
                }
                $html .= '</table>

			

				<div style="text-align:right">

			<div id="updateme"></div>

				' . __("Choose  the files you want to send above, type a message and choose a vendor then click submit:", "sp-cdm") . '  <select name="vendor">

				';
                if ($_POST['submit-vendor'] != "") {
                    //	print_r($_POST);
                }
                $vendors = $wpdb->get_results("SELECT *  FROM " . $wpdb->prefix . "options   where option_name  LIKE 'sp_client_upload_vendors%'  order by option_name", ARRAY_A);
                for ($i = 0; $i < count($vendors); $i++) {
                    $vendor_info[$i] = unserialize($vendors[$i]['option_value']);
                    $html .= '<option value="' . $vendor_info[$i]['email'] . '">' . $vendor_info[$i]['name'] . '</option>';
                }
                $html .= '</select> ' . __("Message:", "sp-cdm") . ' <input type="text" name="vendor-message"> <select name="vendor_attach"><option value="1">' . __("Attach to email:", "sp-cdm") . ' </option><option value="0">' . __("Send links to files", "sp-cdm") . ' </option><option value="3">' . __("Attach and link to to files", "sp-cdm") . ' </option></select> <input type="submit" name="submit-vendor" value="' . __("Email vendor files!", "sp-cdm") . '" onclick="sp_client_upload_email_vendor();return false;"> 

				</div>

				';
            }
            if ($user_id != "") {
                echo $html;
            } else {
                $html .= '</form>';
                return $html;
            }
        }
    }
}
add_action('edit_user_profile', 'sp_client_upload_admin');
?>