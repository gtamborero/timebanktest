<?php
if (!class_exists('cdmProjects')) {
    class cdmProjects
    {
        function add()
        {
            global $wpdb;
            echo '

<form action="admin.php?page=sp-client-document-manager-projects" method="post">';

if($_GET['parent'] != ''){
	$parent = $wpdb->get_results("SELECT  * FROM " . $wpdb->prefix . "sp_cu_project where id = '" . $_GET['parent'] . "'  ", ARRAY_A);
	echo '<input type="hidden" name="parent" value="'.$_GET['parent'].'">';

$selected = $parent[0]['uid'];
}else{
	
$selected = $r[0]['uid'];	
}
            if ($_GET['id'] != "") {
                $r = $wpdb->get_results("SELECT  * FROM " . $wpdb->prefix . "sp_cu_project where id = '" . $_GET['id'] . "'  ", ARRAY_A);
                echo '<input type="hidden" name="id" value="' . $r[0]['id'] . '">';
         $selected = $r[0]['uid'];
		 
		    } //$_GET['id'] != ""
            $users = $wpdb->get_results("SELECT * FROM " . $wpdb->base_prefix . "users order by display_name  ", ARRAY_A);
            echo '<h2>' . sp_cdm_folder_name(1) . '</h2>' . sp_client_upload_nav_menu() . '';
            echo '

	 <table class="wp-list-table widefat fixed posts" cellspacing="0">

  <tr>

    <th width="250">' . __("Name:", "sp-cdm") . '</th>

    <td><input type="text" name="project-name" value="' . stripslashes($r[0]['name']) . '"></td>

  </tr>

  <tr>

    <th>' . __("User:", "sp-cdm") . '</th>

    <td>';
            wp_dropdown_users(array(
                'name' => 'uid',
                'selected' => $selected,
				'show_option_none'  => __("Not Assigned","sp-cdm")
            ));
            echo '</td>

  </tr>';
  
   do_action('sp_cdm_edit_project_main_form', $r);
  echo '

  <tr>

    <td>&nbsp;</td>

    <td><input type="submit" name="save-project" value="' . __("Save", "sp-cdm") . '"></td>

  </tr>

</table>';
           
		   if($_GET['id'] != ''){
		    do_action('sp_cdm_edit_project_form', $_GET['id']);
		   }
            echo '

</form>





';
        }
		
		function getParentName($id){
			global $wpdb;	
			
		$r = $wpdb->get_results("SELECT *  FROM " . $wpdb->prefix . "sp_cu_project   where id = '" . $id . "'", ARRAY_A);	
		
		return $r[0]['name'];
		}
		function getChildren($id,$level = 0){
			
		global $wpdb;
		
		    
		
			$r = $wpdb->get_results("SELECT *  FROM " . $wpdb->prefix . "sp_cu_project   where parent = '" . $id . "' order by parent", ARRAY_A);
			
			if(count($r)>0){
				$level += 1;
				
				for ($x = 1; $x <= $level; $x++) {
				$spacer .= '<span style="margin-right:10px">&rarr; </div>';
				}
				
				for ($i = 0; $i < count($r ); $i++) {
					
					//start loop
					
			
                  $html .= '	<tr>
<td colspan="2">'.$spacer.'' . stripslashes($r[$i]['name']) . '</td>
				

<td>'.$spacer.'<em>Parent: '.$this->getParentName($r[$i]['parent']).'</em></td>



<td>

<a href="' . SP_CDM_PLUGIN_URL . 'ajax.php?function=download-project&id=' . $r[$i]['id'] . '" style="margin-right:15px" >' . __("Download Archive", "sp-cdm") . '</a> ';

if($r[$i]['parent'] == 0 or class_exists('spdm_sub_projects')){
 
 $html .='<a href="admin.php?page=sp-client-document-manager-projects&function=add&parent=' . $r[$i]['id'] . '" style="margin-right:15px" >' . __("Add Sub Folder", "sp-cdm") . '</a> '; 

}


 $html .='<a href="admin.php?page=sp-client-document-manager-projects&function=delete&id=' . $r[$i]['id'] . '" style="margin-right:15px" >' . __("Delete", "sp-cdm") . '</a> 

<a href="admin.php?page=sp-client-document-manager-projects&function=edit&id=' . $r[$i]['id'] . '" >' . __("Modify", "sp-cdm") . '</a></td>

</tr><tr><td colspan="4">'.$this->getChildren($r[$i]['id'],	$level ).'</td></tr>';
      
					
					//end loop
					
					
				}
			}
			
			
			return $html;
		}
        function view()
        {
            global $wpdb;
            if ($_POST['save-project'] != "") {
                $insert['name'] = $_POST['project-name'];
                $insert['uid']  = $_POST['uid'];
               if($_POST['parent'] != ''){
				  $insert['parent']  = $_POST['parent']; 
				   
			   }
			   
			   
			    if ($_POST['id'] != "") {
                    $where['id'] = $_POST['id'];
                    $wpdb->update("" . $wpdb->prefix . "sp_cu_project", $insert, $where);
                    $update['uid']        = $_POST['uid'];
                    $where_project['pid'] = $_POST['id'];
                    $wpdb->update("" . $wpdb->prefix . "sp_cu", $update, $where_project);
                    $insert_id = $_POST['id'];
					do_action('sp_cdm_edit_project_update', $insert_id);
                } else {
                    $wpdb->insert("" . $wpdb->prefix . "sp_cu_project", $insert);
                    $insert_id = $wpdb->insert_id;
					do_action('sp_cdm_edit_project_add', $insert_id);
                }
                do_action('sp_cdm_edit_project_save', $insert_id);
            } //$_POST['save-project'] != ""
            if ($_GET['function'] == 'add' or $_GET['function'] == 'edit') {
                $this->add();
            } //$_GET['function'] == 'add' or $_GET['function'] == 'edit'
            elseif ($_GET['function'] == 'delete') {
                $wpdb->query("DELETE FROM " . $wpdb->prefix . "sp_cu_project WHERE id = " . $_GET['id'] . "	");
              
				$r = $wpdb->get_results("SELECT *  FROM " . $wpdb->prefix . "sp_cu   where pid = '" . $_GET['id'] . "'", ARRAY_A);	
		$num = 0;
				
			
				 if(count($r)>0){
					 $last = count($r) - 1;
				 $array .= 'var myArray = [';
				 for ($i = 0; $i < count($r); $i++) {
					 
						if($i != $last){
						$comma = ',';	
						}else{
						$comma = '';	
						}
						$array .=''. $r[$i]['id'].''.$comma.' ';

						
				 }
				$array .= '];';
				 }else{
					
				echo '<script type="text/javascript">
				window.location = "admin.php?page=sp-client-document-manager-projects";
				</script>';	 
				exit;
					 
				 }
	
		
		
		
	
				echo '<script type="text/javascript">
				
		'.$array.'

jQuery.each(myArray, function(index, value){

		jQuery.ajax({
		
					   type: "POST",
		
					   url: "'.SP_CDM_PLUGIN_URL.'ajax.php?function=delete-file&dlg-delete-file="+ value ,			  
		
					   success: function(msg){
						  
					 if (index === '.count($r).' - 1) {
						window.location = "admin.php?page=sp-client-document-manager-projects"
					   }
						
					   }
		
		
		});
});

</script>';


            } //$_GET['function'] == 'delete'
            else {
                $r = $wpdb->get_results("SELECT " . $wpdb->prefix . "sp_cu_project.name as projectName,

									" . $wpdb->prefix . "sp_cu_project.uid,
									" . $wpdb->prefix . "sp_cu_project.parent,
									" . $wpdb->prefix . "sp_cu_project.id AS projectID,
									" . $wpdb->base_prefix . "users.ID,
									" . $wpdb->base_prefix . "users.user_nicename								
									
									FROM " . $wpdb->prefix . "sp_cu_project
									LEFT JOIN " . $wpdb->base_prefix . "users ON " . $wpdb->prefix . "sp_cu_project.uid = " . $wpdb->base_prefix . "users.ID
										
									 WHERE " . $wpdb->prefix . "sp_cu_project.parent = 0 
									 	
									 order by " . $wpdb->prefix . "sp_cu_project.name", ARRAY_A);
                echo '<h2>' . sp_cdm_folder_name(1) . '</h2>' . sp_client_upload_nav_menu() . '';
                echo '

								

									 

									 

									 <div style="margin:10px">

									 <a href="admin.php?page=sp-client-document-manager-projects&function=add" class="button">' . __("Add", "sp-cdm") . ' ' . sp_cdm_folder_name() . '</a>

									 </div>

									 <table class="wp-list-table widefat fixed posts" cellspacing="0">

	<thead>

	<tr>

<th style="width:40px"><strong>' . __("ID", "sp-cdm") . '</strong></th>

<th><strong>' . __("Name", "sp-cdm") . '</strong></th>

<th><strong>' . __("User", "sp-cdm") . '</strong></th>

<th><strong>' . __("Action", "sp-cdm") . '</strong></th>

</tr>

	</thead>';
                for ($i = 0; $i < count($r); $i++) {
                    $vendor_info[$i] = unserialize($vendors[$i]['option_value']);
                    echo '	<tr>

<td style="font-weight:bold;background-color:#EFEFEF">' . $r[$i]['projectID'] . '</td>				

<td style="font-weight:bold;background-color:#EFEFEF">' . stripslashes($r[$i]['projectName']) . '</td>

<td style="font-weight:bold;background-color:#EFEFEF">' . $r[$i]['user_nicename'] . '</td>

<td style="font-weight:bold;background-color:#EFEFEF">

<a href="' . SP_CDM_PLUGIN_URL . 'ajax.php?function=download-project&id=' . $r[$i]['projectID'] . '" style="margin-right:15px" >' . __("Download Archive", "sp-cdm") . '</a>  ';


if($r[$i]['parent'] == 0 or class_exists('spdm_sub_projects')){
 
 echo '<a href="admin.php?page=sp-client-document-manager-projects&function=add&parent=' . $r[$i]['projectID'] . '" style="margin-right:15px" >' . __("Add Sub Folder", "sp-cdm") . '</a> '; 

}




 echo '<a href="admin.php?page=sp-client-document-manager-projects&function=delete&id=' . $r[$i]['projectID'] . '" style="margin-right:15px" >' . __("Delete", "sp-cdm") . '</a> 

<a href="admin.php?page=sp-client-document-manager-projects&function=edit&id=' . $r[$i]['projectID'] . '" >' . __("Modify", "sp-cdm") . '</a></td>

</tr><tr><td colspan="4">'.$this->getChildren($r[$i]['projectID'] ).'</td></tr>';
                } //$i = 0; $i < count($r); $i++
                echo '</table>';
            }
        }
    }
} //!class_exists('cdmProjects')
?>