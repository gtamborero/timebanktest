if(!(window.console && console.log)) {
  console = {
    log: function(){},
    debug: function(){},
    info: function(){},
    warn: function(){},
    error: function(){}
  };
}

function cdm_refresh_file_view(fid){
	jQuery(".view-file-content").empty();
	 jQuery(".view-file-content").load(jQuery('#sp_cu_ajax_url').val()  + "?function=view-file&id=" + fid);
}

function cdm_check_file_perms(pid){
			
		
		jQuery.ajax({

			   type: "POST",

			   url: jQuery('#sp_cu_ajax_url').val() + '?function=check-file-permissions&pid='+ pid ,			  

			   success: function(msg){
				  // alert(msg);
				if(msg == 1){
					jQuery('.hide_add_file_permission').show();	
					}else{
					jQuery('.hide_add_file_permission').hide();		
					}
			   }

			 });

				
	
}

function cdm_check_folder_perms(pid){
			
		
		jQuery.ajax({

			   type: "POST",

			   url: jQuery('#sp_cu_ajax_url').val() + '?function=check-folder-permissions&pid='+ pid ,			  

			   success: function(msg){
				  
				if(msg == 1){
					jQuery('.hide_add_folder_permission').show();	
					}else{
					jQuery('.hide_add_folder_permission').hide();		
					}
			   }

			 });

				
	
}


function sp_cu_reload_all_projects(context_folder_pid){
	

		jQuery.ajax({

			   type: "POST",

			   url: jQuery('#sp_cu_ajax_url').val() + '?function=reload-project-dropdown&pid='+ context_folder_pid,			  

			   success: function(msg){

					
				jQuery('.pid_select').html(msg);	
			
			   }

			 });

	
	
}

function sp_cu_confirm_delete(div,h,url){

		
if(jQuery(window).width()*0.9< 768){
	var width = jQuery(window).width()*0.9;	
	}else{
		var width = 320;
	}

	var NewDialog = jQuery('<div id="sp_cu_confirm_delete"> ' + div + '</div>');

	

	jQuery(  NewDialog ).dialog({

			width:width,

			height:'auto',

			modal: true,

			buttons: {

				"Yes": function() {

				

				jQuery.ajax({

			   type: "POST",

			   url: url,			  

			   success: function(msg){
			
				cdmCloseModal('file');
jQuery( NewDialog ).remove();

			
				 cdm_ajax_search();

				 

				 

			   }

			 });

				

				

				},

				Cancel: function() {

					jQuery( NewDialog ).remove();

				}

			}

		});

	

}







function sp_cu_confirm(div,h,url){

	
if(jQuery(window).width()*0.9< 768){
	var width = jQuery(window).width()*0.9;	
	}else{
		var width = 320;
	}
	jQuery(  div ).dialog({

			width:width,

			height:'auto',

			modal: true,

			buttons: {

				"Yes": function() {

					window.location = url;

				},

				Cancel: function() {

					jQuery( this ).dialog( "close" );

				}

			}

		});

	

}



function sp_cu_dialog(div,w,h){

	
	
	
	if(jQuery(window).width()*0.9< 768){
	var width = jQuery(window).width()*0.9;	
	}else{
		var width = w;
	}
	var dialogBox = jQuery(div);
     var usableDialog = dialogBox[0];
      //jQuery("div.ui-dialog").remove();
            
	
	
	jQuery(usableDialog).dialog({

			height:'auto',

			width:width

	});

}

/*
jQuery(document).ready(function() {
//  jQuery("#cdm_upload_table tr:first").css("display", "none");
jQuery("#cdm_og").attr("checked","checked");
   
setInterval(function(){cdmPremiumReValidate();},1000);

});
*/



jQuery(document).ready(function() {

jQuery( ".cdm_button" ).button();


jQuery.ajaxSetup({ cache: false });



//add another file input when one is selected

var max = 20;

var replaceMe = function(){

	var obj = jQuery(this);

	if(jQuery("#cdm_upload_fields input[type='file']").length > max)

	{

		alert('fail');

		obj.val("");

		return false;

	}

	jQuery(obj).css({'position':'absolute','left':'-9999px','display':'none'}).parent().prepend('<input type="file" name="'+obj.attr('name')+'"/>')

	jQuery('#upload_list').append('<div class="sp_upload_div"><span class="sp_upload_name">'+obj.val()+'</span><input type="button" value="cancel"/><div>');

	jQuery("#cdm_upload_fields input[type='file']").change(replaceMe);

	jQuery("#cdm_upload_fields input[type='button']").click(function(){

		jQuery(this).parent().remove();

		jQuery(obj).remove();

		return false;

		

		

	});

}

jQuery("#cdm_upload_fields input[type='file']").change(replaceMe);















        jQuery('a.su_ajax').click(function() {

            var url = this.href;

            // show a spinner or something via css

            var dialog = jQuery('<div style="display:none" class="loading"></div>').appendTo('body');

            // open the dialog

            dialog.dialog({

                // add a close listener to prevent adding multiple divs to the document

                close: function(event, ui) {

                    // remove div with all data and events

                    dialog.remove();

                },

                modal: true,

				title: jQuery(this).attr('title'),

				height:'auto',

				width:700

            });

            // load remote content

            dialog.load(

                url, 

                {}, // omit this param object to issue a GET request instead a POST request, otherwise you may provide post parameters within the object

                function (responseText, textStatus, XMLHttpRequest) {

                    // remove the loading class

                    dialog.removeClass('loading');

                }

            );

            //prevent the browser to follow the link

            return false;

        });



});













	

