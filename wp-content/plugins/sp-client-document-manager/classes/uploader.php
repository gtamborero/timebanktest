<?php

$cdm_uploader = new cdm_uploader;


add_action('wp_head', array($cdm_uploader,'css'));
add_action('init', array($cdm_uploader,'js'));
add_action('admin_menu',array($cdm_uploader,'css'));;
add_action('admin_init',  array($cdm_uploader,'js'));
add_action('sp_cdm_bottom_uploader_page',  array($cdm_uploader,'upload_dialog'));
add_action('admin_footer',  array($cdm_uploader,'admin_upload_dialog'));
class cdm_uploader{
	
	function __construct(){
		
	}
	
	
	
	function js(){
		
	wp_enqueue_script('jquery-remodal', SP_CDM_PLUGIN_URL.'js/jquery.remodal.js', array(
        'jquery'
    ));
	
	wp_enqueue_script('jquery-responsive-tabs', SP_CDM_PLUGIN_URL.'js/jquery.responsive.tabs.js', array(
        'jquery'
    ));
	
	
	}
	
	function css(){
	
	
	 wp_register_style('jquery-remodal', SP_CDM_PLUGIN_URL.'css/jquery.remodal.css');
	 wp_enqueue_style('jquery-remodal');
	 
	  wp_register_style('jquery-responsive-tabs', SP_CDM_PLUGIN_URL.'css/jquery.responsive.tabs.css');
	 wp_enqueue_style('jquery-responsive-tabs');
	}
	
	function links(){
		
	}
	function add_folder(){
		global $current_user;
		
		
		
		
		 $add_project = '<div class="remodal" data-remodal-id="folder">';

		if($_GET['page'] == 'sp-client-document-manager-fileview'){
			$add_project .='<input type="hidden" id="sub_category_uid" name="uid" value="' . $_GET['id'] . '">';
		}else{
		$add_project .='<input type="hidden" id="sub_category_uid" name="uid" value="' . $current_user->ID . '">';
		}
		if (@CU_PREMIUM == 1) {
			
			$add_project .='	<input type="hidden" class="cdm_premium_pid_field" name="parent" value="0">';
		}
		$add_project .='
	
		

		'.sp_cdm_folder_name() .' ' . __("Name", "sp-cdm") . ' <input  id="sub_category_name" type="text" name="project-name"  style="width:200px !important"> 

		<input type="submit" value="' . __("Add", "sp-cdm") . ' '.sp_cdm_folder_name() .'" name="add-project" onclick="sp_cu_add_project()">

	

	</div>';
        //$add_project = apply_filters('sp_cdm_add_project_form', $add_project);
		
		return $add_project;
	}
	function above_uploader(){
		
		echo '<script type="text/javascript">
			
			function cdmOpenModal(name){
				
			  var inst = jQuery.remodal.lookup[jQuery("[data-remodal-id=" + name + "]").data("remodal")];
				inst.open();	
				
			}
			function cdmCloseModal(name){
				
			  var inst = jQuery.remodal.lookup[jQuery("[data-remodal-id=" + name + "]").data("remodal")];
				inst.close();	
				
			}
			
			function cdmRefreshFile(file){
				
					jQuery(".view-file-content").empty();
				
				 var url = "'.SP_CDM_PLUGIN_URL.'ajax.php?function=view-file&id=" + file;
			  
			
				
				 jQuery.get(url, function (data) {
				 jQuery(".view-file-content").html(data);			
				
			
				 
				
				});			
				
			}
			function cdmViewFile(file){
				
				jQuery(".view-file-content").empty();
				
				 var url = "'.SP_CDM_PLUGIN_URL.'ajax.php?function=view-file&id=" + file;
			  jQuery(".cdm-modal").remodal({ hashTracking: false});	
			
				
				 jQuery.get(url, function (data) {
				 jQuery(".view-file-content").html(data);
						
				
				
				 var inst = jQuery.remodal.lookup[jQuery("[data-remodal-id=file]").data("remodal")];
				 inst.open();
				 
			
				 
				
				});				 		
		
		}
		
		


		function sp_cu_add_project(){
		
			
		
			jQuery.ajax({
		
		   type: "POST",
		
		   url: "' . SP_CDM_PLUGIN_URL . 'ajax.php?function=save-category",
		
		   data: "name=" + jQuery("#sub_category_name").val() + "&uid=" +  jQuery("#sub_category_uid").val()+ "&parent=" +  jQuery(".cdm_premium_pid_field").val(),
		
		   success: function(msg){
		
		  
		
		 cdmCloseModal("folder");
		cdm_ajax_search();
		
		
			
		
			jQuery(".pid_select").append(jQuery("<option>", { 
		
			value: msg, 
		
			text : jQuery("#sub_category_name").val(),
		
			selected : "selected"
		
			 }
		
			 ));
		
		  
		
		   }
		
		 });
		
		}


			</script>';
			do_action('cdm_above_uploader');
		
	}
	function admin_upload_dialog(){
		
			if($_GET['page'] == 'sp-client-document-manager-fileview'){
				
				$this->upload_dialog();
				
			}
		
	}
	function upload_dialog(){
		
		global $post;
		
	 
		echo  '<div style="display:none">
				';
		echo cdm_uploader::above_uploader();	
		echo  cdm_uploader::add_folder();
		echo '<div class="remodal" data-remodal-id="upload">	';
		
		echo display_sp_upload_form();
				
			
        echo '</div>';
			
		do_action('cdm_upload_dialog');	
		
				
		echo '
		<div class="cdm-modal" data-remodal-options="{ \'hashTracking\': false }" data-remodal-id="file">
			<div class="view-file-content">
			
			</div>
		</div>
				
		</div>';
		
	

	
		
		
	}
}