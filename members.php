<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Members extends Controller {

	public function __construct()
	{
		parent::Controller();
		$this->load->model('member_model');
		$this->custom_session->Set_Session();		
	}

	public function index() {
		$this->display();
	}	
	
	public function display($action = "", $actionid = "")
	{
		
		/* load view */				
		if ($this->session->userdata('loggedin') == true) {		
			switch($actionid) {
				case 'allergies':
						$data['current_tab']   = "data";
						$data['help_content']  = "allergies";
						$data['page_title']    = "Allergies";
						
						// Get Permission
						$permission = $this->member_model->check_permissions($this->session->userdata('userid'),'AllergiesNPPV');
						
						$perm = $permission['perm'];
						
						// Flexigrid Display Columns
						$colModel['AllergiesSID'] = array('ID',40,TRUE,'center',0,TRUE);
						$colModel['Medication'] = array('Medication',100,TRUE,'left',1,FALSE);
						$colModel['AllergicReaction'] = array('Allergic Reaction',200,TRUE,'left',1,FALSE);

						// Flexigrid Display Params
						$gridParams = array(
							'width' => 'auto',
							'height' => 'auto',
							'rp' => 15,
							'rpOptions' => '[10,15,20,25,40]',
							'pagestat' => 'Displaying: {from} to {to} of {total} items.',
							'blockOpacity' => 0.5,
							'title' => 'Allergies - (Password Protected - '.$perm.')',
							'showTableToggleBtn' => true,
							'nomsg'    => 'No Data Found',
							'procmsg'  => 'Processing...Please Wait',
							'errormsg' => 'Database Connection Error'
						); 						
						
						// Flexigrid Buttons
						$data['action_add_label']         = "Add";
						$data['action_edit_label']        = "Edit";
						$data['action_delete_label']      = "Delete";
						$data['action_update_label']      = "Update";
						$data['action_add_form_fields']   = "<tr class=\'trAdded\'><td><input type=\'text\' name=\'Medication\' style=\'width: 100px;\'/></td><td><input type=\'text\' name=\'AllergicReaction\' style=\'width: 200px;\'/></td></tr>";
						$data['action_save_label']        = "Save";
						$data['action_clear_label']       = "Cancel";
						$data['action_selectall_label']   = "Select All";
						$data['action_deselectall_label'] = "DeSelect All";						
 						$buttons[] = array($data['action_add_label'],'add','toolbar'); 	
 						$buttons[] = array($data['action_save_label'],'save','toolbar');
 						$buttons[] = array($data['action_edit_label'],'edit','toolbar'); 
 						$buttons[] = array($data['action_update_label'],'save','toolbar'); 						
 						$buttons[] = array($data['action_clear_label'],'clear','toolbar');
 						$buttons[] = array($data['action_delete_label'],'delete','toolbar');
						$buttons[] = array('separator');
						$buttons[] = array($data['action_selectall_label'],'add','toolbar');
						$buttons[] = array($data['action_deselectall_label'],'delete','toolbar');
						$buttons[] = array('separator'); 							
						
						
						// Parameterize Variable
						$grid_js = build_grid_js('flexigrid',$this->lang->line('site_ssl_url').'ajax/display/action/allergies',$colModel,'Medication','asc',$gridParams,$buttons); 
					
						// Variables to use in FlexigridLoader File
						$data['js_grid'] 	    = $grid_js;
						$data['js_save_url']    = "ajax/display/action/add/allergies";
						$data['js_edit_url']    = "ajax/display/action/edit/allergies";
						$data['js_delete_url']  = "ajax/display/action/delete/allergies";
						$data['side_menu'] 	    = "menu_dataentry";
						
						$this->load->view('utils/flexigridloader', $data);						
					break;						
				case 'data':
				case 'demographics':
						$data['current_tab']   = "data";
						$data['help_content']  = "demographics";
						$data['page_title']    = "Demographics";
						
						// Get Permission
						$general_permission = $this->member_model->check_permissions($this->session->userdata('userid'),'GeneralInfoNPPV');
						
						$data['gen_perm'] = $general_permission['perm'];
									
						$location_permission = $this->member_model->check_permissions($this->session->userdata('userid'),'LocationInfoNPPV');

						$data['loc_perm'] = $location_permission['perm'];	
						
						$contactinfo_permission = $this->member_model->check_permissions($this->session->userdata('userid'),'ContactInfoNPPV');
						
						$data['con_perm'] = $contactinfo_permission['perm'];
												
				  		$data['results'] = $this->member_model->get_all_data($this->session->userdata('userid'),'PatientMaster');	
						
						$data['side_menu'] 	   = "menu_dataentry";
						$data['form']		   = "demographics";
						$data['formname']      = "DATAENTRY1SUBMIT";
						$data['formid']        = "DATAENTRY1SUBMIT";
						$data['formmethod']    = "/members/process/demographics";
						
						$this->load->view('utils/formloader', $data);						
					break;		
				case 'documents':
						$data['current_tab']   = "data";
						$data['help_content']  = "doc";
						$data['page_title']    = "Documents";
						
						$data['side_menu'] 	   = "menu_dataentry";
						$data['form']		   = "file";
						$data['formid']	       = "doc";
						$data['formname']      = "doc";
					 	$data['formtype']	   = "doc";		
					 	$data['formcancel']	   = "/members/display/action/documents";
						$data['formapprovedtypes'] = "DOC, PDF, JPG, OR PNG";
						$data['formmethod']    = "/members/process/file";
						
					    // Get File Upload Record
						$record = $this->member_model->check_files($this->session->userdata('userid'),'doc');

						$numrows 		 = $record['numrows'];
						$data['caption'] = "";
						$data['type']    = $record['type'];
						$data['size']    = $record['size'];
						$data['fileid']  = $record['fileid'];
						$data['submitlabel'] = "Add Document";
						$data['submitname']  = "add";
						
						// Special variable for including File Upload Form in the Flexigrid view
						$data['fileuploadforminc'] = true;					
						$this->load->view('utils/fileuploader', $data);		
					break;		
				case 'documentslist':
						$data['current_tab']   = "data";
						$data['help_content']  = "Documents";
						$data['page_title']    = "Documents List";
						
						// Flexigrid Display Columns
						$colModel['FileID'] 		  = array('ID',40,TRUE,'center',0,TRUE);
						$colModel['Name']    		  = array('Name',200,TRUE,'left',1,FALSE);
						$colModel['Caption'] 		  = array('Caption',200,TRUE,'left',1,FALSE);
						$colModel['Size'] 			  = array('File Size',50,TRUE,'left',1,FALSE);
						$colModel['FileDateAdded'] 	  = array('Updated',50,TRUE,'left',1,FALSE);
						$colModel['Action']		 	  = array('Actions',100,TRUE,'left',0,FALSE);						

						// Flexigrid Display Params
						$gridParams = array(
							'width' => 'auto',
							'height' => 'auto',
							'rp' => 15,
							'rpOptions' => '[10,15,20,25,40]',
							'pagestat' => 'Displaying: {from} to {to} of {total} items.',
							'blockOpacity' => 0.5,
							'title' => 'Medical Documents',
							'showTableToggleBtn' => true,
							'nomsg'    => 'No Data Found',
							'procmsg'  => 'Processing...Please Wait',
							'errormsg' => 'Database Connection Error'						
						); 						
						
						// Flexigrid Buttons
						$buttons[] = array('separator'); 							
						
						
						// Parameterize Variable
						$grid_js = build_grid_js('flexigrid',$this->lang->line('site_ssl_url').'ajax/display/action/documents',$colModel,'Name','asc',$gridParams,$buttons); 
					
						// Variables to use in FlexigridLoader File
						$data['js_grid'] 	    = $grid_js;
						$data['js_save_url']    = "";
						$data['js_edit_url']    = "";
						$data['js_delete_url']  = "";
						$data['side_menu'] 	    = "menu_dataentry";
						
						$this->load->view('utils/flexigridloader', $data);						
					break;											
				case 'emergencycontacts':
						$data['current_tab']   = "data";
						$data['help_content']  = "demographics";
						$data['page_title']    = "Emergency Contacts";
						
					    // Get Permission
						$permission = $this->member_model->check_permissions($this->session->userdata('userid'),'ContactsNPPV');
						
						if ($permission['nppv'] == "1") {
							 $perm = '<img src="/images/locked.gif" title="locked" alt="locked" width="21" height="21" border="0" />';
						}
						else {
							 $perm = '<img src="/images/unlocked.gif" title="locked" alt="locked" width="21" height="21" border="0" />';
						}
												
						// Flexigrid Display Columns
						$colModel['EmergencyContactsSID']    = array('ID',40,TRUE,'center',0,TRUE);
						$colModel['Name'] = array('Name',180,TRUE,'left',1,FALSE);
						$colModel['Phone'] = array('Phone',60,TRUE,'center',1,FALSE);

						// Flexigrid Display Params
						$gridParams = array(
							'width' => 'auto',
							'height' => 'auto',
							'rp' => 15,
							'rpOptions' => '[10,15,20,25,40]',
							'pagestat' => 'Displaying: {from} to {to} of {total} items.',
							'blockOpacity' => 0.5,
							'title' => 'Emergency Contacts - (Password Protected - '.$perm.')',
							'showTableToggleBtn' => true,
							'nomsg'    => 'No Data Found',
							'procmsg'  => 'Processing...Please Wait',
							'errormsg' => 'Database Connection Error'
						); 						
						
						// Flexigrid Buttons
						$data['action_add_label']         = "Add";
						$data['action_edit_label']        = "Edit";
						$data['action_delete_label']      = "Delete";
						$data['action_update_label']      = "Update";
						$data['action_add_form_fields']   = "<tr class=\'trAdded\'><td><input type=\'text\' name=\'Name\' style=\'width: 180px;\'/></td><td><input type=\'text\' name=\'Phone\' style=\'width: 90px;\'/></td></tr>";
						$data['action_save_label']        = "Save";
						$data['action_clear_label']       = "Cancel";
						$data['action_selectall_label']   = "Select All";
						$data['action_deselectall_label'] = "DeSelect All";						
 						$buttons[] = array($data['action_add_label'],'add','toolbar'); 	
 						$buttons[] = array($data['action_save_label'],'save','toolbar');
 						$buttons[] = array($data['action_edit_label'],'edit','toolbar'); 
 						$buttons[] = array($data['action_update_label'],'save','toolbar'); 						
 						$buttons[] = array($data['action_clear_label'],'clear','toolbar');
 						$buttons[] = array($data['action_delete_label'],'delete','toolbar');
						$buttons[] = array('separator');
						$buttons[] = array($data['action_selectall_label'],'add','toolbar');
						$buttons[] = array($data['action_deselectall_label'],'delete','toolbar');
						$buttons[] = array('separator'); 							
						
						
						// Parameterize Variable
						$grid_js = build_grid_js('flexigrid',$this->lang->line('site_ssl_url').'ajax/display/action/emergencycontacts',$colModel,'Name','asc',$gridParams,$buttons); 
					
						// Variables to use in FlexigridLoader File
						$data['js_grid'] 	    = $grid_js;
						$data['js_save_url']    = "ajax/display/action/add/emergencycontacts";
						$data['js_edit_url']    = "ajax/display/action/edit/emergencycontacts";
						$data['js_delete_url']  = "ajax/display/action/delete/emergencycontacts";
						$data['side_menu'] 	    = "menu_dataentry";
						
						$this->load->view('utils/flexigridloader', $data);						
					break;
				case 'familyhistory':
						$data['current_tab']   = "data";
						$data['help_content']  = "familyhistory";
						$data['page_title']    = "Family History";
						
						// Get Permission
						$permission = $this->member_model->check_permissions($this->session->userdata('userid'),'FamilyHistoryNPPV');
						
						$perm = $permission['perm'];
						
						// Flexigrid Display Columns
						$colModel['FamilyHistorySID'] = array('ID',40,TRUE,'center',0,TRUE);
						$colModel['FamilyMember'] = array('Family Member',100,TRUE,'left',1,FALSE);
						$colModel['MedicalCondition'] = array('Condition',150,TRUE,'left',1,FALSE);
						$colModel['LivingDeceased'] = array('Living/Deceased',90,TRUE,'left',1,FALSE);
						$colModel['Age'] = array('Age',75,TRUE,'left',1,FALSE);		
						$colModel['DOBMMValue'] = array('DOBMMValue',30,TRUE,'left',1,TRUE);	
						$colModel['DOBMM'] = array('Month',60,TRUE,'left',1,FALSE);
						$colModel['DOBDD'] = array('Day',50,TRUE,'left',1,FALSE);
						$colModel['DOBYYYY'] = array('Year',50,TRUE,'left',1,FALSE);
						
						// Flexigrid Display Params
						$gridParams = array(
							'width' => 'auto',
							'height' => 'auto',
							'rp' => 15,
							'rpOptions' => '[10,15,20,25,40]',
							'pagestat' => 'Displaying: {from} to {to} of {total} items.',
							'blockOpacity' => 0.5,
							'title' => 'Family History - Date of Birth (Month/Day/Year) - (Password Protected - '.$perm.')',
							'showTableToggleBtn' => true,
							'nomsg'    => 'No Data Found',
							'procmsg'  => 'Processing...Please Wait',
							'errormsg' => 'Database Connection Error'						
						); 			

						// Form Utilities passing into Add and Edit Fields	
						$dropdown_add_familymember = preg_replace("/\r?\n/", "\\n", form_dropdown('FamilyMember',$this->utils->familymembers(),'','id="FamilyMember" style="width: 100px;"')); 
						$dropdown_add_lifestatus = preg_replace("/\r?\n/", "\\n", form_dropdown('LivingDeceased',$this->utils->lifestatus(),'','id="LivingDeceased" style="width: 90px;"')); 
						$dropdown_add_age = $this->utils->age('Age','Age');
						$dropdown_add_year = $this->utils->years('DOBYYYY','DOBYYYY'); 
						$dropdown_add_day = preg_replace("/\r?\n/", "\\n", form_dropdown('DOBDD',$this->utils->days(),'','id="DOBDD" style="width: 50px;"')); 
						$dropdown_add_month = preg_replace("/\r?\n/", "\\n", form_dropdown('DOBMM',$this->utils->month(),'','id="DOBMM" style="width: 50px;"')); 				
						
						// Flexigrid Buttons
						$data['action_add_label']         = "Add";
						$data['action_edit_label']        = "Edit";
						$data['action_delete_label']      = "Delete";
						$data['action_update_label']      = "Update";
						$data['action_add_form_fields']   = '<tr class="trAdded"><td>'.$dropdown_add_familymember.'</td><td><input type="text" name="MedicalCondition" style="width: 150px;"/></td><td>'.$dropdown_add_lifestatus.'</td><td>'.$dropdown_add_age.'</td><td>'.$dropdown_add_month.'</td><td>'.$dropdown_add_day.'</td><td>'.$dropdown_add_year.'</td></tr>';
						$data['action_save_label']        = "Save";
						$data['action_clear_label']       = "Cancel";
						$data['action_selectall_label']   = "Select All";
						$data['action_deselectall_label'] = "DeSelect All";						
 						$buttons[] = array($data['action_add_label'],'add','toolbar'); 	
 						$buttons[] = array($data['action_save_label'],'save','toolbar');
 						$buttons[] = array($data['action_edit_label'],'edit','toolbar'); 
 						$buttons[] = array($data['action_update_label'],'save','toolbar'); 						
 						$buttons[] = array($data['action_clear_label'],'clear','toolbar');
 						$buttons[] = array($data['action_delete_label'],'delete','toolbar');
						$buttons[] = array('separator');
						$buttons[] = array($data['action_selectall_label'],'add','toolbar');
						$buttons[] = array($data['action_deselectall_label'],'delete','toolbar');
						$buttons[] = array('separator'); 							
						
						
						// Parameterize Variable
						$grid_js = build_grid_js('flexigrid',$this->lang->line('site_ssl_url').'ajax/display/action/familyhistory',$colModel,'FamilyMember','asc',$gridParams,$buttons); 
					
						// Variables to use in FlexigridLoader File
						$data['js_grid'] 	    = $grid_js;
						$data['js_save_url']    = "ajax/display/action/add/familyhistory";
						$data['js_edit_url']    = "ajax/display/action/edit/familyhistory";
						$data['js_delete_url']  = "ajax/display/action/delete/familyhistory";
						$data['side_menu'] 	    = "menu_dataentry";
						
						$this->load->view('utils/flexigridloader', $data);						
					break;					
				case 'help':
						$data['current_tab']   = "help";
						$data['help_content']  = "help";
						$data['page_title']    = "Help";
						
						$this->load->view('utils/help', $data);						
					break;							
				case 'hospitalizations':
						$data['current_tab']   = "data";
						$data['help_content']  = "hospitalizations";
						$data['page_title']    = "Hospitalizations";
						
						// Get Permission
						$permission = $this->member_model->check_permissions($this->session->userdata('userid'),'HospitalizationsNPPV');
						
						$perm = $permission['perm'];
						
						// Flexigrid Display Columns
						$colModel['HospitalizationsSID'] = array('ID',40,TRUE,'center',0,TRUE);
						$colModel['HospitalizationCondition'] = array('Condition',100,TRUE,'left',1,FALSE);
						$colModel['MedicationMonthVal'] = array('MonthValue',30,TRUE,'left',1,TRUE);
						$colModel['HospitalizationsMonth'] = array('Month',75,TRUE,'left',1,FALSE);
						$colModel['HospitalizationsYear'] = array('Year',75,TRUE,'left',1,FALSE);
						$colModel['AdmittingPhysician'] = array('Admitting Physician',120,TRUE,'left',1,FALSE);
						$colModel['Hospital'] = array('Hospital',100,TRUE,'left',1,FALSE);
						$colModel['City'] = array('City',50,TRUE,'left',1,FALSE);
						$colModel['State'] = array('State',50,TRUE,'left',1,FALSE);

						// Flexigrid Display Params
						$gridParams = array(
							'width' => 'auto',
							'height' => 'auto',
							'rp' => 15,
							'rpOptions' => '[10,15,20,25,40]',
							'pagestat' => 'Displaying: {from} to {to} of {total} items.',
							'blockOpacity' => 0.5,
							'title' => 'Hospitalizations - (Password Protected - '.$perm.')',
							'showTableToggleBtn' => true,
							'nomsg'    => 'No Data Found',
							'procmsg'  => 'Processing...Please Wait',
							'errormsg' => 'Database Connection Error'						
						); 						
						
						// Form Utilities passing into Add and Edit Fields	
						$dropdown_add_year = $this->utils->years('HospitalizationsYear','HospitalizationsYear'); 
						$dropdown_add_month = preg_replace("/\r?\n/", "\\n", form_dropdown('HospitalizationsMonth',$this->utils->month(),'','id="HospitalizationsMonth" style="width: 30px;"')); 						
						
						
						// Flexigrid Buttons
						$data['action_add_label']         = "Add";
						$data['action_edit_label']        = "Edit";
						$data['action_delete_label']      = "Delete";
						$data['action_update_label']      = "Update";
						$data['action_add_form_fields']   = "<tr class=\'trAdded\'><td><input type=\'text\' name=\'HospitalizationCondition\' style=\'width: 100px;\'/></td><td>".$dropdown_add_month."</td><td>".$dropdown_add_year."</td><td><input type=\'text\' name=\'AdmittingPhysician\' style=\'width: 130px;\'/></td><td><input type=\'text\' name=\'Hospital\' style=\'width: 100px;\'/></td><td><input type=\'text\' name=\'City\' style=\'width: 50px;\'/></td><td><input type=\'text\' name=\'State\' style=\'width: 50px;\'/></td></tr>";
						$data['action_save_label']        = "Save";
						$data['action_clear_label']       = "Cancel";
						$data['action_selectall_label']   = "Select All";
						$data['action_deselectall_label'] = "DeSelect All";						
 						$buttons[] = array($data['action_add_label'],'add','toolbar'); 	
 						$buttons[] = array($data['action_save_label'],'save','toolbar');
 						$buttons[] = array($data['action_edit_label'],'edit','toolbar'); 
 						$buttons[] = array($data['action_update_label'],'save','toolbar'); 						
 						$buttons[] = array($data['action_clear_label'],'clear','toolbar');
 						$buttons[] = array($data['action_delete_label'],'delete','toolbar');
						$buttons[] = array('separator');
						$buttons[] = array($data['action_selectall_label'],'add','toolbar');
						$buttons[] = array($data['action_deselectall_label'],'delete','toolbar');
						$buttons[] = array('separator'); 							
						
						
						// Parameterize Variable
						$grid_js = build_grid_js('flexigrid',$this->lang->line('site_ssl_url').'ajax/display/action/hospitalizations',$colModel,'HospitalizationCondition','asc',$gridParams,$buttons); 
					
						// Variables to use in FlexigridLoader File
						$data['js_grid'] 	    = $grid_js;
						$data['js_save_url']    = "ajax/display/action/add/hospitalizations";
						$data['js_edit_url']    = "ajax/display/action/edit/hospitalizations";
						$data['js_delete_url']  = "ajax/display/action/delete/hospitalizations";
						$data['side_menu'] 	    = "menu_dataentry";
						
						$this->load->view('utils/flexigridloader', $data);						
					break;				
				case 'immunizations':
						$data['current_tab']   = "data";
						$data['help_content']  = "immunizations";
						$data['page_title']    = "Immunizations";
						
						// Get Permission
						$permission = $this->member_model->check_permissions($this->session->userdata('userid'),'ImmunizationsNPPV');
						
						$data['perm'] = $permission['perm'];						
						
				  		$data['lookup_results'] = $this->member_model->get_lookup_data('ImmunizationsLookup','Immunization','asc');					  		
				  		
				  		$data['row_number'] = 0;
						
						$data['side_menu'] 	   = "menu_dataentry";
						$data['form']		   = "immunizations";
						$data['formname']      = "IMMUNIZATIONSSUBMIT";
						$data['formid']        = "IMMUNIZATIONSSUBMIT";
						$data['formmethod']    = "/members/process/immunizations";
						
						$this->load->view('utils/formloader', $data);						
					break;									
				case 'medicalconditions':
						$data['current_tab']   = "data";
						$data['help_content']  = "medicalconditions";
						$data['page_title']    = "Medical Conditions";
						
						// Get Permission
						$permission = $this->member_model->check_permissions($this->session->userdata('userid'),'MedicalConditionsNPPV');
						
						$perm = $permission['perm'];
						
						// Flexigrid Display Columns
						$colModel['MedicalConditionsSID'] = array('ID',40,TRUE,'center',0,TRUE);
						$colModel['MedicalCondition'] = array('Condition',100,TRUE,'left',1,FALSE);
						$colModel['MedicationMonthVal'] = array('MonthValue',30,TRUE,'left',1,TRUE);
						$colModel['DiagnosedMonth'] = array('Diagnosed Month',120,TRUE,'left',1,FALSE);
						$colModel['DiagnosedYear'] = array('Year',25,TRUE,'left',1,FALSE);
						$colModel['PhysicianTreating'] = array('Treating Physician',120,TRUE,'left',1,FALSE);

						// Flexigrid Display Params
						$gridParams = array(
							'width' => 'auto',
							'height' => 'auto',
							'rp' => 15,
							'rpOptions' => '[10,15,20,25,40]',
							'pagestat' => 'Displaying: {from} to {to} of {total} items.',
							'blockOpacity' => 0.5,
							'title' => 'Medical Conditions - (Password Protected - '.$perm.')',
							'showTableToggleBtn' => true,
							'nomsg'    => 'No Data Found',
							'procmsg'  => 'Processing...Please Wait',
							'errormsg' => 'Database Connection Error'						
						); 						
						
						// Form Utilities passing into Add and Edit Fields	
						$dropdown_add_year = $this->utils->years('DiagnosedYear','DiagnosedYear'); 
						$dropdown_add_month = preg_replace("/\r?\n/", "\\n", form_dropdown('DiagnosedMonth',$this->utils->month(),'','style="width: 120px;"')); 				
												
						
						// Flexigrid Buttons
						$data['action_add_label']         = "Add";
						$data['action_edit_label']        = "Edit";
						$data['action_delete_label']      = "Delete";
						$data['action_update_label']      = "Update";
						$data['action_add_form_fields']   = "<tr class=\'trAdded\'><td><input type=\'text\' name=\'MedicalCondition\' style=\'width: 100px;\'/></td><td>".$dropdown_add_month."</td><td>".$dropdown_add_year."</td><td><input type=\'text\' name=\'PhysicianTreating\' style=\'width: 120px;\'/></td></tr>";
						$data['action_save_label']        = "Save";
						$data['action_clear_label']       = "Cancel";
						$data['action_selectall_label']   = "Select All";
						$data['action_deselectall_label'] = "DeSelect All";						
 						$buttons[] = array($data['action_add_label'],'add','toolbar'); 	
 						$buttons[] = array($data['action_save_label'],'save','toolbar');
 						$buttons[] = array($data['action_edit_label'],'edit','toolbar'); 
 						$buttons[] = array($data['action_update_label'],'save','toolbar'); 						
 						$buttons[] = array($data['action_clear_label'],'clear','toolbar');
 						$buttons[] = array($data['action_delete_label'],'delete','toolbar');
						$buttons[] = array('separator');
						$buttons[] = array($data['action_selectall_label'],'add','toolbar');
						$buttons[] = array($data['action_deselectall_label'],'delete','toolbar');
						$buttons[] = array('separator'); 							
						
						
						// Parameterize Variable
						$grid_js = build_grid_js('flexigrid',$this->lang->line('site_ssl_url').'ajax/display/action/medicalconditions',$colModel,'MedicalCondition','asc',$gridParams,$buttons); 
					
						// Variables to use in FlexigridLoader File
						$data['js_grid'] 	    = $grid_js;
						$data['js_save_url']    = "ajax/display/action/add/medicalconditions";
						$data['js_edit_url']    = "ajax/display/action/edit/medicalconditions";
						$data['js_delete_url']  = "ajax/display/action/delete/medicalconditions";
						$data['side_menu'] 	    = "menu_dataentry";
						
						$this->load->view('utils/flexigridloader', $data);						
					break;						
				case 'photo':
						$data['current_tab']   = "data";
						$data['help_content']  = "photo";
						$data['page_title']    = "Photo";
						
						$data['side_menu'] 	   = "menu_dataentry";
						$data['form']		   = "file";
						$data['formid']	       = "photo";
						$data['formname']      = "photo";
						$data['formtype']	   = "photo";
						$data['formcancel']	   = "/members/display/action/photo";
						$data['formapprovedtypes'] = "JPEG or PNG";
						$data['formmethod']    = "/members/process/file";
						
					    // Get File Upload Record
						$record = $this->member_model->check_files($this->session->userdata('userid'),'photo');

						$numrows 		 = $record['numrows'];
						$data['caption'] = $record['caption'];
						$data['type']    = $record['type'];
						$data['size']    = $record['size'];
						$data['fileid']  = $record['fileid'];
						
						if ($numrows > 0) {
							$data['submitlabel'] = "Replace Photo";
							$data['submitname']  = "delete";
						}
						else {
							$data['submitlabel'] = "Add Photo";
							$data['submitname']  = "add";
						}
						
						$this->load->view('utils/fileuploader', $data);						
					break;		
				case 'physicians':
						$data['current_tab']   = "data";
						$data['help_content']  = "physicians";
						$data['page_title']    = "Primary Care - Family";
						
						// Get Permission
						$permission = $this->member_model->check_permissions($this->session->userdata('userid'),'PrimaryPhysicianNPPV');
						
						$perm = $permission['perm'];
						
						// Flexigrid Display Columns
						$colModel['PrimaryPhysicianSID'] = array('ID',40,TRUE,'center',0,TRUE);
						$colModel['FirstName'] = array('First Name',70,TRUE,'left',1,FALSE);
						$colModel['LastName'] = array('Last Name',70,TRUE,'left',1,FALSE);
						$colModel['AddressLine1'] = array('Address 1',50,TRUE,'left',1,FALSE);
						$colModel['AddressLine2'] = array('Address 2',50,TRUE,'left',1,FALSE);
						$colModel['City'] = array('City',40,TRUE,'left',1,FALSE);
						$colModel['State'] = array('State',40,TRUE,'left',1,FALSE);
						$colModel['Zip'] = array('Zip',40,TRUE,'left',1,FALSE);
						$colModel['EmailAddress'] = array('Email',40,TRUE,'left',1,FALSE);
						$colModel['Phone'] = array('Phone',25,TRUE,'left',1,FALSE);
						$colModel['Fax'] = array('Fax',40,TRUE,'left',1,FALSE);
						
						// Flexigrid Display Params
						$gridParams = array(
							'width' => 'auto',
							'height' => 'auto',
							'rp' => 15,
							'rpOptions' => '[10,15,20,25,40]',
							'pagestat' => 'Displaying: {from} to {to} of {total} items.',
							'blockOpacity' => 0.5,
							'title' => 'Primary Care - Family - (Password Protected - '.$perm.')',
							'showTableToggleBtn' => true,
							'nomsg'    => 'No Data Found',
							'procmsg'  => 'Processing...Please Wait',
							'errormsg' => 'Database Connection Error'						
						); 			

						// Flexigrid Buttons
						$data['action_add_label']         = "Add";
						$data['action_edit_label']        = "Edit";
						$data['action_delete_label']      = "Delete";
						$data['action_update_label']      = "Update";
						$data['action_add_form_fields']   = "<tr class=\'trAdded\'><td><input type=\'text\' name=\'FirstName\' style=\'width: 70px;\'/></td><td><input type=\'text\' name=\'LastName\' style=\'width: 70px;\'/>&nbsp;&nbsp;</td><td><input type=\'text\' name=\'AddressLine1\' style=\'width: 50px;\'/></td><td><input type=\'text\' name=\'AddressLine2\' style=\'width: 50px;\'/></td><td><input type=\'text\' name=\'City\' style=\'width: 40px;\'/></td><td><input type=\'text\' name=\'State\' style=\'width: 40px;\'/></td><td><input type=\'text\' name=\'Zip\' style=\'width: 40px;\'/></td><td><input type=\'text\' name=\'EmailAddress\' style=\'width: 40px;\'/></td><td><input type=\'text\' name=\'Phone\' style=\'width: 40px;\'/></td><td><input type=\'text\' name=\'Fax\' style=\'width: 40px;\'/></td></tr>";
						$data['action_save_label']        = "Save";
						$data['action_clear_label']       = "Cancel";
						$data['action_selectall_label']   = "Select All";
						$data['action_deselectall_label'] = "DeSelect All";						
 						$buttons[] = array($data['action_add_label'],'add','toolbar'); 	
 						$buttons[] = array($data['action_save_label'],'save','toolbar');
 						$buttons[] = array($data['action_edit_label'],'edit','toolbar'); 
 						$buttons[] = array($data['action_update_label'],'save','toolbar'); 						
 						$buttons[] = array($data['action_clear_label'],'clear','toolbar');
 						$buttons[] = array($data['action_delete_label'],'delete','toolbar');
						$buttons[] = array('separator');
						$buttons[] = array($data['action_selectall_label'],'add','toolbar');
						$buttons[] = array($data['action_deselectall_label'],'delete','toolbar');
						$buttons[] = array('separator'); 							
						
						
						// Parameterize Variable
						$grid_js = build_grid_js('flexigrid',$this->lang->line('site_ssl_url').'ajax/display/action/physicians',$colModel,'PrimaryPhysician','asc',$gridParams,$buttons); 
					
						// Variables to use in FlexigridLoader File
						$data['js_grid'] 	    = $grid_js;
						$data['js_save_url']    = "ajax/display/action/add/physicians";
						$data['js_edit_url']    = "ajax/display/action/edit/physicians";
						$data['js_delete_url']  = "ajax/display/action/delete/physicians";
						$data['side_menu'] 	    = "menu_dataentry";
						
						$this->load->view('utils/flexigridloader', $data);						
					break;			
				case 'notes':
						$data['current_tab']   = "data";
						$data['help_content']  = "physicians";
						$data['page_title']    = "Physician Notes";
						
						// Get Permission
						$permission = $this->member_model->check_permissions($this->session->userdata('userid'),'PhysicianNotesNPPV');
						
						$perm = $permission['perm'];
						
						// Flexigrid Display Columns
						$colModel['NoteSID'] 		  = array('ID',40,TRUE,'center',0,TRUE);
						$colModel['PhysicianName']    = array('Physician',100,TRUE,'left',1,FALSE);
						$colModel['Notes'] 			  = array('Notes',200,TRUE,'left',1,FALSE);
						$colModel['Summary'] 		  = array('Summary',200,TRUE,'left',1,FALSE);
						$colModel['UpdDate'] 		  = array('Updated',200,TRUE,'left',1,FALSE);

						// Flexigrid Display Params
						$gridParams = array(
							'width' => 'auto',
							'height' => 'auto',
							'rp' => 15,
							'rpOptions' => '[10,15,20,25,40]',
							'pagestat' => 'Displaying: {from} to {to} of {total} items.',
							'blockOpacity' => 0.5,
							'title' => 'Physician Notes - (Password Protected - '.$perm.')',
							'showTableToggleBtn' => true,
							'nomsg'    => 'No Data Found',
							'procmsg'  => 'Processing...Please Wait',
							'errormsg' => 'Database Connection Error'						
						); 						
						
						// Flexigrid Buttons
						$buttons[] = array('separator'); 							
						
						
						// Parameterize Variable
						$grid_js = build_grid_js('flexigrid',$this->lang->line('site_ssl_url').'ajax/display/action/notes',$colModel,'PhysicianName','asc',$gridParams,$buttons); 
					
						// Variables to use in FlexigridLoader File
						$data['js_grid'] 	    = $grid_js;
						$data['js_save_url']    = "";
						$data['js_edit_url']    = "";
						$data['js_delete_url']  = "";
						$data['side_menu'] 	    = "menu_dataentry";
						
						$this->load->view('utils/flexigridloader', $data);						
					break;								
				case 'overcounter':
						$data['current_tab']   = "data";
						$data['help_content']  = "prescriptions";
						$data['page_title']    = "Over Counter Medications";
						
						// Get Permission
						$permission = $this->member_model->check_permissions($this->session->userdata('userid'),'OCMedicationsNPPV');
						
						$perm = $permission['perm'];
						
						// Flexigrid Display Columns
						$colModel['MedicationsSID'] = array('ID',40,TRUE,'center',0,TRUE);
						$colModel['Medication'] = array('Medication',100,TRUE,'left',1,FALSE);
						$colModel['MedicationMonthVal'] = array('MonthValue',10,TRUE,'left',1,TRUE);
						$colModel['MedicationMonth'] = array('Month',75,TRUE,'left',1,FALSE);
						$colModel['MedicationYear'] = array('Year',75,TRUE,'left',1,FALSE);
						$colModel['Dose'] = array('Dose',75,TRUE,'left',1,FALSE);
						$colModel['Frequency'] = array('Frequency',70,TRUE,'left',1,FALSE);
						$colModel['MedicalCondition'] = array('Medical Condition',100,TRUE,'left',1,FALSE);
						
						// Flexigrid Display Params
						$gridParams = array(
							'width' => 'auto',
							'height' => 'auto',
							'rp' => 15,
							'rpOptions' => '[10,15,20,25,40]',
							'pagestat' => 'Displaying: {from} to {to} of {total} items.',
							'blockOpacity' => 0.5,
							'title' => 'Over Counter Medications - (Password Protected - '.$perm.')',
							'showTableToggleBtn' => true,
							'nomsg'    => 'No Data Found',
							'procmsg'  => 'Processing...Please Wait',
							'errormsg' => 'Database Connection Error'						
						); 			

						// Form Utilities passing into Add and Edit Fields
						$dropdown_add_year = $this->utils->years('MedicationYear','MedicationYear');
						$dropdown_add_month = preg_replace("/\r?\n/", "\\n", form_dropdown('MedicationMonth',$this->utils->month(),'','id="MedicationMonthID" style="width: 75px;"')); 						
						
						// Flexigrid Buttons
						$data['action_add_label']         = "Add";
						$data['action_edit_label']        = "Edit";
						$data['action_delete_label']      = "Delete";
						$data['action_update_label']      = "Update";
						$data['action_add_form_fields']   = "<tr class=\'trAdded\'><td><input type=\'text\' name=\'Medication\' style=\'width: 100px;\'/></td><td>".$dropdown_add_month."&nbsp;&nbsp;&nbsp;</td><td>".$dropdown_add_year."</td><td>&nbsp;<input type=\'text\' name=\'Dose\' style=\'width: 75px;\'/>&nbsp;&nbsp;&nbsp;</td><td><input type=\'text\' name=\'Frequency\' style=\'width: 70px;\'/></td><td><input type=\'text\' name=\'MedicalCondition\' style=\'width: 100px;\'/></td></tr>";
						$data['action_save_label']        = "Save";
						$data['action_clear_label']       = "Cancel";
						$data['action_selectall_label']   = "Select All";
						$data['action_deselectall_label'] = "DeSelect All";						
 						$buttons[] = array($data['action_add_label'],'add','toolbar'); 	
 						$buttons[] = array($data['action_save_label'],'save','toolbar');
 						$buttons[] = array($data['action_edit_label'],'edit','toolbar'); 
 						$buttons[] = array($data['action_update_label'],'save','toolbar'); 						
 						$buttons[] = array($data['action_clear_label'],'clear','toolbar');
 						$buttons[] = array($data['action_delete_label'],'delete','toolbar');
						$buttons[] = array('separator');
						$buttons[] = array($data['action_selectall_label'],'add','toolbar');
						$buttons[] = array($data['action_deselectall_label'],'delete','toolbar');
						$buttons[] = array('separator'); 							
						
						
						// Parameterize Variable
						$grid_js = build_grid_js('flexigrid',$this->lang->line('site_ssl_url').'ajax/display/action/overcounter',$colModel,'FamilyMember','asc',$gridParams,$buttons); 
					
						// Variables to use in FlexigridLoader File
						$data['js_grid'] 	    = $grid_js;
						$data['js_save_url']    = "ajax/display/action/add/overcounter";
						$data['js_edit_url']    = "ajax/display/action/edit/overcounter";
						$data['js_delete_url']  = "ajax/display/action/delete/overcounter";
						$data['js_month_url']	= "ajax/display/action/check/month";
						$data['js_year_url']	= "ajax/display/action/check/year";
						$data['side_menu'] 	    = "menu_dataentry";
						
						$this->load->view('utils/flexigridloader', $data);						
					break;					
				case 'prescriptions':
						$data['current_tab']   = "data";
						$data['help_content']  = "prescriptions";
						$data['page_title']    = "Prescriptions";
						
						// Get Permission
						$permission = $this->member_model->check_permissions($this->session->userdata('userid'),'MedicationsNPPV');
						
						$perm = $permission['perm'];
						
						// Flexigrid Display Columns
						$colModel['MedicationsSID'] = array('ID',40,TRUE,'center',0,TRUE);
						$colModel['Medication'] = array('Medication',100,TRUE,'left',1,FALSE);
						$colModel['MedicationMonthVal'] = array('MonthValue',30,TRUE,'left',1,TRUE);
						$colModel['MedicationMonth'] = array('Month',75,TRUE,'left',1,FALSE);
						$colModel['MedicationYear'] = array('Year',75,TRUE,'left',1,FALSE);
						$colModel['Dose'] = array('Dose',75,TRUE,'left',1,FALSE);
						$colModel['Frequency'] = array('Frequency',75,TRUE,'left',1,FALSE);
						$colModel['PrescribingDr'] = array('Doctor',70,TRUE,'left',1,FALSE);
						$colModel['Pharmacy'] = array('Pharmacy',70,TRUE,'left',1,FALSE);
						$colModel['PharmacyPhone'] = array('Phone',70,TRUE,'left',1,FALSE);
						
						// Flexigrid Display Params
						$gridParams = array(
							'width' => 'auto',
							'height' => 'auto',
							'rp' => 15,
							'rpOptions' => '[10,15,20,25,40]',
							'pagestat' => 'Displaying: {from} to {to} of {total} items.',
							'blockOpacity' => 0.5,
							'title' => 'Prescriptions - (Password Protected - '.$perm.')',
							'showTableToggleBtn' => true,
							'nomsg'    => 'No Data Found',
							'procmsg'  => 'Processing...Please Wait',
							'errormsg' => 'Database Connection Error'						
						); 	

						// Form Utilities passing into Add and Edit Fields	
						$dropdown_add_year = $this->utils->years('MedicationYear','MedicationYear');
						$dropdown_add_month = preg_replace("/\r?\n/", "\\n", form_dropdown('MedicationMonth',$this->utils->month(),'','id="MedicationMonth" style="width: 75px;"')); 							
						
						// Flexigrid Buttons
						$data['action_add_label']         = "Add";
						$data['action_edit_label']        = "Edit";
						$data['action_delete_label']      = "Delete";
						$data['action_update_label']      = "Update";
						$data['action_add_form_fields']   = "<tr class=\'trAdded\'><td><input type=\'text\' name=\'Medication\' style=\'width: 100px;\'/></td><td>".$dropdown_add_month."</td><td>".$dropdown_add_year."</td><td>&nbsp;<input type=\'text\' name=\'Dose\' style=\'width:75px;\'/>&nbsp;&nbsp;&nbsp;</td><td><input type=\'text\' name=\'Frequency\' style=\'width: 70px;\'/></td><td><input type=\'text\' name=\'PrescribingDr\' style=\'width: 70px;\'/></td><td><input type=\'text\' name=\'Pharmacy\' style=\'width: 70px;\'/></td><td><input type=\'text\' name=\'PharmacyPhone\' style=\'width: 70px;\'/></td></tr>";
						$data['action_save_label']        = "Save";
						$data['action_clear_label']       = "Cancel";
						$data['action_selectall_label']   = "Select All";
						$data['action_deselectall_label'] = "DeSelect All";						
 						$buttons[] = array($data['action_add_label'],'add','toolbar'); 	
 						$buttons[] = array($data['action_save_label'],'save','toolbar');
 						$buttons[] = array($data['action_edit_label'],'edit','toolbar'); 
 						$buttons[] = array($data['action_update_label'],'save','toolbar'); 						
 						$buttons[] = array($data['action_clear_label'],'clear','toolbar');
 						$buttons[] = array($data['action_delete_label'],'delete','toolbar');
						$buttons[] = array('separator');
						$buttons[] = array($data['action_selectall_label'],'add','toolbar');
						$buttons[] = array($data['action_deselectall_label'],'delete','toolbar');
						$buttons[] = array('separator'); 							
						
						
						// Parameterize Variable
						$grid_js = build_grid_js('flexigrid',$this->lang->line('site_ssl_url').'ajax/display/action/prescriptions',$colModel,'FamilyMember','asc',$gridParams,$buttons); 
					
						// Variables to use in FlexigridLoader File
						$data['js_grid'] 	    = $grid_js;
						$data['js_save_url']    = "ajax/display/action/add/prescriptions";
						$data['js_edit_url']    = "ajax/display/action/edit/prescriptions";
						$data['js_delete_url']  = "ajax/display/action/delete/prescriptions";
						$data['side_menu'] 	    = "menu_dataentry";
						
						$this->load->view('utils/flexigridloader', $data);						
					break;																		
				case 'print';
						$data['current_tab']  = "print";
						$data['help_content']  = "print";
						$data['page_title'] = "Print";
						$this->load->view('utils/print', $data);					
					break;
				case 'preferences':
						$data['current_tab']  = "data";
						$data['help_content']  = "preferences";
						$data['page_title'] = "Security Preferences";
						
						// Flexigrid Display Columns
						$colModel['NppvID']    = array('ID',40,FALSE,'center',0,TRUE);
						$colModel['NPPVTitle'] = array('Title',180,FALSE,'left',1,FALSE);
						$colModel['NPPV'] 	   = array('Status',60,FALSE,'center',0,FALSE);

						// Flexigrid Display Params
						$gridParams = array(
							'width' => 'auto',
							'height' => 'auto',
							'rp' => 15,
							'rpOptions' => '[10,15,20,25,40]',
							'pagestat' => 'Displaying: {from} to {to} of {total} items.',
							'blockOpacity' => 0.5,
							'title' => 'Security Preferences',
							'showTableToggleBtn' => true,
							'nomsg'    => 'No Data Found',
							'procmsg'  => 'Processing...Please Wait',
							'errormsg' => 'Database Connection Error'						
						); 						
						
						// Flexigrid Buttons
						$data['action_disable_label']     = "Unlock";
						$data['action_enable_label']      = "Lock";
						$data['action_selectall_label']   = "Select All";
						$data['action_deselectall_label'] = "DeSelect All";
						$buttons[] = array($data['action_selectall_label'],'add','toolbar');
						$buttons[] = array($data['action_deselectall_label'],'delete','toolbar');
						$buttons[] = array('separator'); 							
						$buttons[] = array($data['action_enable_label'],'add','toolbar');						
						$buttons[] = array($data['action_disable_label'],'delete','toolbar');
						$buttons[] = array('separator'); 									
						
						// Parameterize Variable
						$grid_js = build_grid_js('flexigrid',$this->lang->line('site_ssl_url').'ajax/display/action/preferences',$colModel,'NPPVTitle','asc',$gridParams,$buttons); 
					
						// Variables to use in FlexigridLoader File
						$data['js_grid'] 	    = $grid_js;
						$data['js_save_url']    = "ajax/display/action/add/preferences";
						$data['js_edit_url']    = "ajax/display/action/edit/preferences";
 						$data['js_enable_url']  = "ajax/display/action/toggle/preferences/enable";
						$data['js_disable_url'] = "ajax/display/action/toggle/preferences/disable"; 
						$data['side_menu'] 	    = "menu_dataentry";
										
						$this->load->view('utils/flexigridloader', $data);						
					break;
				case 'socialhistory':
						$data['current_tab']   = "data";
						$data['help_content']  = "socialhistory";
						$data['page_title']    = "Social History";
						
						// Get Permission
						$permission = $this->member_model->check_permissions($this->session->userdata('userid'),'SocialHistoryNPPV');
						
						$data['perm'] = $permission['perm'];						
						
				  		$data['results'] = $this->member_model->get_all_data($this->session->userdata('userid'),'SocialHistory');	
						
						$data['side_menu'] 	   = "menu_dataentry";
						$data['form']		   = "socialhistory";
						$data['formname']      = "SOCIALHISTORYSUBMIT";
						$data['formid']        = "SOCIALHISTORYSUBMIT";
						$data['formmethod']    = "/members/process/socialhistory";						
						
						$this->load->view('utils/formloader', $data);						
					break;					
				case 'specialists':
						$data['current_tab']   = "data";
						$data['help_content']  = "specialists";
						$data['page_title']    = "Specialists";
						
						// Get Permission
						$permission = $this->member_model->check_permissions($this->session->userdata('userid'),'SpecialtyPhysicianNPPV');
						
						$perm = $permission['perm'];
						
						// Flexigrid Display Columns
						$colModel['SpecialtyPhysicianSID'] = array('ID',40,TRUE,'center',0,TRUE);
						$colModel['SpecialityIDValue'] = array('SpecialtyIDValue',40,TRUE,'center',0,TRUE);
						$colModel['SpecialityID'] = array('Specialty',40,TRUE,'center',0,FALSE);
						$colModel['FirstName'] = array('First Name',70,TRUE,'left',1,FALSE);
						$colModel['LastName'] = array('Last Name',70,TRUE,'left',1,FALSE);
						$colModel['AddressLine1'] = array('Address 1',50,TRUE,'left',1,FALSE);
						$colModel['AddressLine2'] = array('Address 2',50,TRUE,'left',1,FALSE);
						$colModel['City'] = array('City',40,TRUE,'left',1,FALSE);
						$colModel['State'] = array('State',40,TRUE,'left',1,FALSE);
						$colModel['Zip'] = array('Zip',40,TRUE,'left',1,FALSE);
						$colModel['EmailAddress'] = array('Email',40,TRUE,'left',1,FALSE);
						$colModel['Phone'] = array('Phone',25,TRUE,'left',1,FALSE);
						$colModel['Fax'] = array('Fax',40,TRUE,'left',1,FALSE);
						
						// Flexigrid Display Params
						$gridParams = array(
							'width' => 'auto',
							'height' => 'auto',
							'rp' => 15,
							'rpOptions' => '[10,15,20,25,40]',
							'pagestat' => 'Displaying: {from} to {to} of {total} items.',
							'blockOpacity' => 0.5,
							'title' => 'Specialists - (Password Protected - '.$perm.')',
							'showTableToggleBtn' => true,
							'nomsg'    => 'No Data Found',
							'procmsg'  => 'Processing...Please Wait',
							'errormsg' => 'Database Connection Error'						
						); 		

						// Form Utilities passing into Add and Edit Fields	
						$specialities = $this->member_model->get_dropdown_data('SpecialityMaster','SpecialityType','ASC');
						foreach	($specialities AS $row) {
    						$specialityname[$row->SpecialityID] = &$row->SpecialityType;
						}
						$dropdown_add_speciality = preg_replace("/\r?\n/", "\\n", form_dropdown('SpecialityID',$specialityname,'','id="SpecialityID" style="width: 40px;"'));

						// Flexigrid Buttons
						$data['action_add_label']         = "Add";
						$data['action_edit_label']        = "Edit";
						$data['action_delete_label']      = "Delete";
						$data['action_update_label']      = "Update";
						$data['action_add_form_fields']   = "<tr class=\'trAdded\'><td>".$dropdown_add_speciality."</td><td><input type=\'text\' name=\'FirstName\' style=\'width: 70px;\'/></td><td><input type=\'text\' name=\'LastName\' style=\'width: 70px;\'/>&nbsp;&nbsp;</td><td><input type=\'text\' name=\'AddressLine1\' style=\'width: 50px;\'/></td><td><input type=\'text\' name=\'AddressLine2\' style=\'width: 50px;\'/></td><td><input type=\'text\' name=\'City\' style=\'width: 40px;\'/></td><td><input type=\'text\' name=\'State\' style=\'width: 40px;\'/></td><td><input type=\'text\' name=\'Zip\' style=\'width: 40px;\'/></td><td><input type=\'text\' name=\'EmailAddress\' style=\'width: 40px;\'/></td><td><input type=\'text\' name=\'Phone\' style=\'width: 40px;\'/></td><td><input type=\'text\' name=\'Fax\' style=\'width: 40px;\'/></td></tr>";
						$data['action_save_label']        = "Save";
						$data['action_clear_label']       = "Cancel";
						$data['action_selectall_label']   = "Select All";
						$data['action_deselectall_label'] = "DeSelect All";						
 						$buttons[] = array($data['action_add_label'],'add','toolbar'); 	
 						$buttons[] = array($data['action_save_label'],'save','toolbar');
 						$buttons[] = array($data['action_edit_label'],'edit','toolbar'); 
 						$buttons[] = array($data['action_update_label'],'save','toolbar'); 						
 						$buttons[] = array($data['action_clear_label'],'clear','toolbar');
 						$buttons[] = array($data['action_delete_label'],'delete','toolbar');
						$buttons[] = array('separator');
						$buttons[] = array($data['action_selectall_label'],'add','toolbar');
						$buttons[] = array($data['action_deselectall_label'],'delete','toolbar');
						$buttons[] = array('separator'); 				
						
						
						// Parameterize Variable
						$grid_js = build_grid_js('flexigrid',$this->lang->line('site_ssl_url').'ajax/display/action/specialists',$colModel,'SpecialtyPhysician','asc',$gridParams,$buttons); 
					
						// Variables to use in FlexigridLoader File
						$data['js_grid'] 	    = $grid_js;
						$data['js_save_url']    = "ajax/display/action/add/specialists";
						$data['js_edit_url']    = "ajax/display/action/edit/specialists";
						$data['js_delete_url']  = "ajax/display/action/delete/specialists";
						$data['side_menu'] 	    = "menu_dataentry";
						
						$this->load->view('utils/flexigridloader', $data);								
					break;						
				case 'surgeries':
						$data['current_tab']   = "data";
						$data['help_content']  = "surgeries";
						$data['page_title']    = "Surgeries";
						
						// Get Permission
						$permission = $this->member_model->check_permissions($this->session->userdata('userid'),'SurgeriesNPPV');
						
						$perm = $permission['perm'];
						
						// Flexigrid Display Columns
						$colModel['SurgeriesSID'] = array('ID',40,TRUE,'center',0,TRUE);
						$colModel['SurgicalProcedure'] = array('Procedure',100,TRUE,'left',1,FALSE);
						$colModel['MedicationMonthVal'] = array('MonthValue',30,TRUE,'left',1,TRUE);
						$colModel['SurgeriesMonth'] = array('Month',75,TRUE,'left',1,FALSE);
						$colModel['SurgeriesYear'] = array('Year',75,TRUE,'left',1,FALSE);
						$colModel['Surgeon'] = array('Surgeon',120,TRUE,'left',1,FALSE);
						$colModel['Hospital'] = array('Hospital',100,TRUE,'left',1,FALSE);
						$colModel['City'] = array('City',50,TRUE,'left',1,FALSE);
						$colModel['State'] = array('State',50,TRUE,'left',1,FALSE);

						// Flexigrid Display Params
						$gridParams = array(
							'width' => 'auto',
							'height' => 'auto',
							'rp' => 15,
							'rpOptions' => '[10,15,20,25,40]',
							'pagestat' => 'Displaying: {from} to {to} of {total} items.',
							'blockOpacity' => 0.5,
							'title' => 'Surgeries - (Password Protected - '.$perm.')',
							'showTableToggleBtn' => true,
							'nomsg'    => 'No Data Found',
							'procmsg'  => 'Processing...Please Wait',
							'errormsg' => 'Database Connection Error'						
						); 	

						// Form Utilities passing into Add and Edit Fields	
						$dropdown_add_year = $this->utils->years('SurgeriesYear','SurgeriesYear');
						$dropdown_add_month = preg_replace("/\r?\n/", "\\n", form_dropdown('SurgeriesMonth',$this->utils->month(),'','id="SurgeriesMonth" style="width: 75px;"')); 						
						
						// Flexigrid Buttons
						$data['action_add_label']         = "Add";
						$data['action_edit_label']        = "Edit";
						$data['action_delete_label']      = "Delete";
						$data['action_update_label']      = "Update";
						$data['action_add_form_fields']   = "<tr class=\'trAdded\'><td><input type=\'text\' name=\'SurgicalProcedure\' style=\'width: 100px;\'/></td><td>".$dropdown_add_month."</td><td>".$dropdown_add_year."</td><td><input type=\'text\' name=\'Surgeon\' style=\'width: 130px;\'/></td><td><input type=\'text\' name=\'Hospital\' style=\'width: 100px;\'/></td><td><input type=\'text\' name=\'City\' style=\'width: 50px;\'/></td><td><input type=\'text\' name=\'State\' style=\'width: 50px;\'/></td></tr>";
						$data['action_save_label']        = "Save";
						$data['action_clear_label']       = "Cancel";
						$data['action_selectall_label']   = "Select All";
						$data['action_deselectall_label'] = "DeSelect All";						
 						$buttons[] = array($data['action_add_label'],'add','toolbar'); 	
 						$buttons[] = array($data['action_save_label'],'save','toolbar');
 						$buttons[] = array($data['action_edit_label'],'edit','toolbar'); 
 						$buttons[] = array($data['action_update_label'],'save','toolbar'); 						
 						$buttons[] = array($data['action_clear_label'],'clear','toolbar');
 						$buttons[] = array($data['action_delete_label'],'delete','toolbar');
						$buttons[] = array('separator');
						$buttons[] = array($data['action_selectall_label'],'add','toolbar');
						$buttons[] = array($data['action_deselectall_label'],'delete','toolbar');
						$buttons[] = array('separator'); 							
						
						
						// Parameterize Variable
						$grid_js = build_grid_js('flexigrid',$this->lang->line('site_ssl_url').'ajax/display/action/surgeries',$colModel,'SurgicalProcedure','asc',$gridParams,$buttons); 
					
						// Variables to use in FlexigridLoader File
						$data['js_grid'] 	    = $grid_js;
						$data['js_save_url']    = "ajax/display/action/add/surgeries";
						$data['js_edit_url']    = "ajax/display/action/edit/surgeries";
						$data['js_delete_url']  = "ajax/display/action/delete/surgeries";
						$data['side_menu'] 	    = "menu_dataentry";
						
						$this->load->view('utils/flexigridloader', $data);						
					break;								
				default:
						$data['current_tab']  = "members";
						$data['help_content']  = "help";
						$data['page_title'] = "Member";
						$this->load->view('members/index', $data);
					break;	
			}
			
		}
		else {
			 $data['current_tab']  = "members";
			 $data['help_content']  = "help";
			 $data['page_title'] = "Members";
			 $data['message'] = 'You do not have permission to view this page. Please login.';
			 $this->load->view('utils/generator', $data);
		}
	}
	
	public function process($object = "", $objectid = "")
	{
		/* load view */				
		if ($this->session->userdata('loggedin') == true) {	
			switch($object) {
				case 'demographics':
						  /* General Configurations */
						  $data['current_tab']   = "data";
						  $data['page_title']    = "Demographics";
						  $data['side_menu'] 	   = "menu_dataentry";
						  $data['form']		   = "demographics";
						  $data['formname']      = "DATAENTRY1SUBMIT";
						  $data['formid']        = "DATAENTRY1SUBMIT";
						  $data['formmethod']    = "/members/process/demographics";	
						  									
						  // Get Permission
						  $general_permission = $this->member_model->check_permissions($this->session->userdata('userid'),'GeneralInfoNPPV');
								
						  $data['gen_perm'] = $general_permission['perm'];
									
						  $location_permission = $this->member_model->check_permissions($this->session->userdata('userid'),'LocationInfoNPPV');

						  $data['loc_perm'] = $location_permission['perm'];	
						
						  $contactinfo_permission = $this->member_model->check_permissions($this->session->userdata('userid'),'ContactInfoNPPV');
						
						  $data['con_perm'] = $contactinfo_permission['perm'];	

						  $data['results'] = $this->member_model->get_all_data($this->session->userdata('userid'),'PatientMaster');
						  
						  /* Rules */
						  $rules['txtFirstName']		   = "required";
						  $rules['txtAddressLine1']        = "required";
						  $rules['txtCity']		   	       = "required";
						  $rules['txtState']       	       = "required";
						  $rules['txtZip']		   		   = "required";
						  $rules['txtEmailAddress']        = "required";						  						  
		
						  $this->validation->set_rules($rules);
		
						  $fields['txtFirstName']		   = 'First Name';
						  $fields['txtAddressLine1']	   = 'Address';	
						  $fields['txtCity']		   	   = 'City';
						  $fields['txtState']	           = 'State';
						  $fields['txtZip']		      	   = 'Zip';
						  $fields['txtEmailAddress']	   = 'Email Address';						  						  
						  
						  $this->validation->set_fields($fields);
						  
						  if ($this->validation->run() == FALSE) {																									
								// Load View
								$this->load->view('utils/formloader', $data);
						  }
						  else {
								/* Process Form */
						  		$listrows = "UserID";							
						  		$records = $this->member_model->select_data($this->session->userdata('userid'),'PatientMaster',$listrows,'UserID','asc','UserID',false);					
						  		// Data Passed from Form
						  		$form_data = array(
						  			'FirstName' 		=> (string) $this->input->post('txtFirstName'),
						  			'LastName'  		=> (string) $this->input->post('txtLastName'),
						  			'MiddleInitial'		=> (string) $this->input->post('txtMiddleInitial'),
						  			'Suffix'			=> (string) $this->input->post('txtSuffix'),
						  			'DOBMM'				=> (string) $this->input->post('txtDOBMM'),
						  			'DOBDD'				=> (string) $this->input->post('txtDOBDD'),
						  			'DOBYYYY'			=> (string) $this->input->post('txtDOBYYYY'),
						  			'Gender'			=> (string) $this->input->post('selGender'),
						  			'BloodType1'		=> (string) $this->input->post('selBloodType1'),
						  			'BloodType2'		=> (string) $this->input->post('selBloodType2'),
						  		    'AddressLine1'		=> (string) $this->input->post('txtAddressLine1'),
						  			'AddressLine2'		=> (string) $this->input->post('txtAddressLine2'),
						  			'City'				=> (string) $this->input->post('txtCity'),
						  			'State'				=> (string) $this->input->post('txtState'),
						  			'Zip'				=> (string) $this->input->post('txtZip'),
						  			'DayPhone'			=> (string) $this->input->post('txtDayPhone'),
						  			'DayPhone2'			=> (string) $this->input->post('txtDayPhone2'),
						  			'DayPhone3'			=> (string) $this->input->post('txtDayPhone3'),
						  			'EveningPhone'		=> (string) $this->input->post('txtEveningPhone'),
						  			'EveningPhone2'		=> (string) $this->input->post('txtEveningPhone2'),
						  			'EveningPhone3'		=> (string) $this->input->post('txtEveningPhone3'),
						  			'CellPhone'			=> (string) $this->input->post('txtCellPhone'),
						  			'CellPhone2'		=> (string) $this->input->post('txtCellPhone2'),
						  			'CellPhone3'		=> (string) $this->input->post('txtCellPhone3'),
						  			'AltAddressLine1'	=> (string) $this->input->post('txtAltAddressLine1'),
						  			'AltAddressLine2'	=> (string) $this->input->post('txtAltAddressLine2'),
						  			'AltCity'			=> (string) $this->input->post('txtAltCity'),
						  			'AltState'			=> (string) $this->input->post('txtAltState'),
						  			'AltZip'			=> (string) $this->input->post('txtAltZip'),
						  			'AltDayPhone'		=> (string) $this->input->post('txtAltDayPhone'),
						  			'AltDayPhone2'		=> (string) $this->input->post('txtAltDayPhone2'),
						  			'AltDayPhone3'		=> (string) $this->input->post('txtAltDayPhone3'),
						  			'AltEveningPhone'	=> (string) $this->input->post('txtAltEveningPhone'),
						  			'AltEveningPhone2'	=> (string) $this->input->post('txtAltEveningPhone2'),
						  			'AltEveningPhone3'	=> (string) $this->input->post('txtAltEveningPhone3'),
						  			'EmailAddress'		=> (string) $this->input->post('txtEmailAddress')
						  		);
						  		if ($records['record_count'] > 0) {
						  			if ($this->member_model->edit_data($form_data,$this->session->userdata('userid'),'PatientMaster') === true) {
						  				  // Redirect back to the main Demographics Form
						  				  //redirect('/members/display/action/demographics', 'refresh');					  				
						  				  $this->output->set_header('refresh:1; url="/members/display/action/demographics"');
						  			}	
						  			else {
						  				  $data['message'] = "Could not Edit Data. Please check form and re-submit.";
				   						 // Load View
										 $this->load->view('utils/formloader', $data);						  				  
						  			}
						  		}
						  		else {
						  			if ($this->member_model->add_data($this->session->userdata('userid'),$form_data,'PatientMaster') === true) {
						  				  // Redirect back to the main Demographics Form
						  				  $this->output->set_header('refresh:1; url="/members/display/action/demographics"');				  				
						  			}	
						  			else {
						  				  $data['message'] = "Could not Add Data. Please check form and re-submit.";
				   						 // Load View
										 $this->load->view('utils/formloader', $data);						  				  
						  			}						  		
						 	    }
	   					  }
					break;
				case 'file':
						 /* Set Switch between Photos and Documents */
					    // Get File Upload Record
						$record = $this->member_model->check_files($this->session->userdata('userid'),$this->input->post('Type'));

						$numrows 		 = $record['numrows'];
						$data['caption'] = $record['caption'];
						$data['type']    = $record['type'];
											
						 switch ($this->input->post('Type')) {
						 	case 'doc':
						 		 /* Processing Variables */
						 		 	$extension_approved = array("doc","jpg","jpeg","pdf","png","txt");
						 		 	$redirect_page = "/members/display/action/documentslist";
						 		
								 /* General Configrations */
									$data['current_tab']   = "data";
									$data['help_content']  = "doc";
									$data['page_title']    = "Documents";
						
									$data['side_menu'] 	   = "menu_dataentry";
									$data['form']		   = "file";
									$data['formid']	       = "doc";
									$data['formname']      = "doc";
									$data['formtype']	   = "doc";
									$data['formcancel']	   = "/members/display/action/documents";	
									$data['formapprovedtypes'] = "DOC, JPG, PDF, PNG, or TXT";
									$data['formmethod']    = "/members/process/file";	
						 		
 									$data['submitlabel'] = "Add Document";
									$data['submitname']  = "add";									
						 		break;
						 	case 'photo':
						 		 /* Processing Variables */
						 		 	$extension_approved = array("jpg","jpeg","png");
						 		 	$redirect_page = "/members/display/action/photo";
						 		 						 		
								 /* General Configrations */
									$data['current_tab']   = "data";
									$data['help_content']  = "photo";
									$data['page_title']    = "Photo";
						
									$data['side_menu'] 	   = "menu_dataentry";
									$data['form']		   = "file";
									$data['formid']	       = "photo";
									$data['formname']      = "photo";
									$data['formtype']	   = "photo";	
									$data['formcancel']	   = "/members/display/action/photo";
									$data['formapprovedtypes'] = "JPEG or PNG";
									$data['formmethod']    = "/members/process/file";				
						 			
									if ($numrows > 0) {
										$data['submitlabel'] = "Replace Photo";
										$data['submitname']  = "delete";
									}
									else {
										$data['submitlabel'] = "Add Photo";
										$data['submitname']  = "add";
									}									
									
												 		
						 		break;
						 	default:
						 		echo 'No File Type Defined - ';
							 	break;
						 }
						
			        	// Validate Form input and check Image Parameters
        				$fileName  = $_FILES['file']['name'];
						$tmpName   = $_FILES['file']['tmp_name'];
						$fileSize  = $_FILES['file']['size'];
						$fileType  = $_FILES['file']['type'];
						$fileError = $_FILES['file']['error'];
						
						$data['message'] = "";

   						if (strlen($fileName) == 0) {
   							$data['message'] .= "Please select a file to upload.";
   							$content = "";
   						}
   						else {
							/* Process File */
							$fp        = fopen($tmpName, 'r');
							$content   = fread($fp, filesize($tmpName));
							$content   = addslashes($content);
   							fclose($fp);   							
							if(!get_magic_quotes_gpc()) {
				 			   $fileName = addslashes($fileName);
							} 
   						}

						if (strlen($this->input->post('Caption')) > 0) {
						   $fileCaption = $this->input->post('Caption');
						}
						else {
							$fileCaption = "Not Provided";
						}
			
						if ($fileError > 0) {
				 			$data['message'] .= "<br/>Please select a File before Uploading.";
						}
						elseif ($fileSize == 0) {
				 			$data['message'] .= "<br />File size cannot be zero bytes.";
						}
						elseif ($fileSize > $this->lang->line('site_max_file_size')) {
				 			$data['message'] .= "<br />File cannot exceed ".$this->lang->line('site_max_file_size')." bytes";
						}
						elseif (!in_array(end(explode(".",strtolower($fileName))),$extension_approved)) {
							$data['message'] .= "<br />Must upload correct file format: ".$data['formapprovedtypes'];
						}
						else {
			 	 			$data['message'] = "";				 
						}
						
						$form_data = array(
							'File' 		=> $content,
							'Caption'	=> (string) $fileCaption,
							'Size'		=> (int) $fileSize,
							'Name'		=> (string) $fileName,
							'Type'		=> (string) $this->input->post('Type'),
							'Ext'		=> (string) $fileType,
							'UserID'    => (int) $this->session->userdata('userid'),
							'FileDateAdded'  => date('Y-m-d H:i:s'),
						    'FileDateEdited' => date('Y-m-d H:i:s')			
						);						
						
						if ($data['message'] == "") {
							// Delete Photo if Replacing a Photo
							if ($this->input->post('Type') == "photo") {
								$this->member_model->delete_photo($this->session->userdata('userid'));
							}			
							if ($this->member_model->add_data($this->session->userdata('userid'),$form_data,'PatientFiles') === true) {
								// Delete Temporary Upload File
								unlink($tmpName);
						  		// Redirect back to the Photo or Document Forms
						  		$this->output->set_header("refresh:1; url=".$redirect_page);				  				
						  	}	
						  	else {
						  		 // Error Page
						  		 $this->load->view('utils/fileuploader', $data);	  
						  	}
						}
						else {
				 			// Error Page
				 			$this->load->view('utils/fileuploader', $data);	
						}						
						
					break;
				case 'immunizations':
						  /* General Configurations */
						  $data['current_tab']   = "data";
						  $data['page_title']    = "Immunizations";
						  $data['side_menu'] 	 = "menu_dataentry";
						  $data['form']		     = "immunizations";
						  $data['formname']      = "IMMUNIZATIONSSUBMIT";
						  $data['formid']        = "IMMUNIZATIONSSUBMIT";
						  $data['formmethod']    = "/members/process/immunizations";	
						  
						  $form_data = array(
						  		'ImmunizationsLookupSID' => (int) $this->input->post('ImmunizationsLookupSID'),
						  		'Status'				 => (int) $this->input->post('Status'),
						  		'Month'					 => (int) $this->input->post('Month'),
						  		'Year'					 => (int) $this->input->post('Year')
						  );
						  				
						  /* Process Form */
						  if ($this->member_model->edit_data($form_data,$this->session->userdata('userid'),'Immunizations') === true) {
						  		// Redirect back to the main Demographics Form
						  		$this->output->set_header('refresh:1; url="/members/display/action/immunizations"');					  				
						  }	
						  else {
						  		$data['message'] = "Could not Edit Data. Please check form and re-submit.";
				   			    // Load View
							    $this->load->view('utils/formloader', $data);						  				  
						  }		  
						  					
					break;	
				case 'socialhistory':
						  /* General Configurations */
						  $data['current_tab']   = "data";
						  $data['page_title']    = "Social History";
						  $data['side_menu'] 	 = "menu_dataentry";
						  $data['form']		     = "socialhistory";
						  $data['formname']      = "SOCIALHISTORYSUBMIT";
						  $data['formid']        = "SOCIALHISTORYSUBMIT";
						  $data['formmethod']    = "/members/process/socialhistory";	
						  									
						  /* Process Form */
						  $listrows = "UserID";
						  $records = $this->member_model->select_data($this->session->userdata('userid'),'SocialHistory',$listrows,'UserID','asc','UserID',false);					
						  // Data Passed from Form
						  		$form_data = array(
						  			'CigarettsUse' 			=> (string) $this->input->post('RdCigarettsUse'),
						  			'CigarettsPacksPerDay'  => (string) $this->input->post('selCigarettsPacksPerDay'),
						  			'CigarettsYearsUsed'	=> (string) $this->input->post('selCigarettsYearsUsed'),
						  			'CigarsUse'				=> (string) $this->input->post('RdCigarsUse'),
						  			'CigarsPerWeek'			=> (string) $this->input->post('selCigarsPerWeek'),
						  			'CigarsYearsUsed'		=> (string) $this->input->post('selCigarsYearsUsed'),
						  			'ChewingTobaccoUse'		=> (string) $this->input->post('RdChewingTobaccoUse'),
						  			'QTMM'					=> (string) $this->input->post('selQTMM'),
						  			'QTYYYY'				=> (string) $this->input->post('selQTYYYY'),
						  			'BeerUse'				=> (string) $this->input->post('RdBeerUse'),
						  		    'BeerPerWeek'			=> (string) $this->input->post('selBeerPerWeek'),
						  			'WineUse'				=> (string) $this->input->post('RdWineUse'),
						  			'WinePerWeek'			=> (string) $this->input->post('selWinePerWeek'),
						  			'LiquorUse'				=> (string) $this->input->post('RdLiquorUse'),
						  			'LiquorPerWeek'			=> (string) $this->input->post('selLiquorPerWeek'),
						  			'QuitAlcohol'			=> (string) $this->input->post('RdQuitAlcohol'),
						  			'QAMM'					=> (string) $this->input->post('selQAMM'),
						  			'QAYYYY'				=> (string) $this->input->post('selQAYYYY'),
						  			'DrugIV'				=> (string) $this->input->post('RdDrugIV'),
						  			'DrugMarijuana'			=> (string) $this->input->post('RdDrugMarijuana'),
						  			'DrugCocain'			=> (string) $this->input->post('RdDrugCocain'),
						  			'DrugOther'				=> (string) $this->input->post('RdDrugOther'),
						  			'DrugOtherDesc'			=> (string) $this->input->post('txtDrugOtherDesc'),
						  			'QuitDrug'				=> (string) $this->input->post('RdQuitDrug'),
						  			'QDMM'					=> (string) $this->input->post('selQDMM'),
						  			'QDYYYY'				=> (string) $this->input->post('selQDYYYY')
						  		);
						  		if ($records['record_count'] > 0) {
						  			if ($this->member_model->edit_data($form_data,$this->session->userdata('userid'),'SocialHistory') === true) {
						  				  // Redirect back to the main Demographics Form
						  				  $this->output->set_header('refresh:1; url="/members/display/action/socialhistory"');
						  			}	
						  			else {
						  				  $data['message'] = "Could not Edit Data. Please check form and re-submit.";
				   						 // Load View
										 $this->load->view('utils/formloader', $data);						  				  
						  			}
						  		}
						  		else {
						  			if ($this->member_model->add_data($this->session->userdata('userid'),$form_data,'SocialHistory') === true) {
						  				  // Redirect back to the main Demographics Form
						  				  $this->output->set_header('refresh:1; url="/members/display/action/socialhistory"');				  				
						  			}	
						  			else {
						  				  $data['message'] = "Could not Add Data. Please check form and re-submit.";
				   						 // Load View
										 $this->load->view('utils/formloader', $data);						  				  
						  			}						  		
						 	    }			
					break;	
				default:
						echo "No Object Defined - ";
					break;
			}
		}	
		else {
			 $data['current_tab']  = "members";
			 $data['page_title'] = "Members";
			 $data['message'] = 'You do not have permission to view this page. Please login.';
			 $this->load->view('utils/generator', $data);			
		}	
	}	
	
}

/* End of file members.php */
/* Location: ./system/application/controllers/members.php */