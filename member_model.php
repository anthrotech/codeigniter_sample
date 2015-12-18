<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Member_model extends Model 
{
	
	public function __construct()
    {
        parent::Model();
        $this->CI=&get_instance();
    }
    
    public function check_data($tablename,$columns,$keycolumn,$keyvalue) {
    	// Get the Permission
		$this->db->trans_start();
		$this->db->start_cache();
		$this->db->select($columns);
		$this->db->from($tablename);
		$this->db->where($keycolumn,$keyvalue);
		$this->db->stop_cache();
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			log_message('error', 'Could not process check_data in member_model.');
			$this->db->trans_rollback();
			return false;
		}
		else {
			$this->db->trans_commit();
			if ($this->db->count_all_results() > 0) {
					$result = $this->db->get()->result();
					$r = array();
					foreach($result as $row) {
						$type = $row->SpecialityType;	
	        			$return['type'] = $type;
					}
			}
			else {
				  $return['type'] = "";
			}
		}				
		$this->db->flush_cache();

						
		return $return;				
    }      

    public function check_files($userid,$filetype) {
		$this->db->trans_start();
		$this->db->start_cache();
		$this->db->select('SecurityMaster.UserName, SecurityMaster.SecurityTypeID, PatientMaster.UserID, PatientMaster.FirstName, PatientMaster.LastName, PatientFiles.FileID, PatientFiles.File, PatientFiles.Size, PatientFiles.Ext, PatientFiles.Type, PatientFiles.Name, PatientFiles.Caption');
		$this->db->from("SecurityMaster");
		$where = "SecurityMaster.UserID = ".$userid." AND PatientFiles.UserID = ".$userid." AND PatientFiles.Type = '".$filetype."' AND SecurityMaster.ActiveInd = 'A' AND SecurityMaster.SecurityTypeID = 1";
		$this->db->join('PatientMaster','SecurityMaster.UserID = PatientMaster.UserID','inner');
		$this->db->join('PatientFiles','SecurityMaster.UserID = PatientFiles.UserID','inner');
		$this->db->where($where);
		$this->db->stop_cache();
		$this->db->trans_complete();    	

		if ($this->db->trans_status() === FALSE) {
			log_message('error', 'Could not process check_files in member_model.');
			$this->db->trans_rollback();
			return false;
		}
		else {
			$this->db->trans_commit();
			$numrows = $this->db->count_all_results();
			$return['numrows'] = $numrows;				
			if ($this->db->count_all_results() > 0) {
					$result = $this->db->get()->result();
					$r = array();
					foreach($result as $row) {
						$caption = $row->Caption;	
						$type    = $row->Type;
						$file    = $row->File;
						$size    = $row->Size;
						$ext     = $row->Ext;
						$fileid  = $row->FileID;
						$name    = $row->Name;
	        			$return['caption'] = $caption;
	        			$return['type']    = $type;
	        			$return['file']    = $file;
	        			$return['ext']     = $ext;
	        			$return['size']    = $size;
	        			$return['fileid']  = $fileid;
	        			$return['name']    = $name;
  					}
			}
			else {
			 	$return['caption'] = "";
			 	$return['type']    = "";
	        	$return['file']    = "";
	        	$return['ext']     = "";
	        	$return['size']    = "";
	        	$return['fileid']  = "";			 	
	        	$return['name']    = "";
 			}
		}				
		$this->db->flush_cache();		
		
		return $return;	
    }    
    
    public function check_show_files($userid,$fileid,$filetype) {
		$this->db->trans_start();
		$this->db->start_cache();
		$this->db->select('SecurityMaster.UserName, SecurityMaster.SecurityTypeID, PatientMaster.UserID, PatientMaster.FirstName, PatientMaster.LastName, PatientFiles.FileID, PatientFiles.File, PatientFiles.Size, PatientFiles.Ext, PatientFiles.Type, PatientFiles.Name, PatientFiles.Caption');
		$this->db->from("SecurityMaster");
		$where = "SecurityMaster.UserID = ".$userid." AND PatientFiles.FileID = ".$fileid." AND PatientFiles.UserID = ".$userid." AND PatientFiles.Type = '".$filetype."' AND SecurityMaster.ActiveInd = 'A' AND SecurityMaster.SecurityTypeID = 1";
		$this->db->join('PatientMaster','SecurityMaster.UserID = PatientMaster.UserID','inner');
		$this->db->join('PatientFiles','SecurityMaster.UserID = PatientFiles.UserID','inner');
		$this->db->where($where);
		$this->db->stop_cache();
		$this->db->trans_complete();    	

		if ($this->db->trans_status() === FALSE) {
			log_message('error', 'Could not process check_files in member_model.');
			$this->db->trans_rollback();
			return false;
		}
		else {
			$this->db->trans_commit();
			$numrows = $this->db->count_all_results();
			$return['numrows'] = $numrows;				
			if ($this->db->count_all_results() > 0) {
					$result = $this->db->get()->result();
					$r = array();
					foreach($result as $row) {
						$caption = $row->Caption;	
						$type    = $row->Type;
						$file    = $row->File;
						$size    = $row->Size;
						$ext     = $row->Ext;
						$fileid  = $row->FileID;
						$name    = $row->Name;
	        			$return['caption'] = $caption;
	        			$return['type']    = $type;
	        			$return['file']    = $file;
	        			$return['ext']     = $ext;
	        			$return['size']    = $size;
	        			$return['fileid']  = $fileid;
	        			$return['name']    = $name;
  					}
			}
			else {
			 	$return['caption'] = "";
			 	$return['type']    = "";
	        	$return['file']    = "";
	        	$return['ext']     = "";
	        	$return['size']    = "";
	        	$return['fileid']  = "";			 	
	        	$return['name']    = "";
 			}
		}				
		$this->db->flush_cache();		
		
		return $return;	
    }
    
	public function delete_doc($userid,$fileid)  
	{
		   	// Delete from Table
			$this->db->trans_start();
			$this->db->start_cache();	
					
			$where = "FileID = ".$fileid." AND UserID = ".$userid;
			$this->db->where($where);
			$this->db->delete("PatientFiles"); 
			$this->db->stop_cache();
			$this->db->trans_complete();
			
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				log_message('error', 'Could not process delete_files in member_model. No action passed.');
				return false;
			}
			else {
				$this->db->trans_commit();
				return true;			
			}			
			$this->db->flush_cache();
	}        
    
	public function delete_photo($userid)  
	{
		   	// Delete from Table
			$this->db->trans_start();
			$this->db->start_cache();	
					
			$where = "UserID = ".$userid." AND Type = 'photo'";
			$this->db->where($where);
			$this->db->delete("PatientFiles"); 
			$this->db->stop_cache();
			$this->db->trans_complete();
			
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				log_message('error', 'Could not process delete_files in member_model. No action passed.');
				return false;
			}
			else {
				$this->db->trans_commit();
				return true;			
			}			
			$this->db->flush_cache();
	}    
    
    
    public function check_permissions($userid,$nppvtype) {
    	// Get the Permission
		$this->db->trans_start();
		$this->db->start_cache();
		$this->db->select('NPPV');
		$this->db->from('PatientNPPVMaster');
		$where = "UserID = ".$userid." AND NPPVType = '".$nppvtype."'";
		$this->db->where($where);
		$this->db->stop_cache();
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			log_message('error', 'Could not process check_promotions in member_model.');
			$this->db->trans_rollback();
			return false;
		}
		else {
			$this->db->trans_commit();		
			if ($this->db->count_all_results() > 0) {
					$result = $this->db->get()->result();
					$r = array(); 
					foreach($result as $row) {
						$nppv = $row->NPPV;	
	        			$return['nppv'] = $nppv;
					}
			}
			else {
				  $return['nppv'] = 0;
			}
		}				
		$this->db->flush_cache();
		
		if ($return['nppv'] == "1") {
			$return['perm'] = '<img src="/images/locked.gif" title="locked" alt="locked" width="21" height="21" border="0" />';
		}
		else {
			$return['perm'] = '<img src="/images/unlocked.gif" title="locked" alt="locked" width="21" height="21" border="0" />';
		}
						
		return $return;				
    }   
    
	public function get_all_data($userid,$tablename) {
		 $this->db->trans_start();
		 $this->db->start_cache();		
		 $this->db->where('UserID', $userid);
		 $result=$this->db->get($tablename);
		 $this->db->stop_cache();
	 	 $this->db->trans_complete();
	 	 	
		 if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				log_message('error', 'Could not process get_all_data in member_model. No action passed.');
				return false;
		 }
		 else {				
				$this->db->trans_commit();
				if ($this->db->count_all_results() > 0) {
					return $result;
					return true;
				}
				else{
					log_message('error', 'Could not process get_all_data in member_model.');
					return false;			
				}						
				$this->db->flush_cache();
		 }	 	 	 
	}    	
	
	public function get_immunization_data($userid,$lookupvalue) {
		 $this->db->trans_start();
		 $this->db->start_cache();	
		 $this->db->select('*');
		 $this->db->from('Immunizations');
	 	 $where = "UserID = '".$userid."' AND ImmunizationsLookupSID = ".$lookupvalue;
		 $this->db->where($where);
		 $this->db->stop_cache();
	 	 $this->db->trans_complete();
	 	 	
		 if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				log_message('error', 'Could not process get_immunization_data in member_model. No action passed.');
				return false;
		 }
		 else {				
			$this->db->trans_commit();
			if ($this->db->count_all_results() > 0) {
					$result = $this->db->get()->result();
					$r = array();
					foreach($result as $row) {
						$immid 				  = $row->ImmunizationsLookupSID;
						$status 		 	  = $row->Status;
						$month 			      = $row->Month;
						$year				  = $row->Year;	

						$return['immunizationid'] = $immid;
						$return['status']		  = $status;
						$return['month']		  = $month;
						$return['year']			  = $year;
					}
			}	
			else {
				 $return['immunizationid'] = "";
				 $return['status'] 		   = "";
				 $return['month']          = "";
				 $return['year']           = "";
			}
			$this->db->flush_cache();
			
			return $return;
		 }	 	 	 
	}    	
	

	public function get_lookup_data($tablename,$sortcolumn, $sortorder) {
		 $this->db->order_by($sortcolumn, $sortorder);
		 $result=$this->db->get($tablename);
	 	 if ($this->db->count_all_results() > 0) {
					return $result;
					return true;
		 }
		 else{
			   log_message('error', 'Could not process get_lookup_data in member_model.');
			   return false;			
		 }	 	 	 
	}    
	
	public function get_dropdown_data($tablename,$sortcolumn, $sortorder) {
		 $this->db->order_by($sortcolumn, $sortorder);
		 $result=$this->db->get($tablename);
	 	 if ($this->db->count_all_results() > 0) {
					return $result->result();
					return true;
		 }
		 else{
			   log_message('error', 'Could not process get_dropdown_data in member_model.');
			   return null;
			   return false;			
		 }	 	 	 
	}    
		
	
	public function select_data($userid,$table_name,$listofrows,$sortcolumn,$sortorder,$keycolumn,$useflexigrid = true) 
	{
		
		//Build contents query
		$this->db->trans_start();
		$this->db->start_cache();		
		$this->db->select($listofrows)->from($table_name);
		$this->db->where('UserID', $userid);
		if ($useflexigrid === true) {
			$this->CI->flexigrid->build_query();
		}
		$this->db->stop_cache();
		$this->db->trans_complete();	
		
		if ($this->db->trans_status() === FALSE) {
			log_message('error', 'Could not process select_data in member_model. Initial '.$table_name.' Query Failed.');
			$this->db->trans_rollback();
			return false;
		}
		else {
			$this->db->trans_commit();
			if ($this->db->count_all_results() > 0) {			
				//Get contents
				$return['records'] = $this->db->get();
		
				//Build count query
				$this->db->trans_start();
				$this->db->start_cache();					
				$this->db->select('count('.$keycolumn.') as record_count')->from($table_name);
				$this->db->where('UserID', $userid);
				if ($useflexigrid === true) {
					$this->CI->flexigrid->build_query(FALSE);
				}
				$this->db->stop_cache();
				$this->db->trans_complete();				

				if ($this->db->trans_status() === FALSE) {
					log_message('error', 'Could not process select_data in member_model. '.$table_name.' Count Query Failed.');
					$this->db->trans_rollback();
					return false;
				}
				else {
					$this->db->trans_commit();
					if ($this->db->count_all_results() > 0) {	
						//Get Record Count
						$record_count = $this->db->get();
						$row = $record_count->row();
		
						// Return Count
						$return['record_count'] = $row->record_count;
	
						//Return all
						return $return;
					}
					$this->db->flush_cache();
				}
			}
		}
	}	
	
	
	public function select_doc_data($userid,$table_name,$listofrows,$sortcolumn,$sortorder,$keycolumn) 
	{
		
		//Build contents query
		$this->db->trans_start();
		$this->db->start_cache();		
		$this->db->select($listofrows)->from($table_name);
		$where = "Type = 'doc' AND UserID = ".$userid;
		$this->db->where($where);
		$this->db->order_by($sortcolumn, $sortorder); 
		$this->db->stop_cache();
		$this->db->trans_complete();	
		
		if ($this->db->trans_status() === FALSE) {
			log_message('error', 'Could not process select_data in member_model. Initial '.$table_name.' Query Failed.');
			$this->db->trans_rollback();
			return false;
		}
		else {
			$this->db->trans_commit();
			if ($this->db->count_all_results() > 0) {			
				//Get contents
				$return['records'] = $this->db->get();
		
				//Build count query
				$this->db->trans_start();
				$this->db->start_cache();					
				$this->db->select('count('.$keycolumn.') as record_count')->from($table_name);
				$this->db->where('UserID', $userid);
				$this->db->stop_cache();
				$this->db->trans_complete();				

				if ($this->db->trans_status() === FALSE) {
					log_message('error', 'Could not process select_data in member_model. '.$table_name.' Count Query Failed.');
					$this->db->trans_rollback();
					return false;
				}
				else {
					$this->db->trans_commit();
					if ($this->db->count_all_results() > 0) {	
						//Get Record Count
						$record_count = $this->db->get();
						$row = $record_count->row();
		
						// Return Count
						$return['record_count'] = $row->record_count;
	
						//Return all
						return $return;
					}
					$this->db->flush_cache();
				}
			}
		}
	}		

	public function add_data($userid,$data,$table_name) 
	{
		   	// Insert into table
			$this->db->trans_start();
			$this->db->start_cache();	
					
			$this->db->insert($table_name, $data); 			
			$this->db->stop_cache();
			$this->db->trans_complete();
			
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				log_message('error', 'Could not process add_data in member_model. No action passed.');
				return false;
			}
			else {				
				$this->db->trans_commit();
				return true;	
			}		
			$this->db->flush_cache();		
	}	

	public function delete_data($ids,$keycolumn,$table_name)  
	{
		   	// Delete from Table
			$this->db->trans_start();
			$this->db->start_cache();	
					
			$where = $keycolumn." IN (".$ids.")";
			$this->db->where($where);
			$this->db->delete($table_name); 
			$this->db->stop_cache();
			$this->db->trans_complete();
			
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				log_message('error', 'Could not process delete_data in member_model. No action passed.');
				return false;
			}
			else {
				$this->db->trans_commit();
				return true;			
			}			
			$this->db->flush_cache();
	}		

	public function edit_data($data,$userid,$table_name) 
	{
		   	// Edit Table
		   	$this->db->trans_start();
			$this->db->start_cache();
			switch($table_name) {
					case 'AdminProfile':
						for ($i=0;$i<count($_POST['profileID']);$i++){
							$id =  mysql_real_escape_string($_POST['profileID'][$i]);
							$reminder = mysql_real_escape_string($_POST['renewalReminderDayRange'][$i]);
							$email = mysql_real_escape_string($_POST['renewalEmailReminderDayRange'][$i]);	
							$sql = "UPDATE $table_name 
							SET renewalReminderDayRange = '$reminder', 
							renewalEmailReminderDayRange = '$email'
							WHERE profileID = $id";
							$this->db->query($sql);
						}						
						break;					
					case 'Allergies':
						for ($i=0;$i<count($_POST['AllergiesSID']);$i++){
							$id =  mysql_real_escape_string($_POST['AllergiesSID'][$i]);
							$medication = mysql_real_escape_string($_POST['Medication'][$i]);
							$allergicreaction = mysql_real_escape_string($_POST['AllergicReaction'][$i]);	
							$sql = "UPDATE $table_name 
							SET Medication = '$medication', 
							AllergicReaction = '$allergicreaction' 
							WHERE AllergiesSID = $id";
							$this->db->query($sql);
						}
						break;
					case 'EmergencyContacts':
						for ($i=0;$i<count($_POST['EmergencyContactsSID']);$i++){
							$id =  mysql_real_escape_string($_POST['EmergencyContactsSID'][$i]);
							$name = mysql_real_escape_string($_POST['Name'][$i]);
							$phone = mysql_real_escape_string($_POST['Phone'][$i]);	
							$sql = "UPDATE $table_name 
							SET Name = '$name', 
							Phone = '$phone' 
							WHERE EmergencyContactsSID = $id";
							$this->db->query($sql);
						}
						break;				
					case 'FamilyHistory':	
						for ($i=0;$i<count($_POST['FamilyHistorySID']);$i++){
							$id =  mysql_real_escape_string($_POST['FamilyHistorySID'][$i]);
							$familymember = mysql_real_escape_string($_POST['FamilyMember'][$i]);
							$medicalcondition = mysql_real_escape_string($_POST['MedicalCondition'][$i]);	
							$livingdeceased = mysql_real_escape_string($_POST['LivingDeceased'][$i]);
							$age = mysql_real_escape_string($_POST['Age'][$i]);
							$dobmm = mysql_real_escape_string($_POST['DOBMM'][$i]);
							$dobdd = mysql_real_escape_string($_POST['DOBDD'][$i]);
							$dobyyyy = mysql_real_escape_string($_POST['DOBYYYY'][$i]);
							$sql = "UPDATE $table_name 
							SET FamilyMember = '$familymember', 
							MedicalCondition = '$medicalcondition', 
							LivingDeceased = '$livingdeceased', 
							Age = $age, 
							DOBMM = '$dobmm', 
							DOBDD = '$dobdd', 
							DOBYYYY = '$dobyyyy' 
							WHERE FamilyHistorySID = $id";
							$this->db->query($sql);
						}						
						break;		
					case 'Hospitalizations':
						for ($i=0;$i<count($_POST['HospitalizationsSID']);$i++){
							$id =  mysql_real_escape_string($_POST['HospitalizationsSID'][$i]);
							$hospitalizationcondition = mysql_real_escape_string($_POST['HospitalizationCondition'][$i]);
							$hospitalizationsmonth = mysql_real_escape_string($_POST['HospitalizationsMonth'][$i]);	
							$hospitalizationsyear = mysql_real_escape_string($_POST['HospitalizationsYear'][$i]);
							$admittingphysician = mysql_real_escape_string($_POST['AdmittingPhysician'][$i]);
							$hospital = mysql_real_escape_string($_POST['Hospital'][$i]);
							$city = mysql_real_escape_string($_POST['City'][$i]);
							$state = mysql_real_escape_string($_POST['State'][$i]);
							$sql = "UPDATE $table_name 
							SET HospitalizationCondition = '$hospitalizationcondition', 
							HospitalizationsMonth = '$hospitalizationsmonth', 
							HospitalizationsYear = '$hospitalizationsyear', 
							AdmittingPhysician = '$admittingphysician', 
							Hospital = '$hospital', 
							City = '$city', 
							State = '$state'
							WHERE HospitalizationsSID = $id";
							$this->db->query($sql);
						}						
						break;	
					case 'Immunizations':
						foreach ($_POST['ImmunizationsLookupSID'] as $num => $val) {
							$id = $val; 
							if (isset($_POST['Status'][$val])) {
								$status = mysql_real_escape_string($_POST['Status'][$val]);
							}
							else {
								$status = "0";
							}
							if (strlen($_POST['Month'][$val]) > 0) {
								$month = mysql_real_escape_string($_POST['Month'][$val]);
							}
							else {
								$month = "00";
							}
							if (strlen($_POST['Year'][$val]) > 0) {
								$year = mysql_real_escape_string($_POST['Year'][$val]);
							}
							else {
								$year = "0000";
							}
							$values[] = "($id,$userid,$status,$month,$year)";
						 }
						 $delete_sql = "DELETE FROM $table_name WHERE UserID = $userid";
						 $this->db->query($delete_sql);
						 $insert_sql = "INSERT INTO $table_name (ImmunizationsLookupSID,UserID,Status,Month,Year) VALUES ".implode(',', $values);
						 $this->db->query($insert_sql);						
						break;							
					case 'MedicalConditions':
						for ($i=0;$i<count($_POST['MedicalConditionsSID']);$i++){
							$id =  mysql_real_escape_string($_POST['MedicalConditionsSID'][$i]);
							$medicalcondition = mysql_real_escape_string($_POST['MedicalCondition'][$i]);
							$diagnosedmonth = mysql_real_escape_string($_POST['DiagnosedMonth'][$i]);	
							$diagnosedyear = mysql_real_escape_string($_POST['DiagnosedYear'][$i]);
							$physiciantreating = mysql_real_escape_string($_POST['PhysicianTreating'][$i]);
							$sql = "UPDATE $table_name 
							SET MedicalCondition = '$medicalcondition', 
							DiagnosedMonth = '$diagnosedmonth', 
							DiagnosedYear = '$diagnosedyear', 
							PhysicianTreating = '$physiciantreating'
							WHERE MedicalConditionsSID = $id";
							$this->db->query($sql);
						}						
						break;	
					case 'OCMedications':
						for ($i=0;$i<count($_POST['MedicationsSID']);$i++){
							$id =  mysql_real_escape_string($_POST['MedicationsSID'][$i]);
							$medication = mysql_real_escape_string($_POST['Medication'][$i]);
							$medicationmonth = mysql_real_escape_string($_POST['MedicationMonth'][$i]);	
							$medicationyear = mysql_real_escape_string($_POST['MedicationYear'][$i]);
							$dose = mysql_real_escape_string($_POST['Dose'][$i]);
							$frequency =  mysql_real_escape_string($_POST['Frequency'][$i]);
							$medicalcondition = mysql_real_escape_string($_POST['MedicalCondition'][$i]);
							$sql = "UPDATE $table_name 
							SET Medication = '$medication', 
							MedicationMonth = '$medicationmonth', 
							MedicationYear = '$medicationyear', 
							Dose = '$dose',
							Frequency = '$frequency',
							MedicalCondition = '$medicalcondition'
							WHERE MedicationsSID = $id";
							$this->db->query($sql);
						}						
						break;									
					case 'physicianNotes':
						for ($i=0;$i<count($_POST['NoteSID']);$i++){
							$id =  mysql_real_escape_string($_POST['NoteSID'][$i]);
							$physicianname = mysql_real_escape_string($_POST['PhysicianName'][$i]);
							$notes = mysql_real_escape_string($_POST['Notes'][$i]);	
							$summary = mysql_real_escape_string($_POST['Summary'][$i]);
							$sql = "UPDATE $table_name 
							SET PhysicianName = '$physicianname', 
							Notes = '$notes', 
							Summary = '$summary'
							WHERE NoteSID = $id";
							$this->db->query($sql);
						}						
						break;				
					case 'PrescribedMedications':
						for ($i=0;$i<count($_POST['MedicationsSID']);$i++){
							$id =  mysql_real_escape_string($_POST['MedicationsSID'][$i]);
							$medication = mysql_real_escape_string($_POST['Medication'][$i]);
							$medicationmonth = mysql_real_escape_string($_POST['MedicationMonth'][$i]);	
							$medicationyear = mysql_real_escape_string($_POST['MedicationYear'][$i]);
							$dose = mysql_real_escape_string($_POST['Dose'][$i]);
							$frequency =  mysql_real_escape_string($_POST['Frequency'][$i]);
							$prescribingdr = mysql_real_escape_string($_POST['PrescribingDr'][$i]);
							$pharmacy = mysql_real_escape_string($_POST['Pharmacy'][$i]);
							$pharmacyphone = mysql_real_escape_string($_POST['PharmacyPhone'][$i]);
							$sql = "UPDATE $table_name 
							SET Medication = '$medication', 
							MedicationMonth = '$medicationmonth', 
							MedicationYear = '$medicationyear', 
							Dose = '$dose',
							Frequency = '$frequency',
							PrescribingDr = '$prescribingdr',
							Pharmacy = '$pharmacy',
							PharmacyPhone = '$pharmacyphone'
							WHERE MedicationsSID = $id";
							$this->db->query($sql);
						}						
						break;		
					case 'PrimaryPhysician':
						for ($i=0;$i<count($_POST['PrimaryPhysicianSID']);$i++){
							$id =  mysql_real_escape_string($_POST['PrimaryPhysicianSID'][$i]);
							$firstname = mysql_real_escape_string($_POST['FirstName'][$i]);
							$lastname = mysql_real_escape_string($_POST['LastName'][$i]);	
							$addressline1 = mysql_real_escape_string($_POST['AddressLine1'][$i]);
							$addressline2 = mysql_real_escape_string($_POST['AddressLine2'][$i]);
							$city =  mysql_real_escape_string($_POST['City'][$i]);
							$state = mysql_real_escape_string($_POST['State'][$i]);
							$zip = mysql_real_escape_string($_POST['Zip'][$i]);
							$email = mysql_real_escape_string($_POST['EmailAddress'][$i]);
							$phone = mysql_real_escape_string($_POST['Phone'][$i]);
							$fax = mysql_real_escape_string($_POST['Fax'][$i]);
							$sql = "UPDATE $table_name 
							SET FirstName = '$firstname', 
							LastName = '$lastname', 
							AddressLine1 = '$addressline1', 
							AddressLine2 = '$addressline2',
							City = '$city',
							State = '$state',
							Zip = '$zip',
							EmailAddress = '$email',
							Phone = '$phone',
							Fax   = '$fax'
							WHERE PrimaryPhysicianSID = $id";
							$this->db->query($sql);
						}						
						break;	
					case 'Promotions':
						for ($i=0;$i<count($_POST['PromotionID']);$i++){
							$id =  mysql_real_escape_string($_POST['PromotionID'][$i]);
							$contact = mysql_real_escape_string($_POST['Contact'][$i]);
							$email = mysql_real_escape_string($_POST['emailAddress'][$i]);	
							$keyword = mysql_real_escape_string($_POST['Keyword'][$i]);
							$startdate = mysql_real_escape_string($_POST['StartDate'][$i]);
							$enddate =  mysql_real_escape_string($_POST['EndDate'][$i]);
							$maxusage = mysql_real_escape_string($_POST['MaximumUsage'][$i]);
							$amount = mysql_real_escape_string($_POST['Amount'][$i]);
							$percentage = mysql_real_escape_string($_POST['Percentage'][$i]);
							$active = mysql_real_escape_string($_POST['Active'][$i]);
							$type = mysql_real_escape_string($_POST['Type'][$i]);
							$sql = "UPDATE $table_name 
							SET Contact = '$contact', 
							emailAddress = '$email', 
							StartDate = '$startdate', 
							EndDate   = '$enddate',
							MaximumUsage = '$maxusage',
							Amount = '$amount',
							Percentage = '$percentage',
							Active = '$active',
							Type = '$type'
							WHERE PromotionID = $id";
							$this->db->query($sql);
						}						
						break;							
					case 'SpecialtyPhysician':
						for ($i=0;$i<count($_POST['SpecialtyPhysicianSID']);$i++){
							$id =  mysql_real_escape_string($_POST['SpecialtyPhysicianSID'][$i]);
							$specialityid = mysql_real_escape_string($_POST['SpecialityID'][$i]);
							$firstname = mysql_real_escape_string($_POST['FirstName'][$i]);
							$lastname = mysql_real_escape_string($_POST['LastName'][$i]);	
							$addressline1 = mysql_real_escape_string($_POST['AddressLine1'][$i]);
							$addressline2 = mysql_real_escape_string($_POST['AddressLine2'][$i]);
							$city =  mysql_real_escape_string($_POST['City'][$i]);
							$state = mysql_real_escape_string($_POST['State'][$i]);
							$zip = mysql_real_escape_string($_POST['Zip'][$i]);
							$email = mysql_real_escape_string($_POST['EmailAddress'][$i]);
							$phone = mysql_real_escape_string($_POST['Phone'][$i]);
							$fax = mysql_real_escape_string($_POST['Fax'][$i]);
							$sql = "UPDATE $table_name 
							SET SpecialityID = $specialityid,
							FirstName = '$firstname', 
							LastName = '$lastname', 
							AddressLine1 = '$addressline1', 
							AddressLine2 = '$addressline2',
							City = '$city',
							State = '$state',
							Zip = '$zip',
							EmailAddress = '$email',
							Phone = '$phone',
							Fax   = '$fax'
							WHERE SpecialtyPhysicianSID = $id";
							$this->db->query($sql);
						}						
						break;													
					case 'Surgeries':
						for ($i=0;$i<count($_POST['SurgeriesSID']);$i++){
							$id =  mysql_real_escape_string($_POST['SurgeriesSID'][$i]);
							$surgericalprocedure = mysql_real_escape_string($_POST['SurgicalProcedure'][$i]);
							$surgeriesmonth = mysql_real_escape_string($_POST['SurgeriesMonth'][$i]);	
							$surgeriesyear = mysql_real_escape_string($_POST['SurgeriesYear'][$i]);
							$surgeon = mysql_real_escape_string($_POST['Surgeon'][$i]);
							$hospital =  mysql_real_escape_string($_POST['Hospital'][$i]);
							$city = mysql_real_escape_string($_POST['City'][$i]);
							$state = mysql_real_escape_string($_POST['State'][$i]);
							$sql = "UPDATE $table_name 
							SET SurgicalProcedure = '$surgericalprocedure', 
							SurgeriesMonth = '$surgeriesmonth', 
							SurgeriesYear = '$surgeriesyear', 
							Surgeon = '$surgeon',
							Hospital = '$hospital',
							City = '$city',
							State = '$state'
							WHERE SurgeriesSID = $id";
							$this->db->query($sql);
						}						
						break;																														
					default:
						$this->db->where('UserID',$this->session->userdata('userid'));
						$this->db->update($table_name, $data);
						break;
			}	
			$this->db->stop_cache();
			$this->db->trans_complete();
			
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				log_message('error', 'Could not process edit_data in member_model. No action passed.');
				return false;
			}
			else {
				$this->db->trans_commit();
				return true;
			}		
			$this->db->flush_cache();				
			
	}		

	public function toggle_data($ids,$action,$colname,$keycolumn,$table_name) 
	{
		
		// Set Action
		if (strlen($action) > 0) {
			switch($action) {
				case 'enable':
					 	$value = "1";
					break;
				case 'disable':
						$value = "0";
					break;
				default:
					log_message('error', 'Could not process toggle_data in member_model. No action passed.');
					return false;
					break;
			}
		}
		else {
			log_message('error', 'Could not process toggle_data in member_model. No action passed.');
			return false;
		}
		
		$data = array(
               $colname => $value
            );

		$this->db->trans_start();
		$this->db->start_cache();
		$where = $keycolumn." IN (".$ids.")";
		$this->db->where($where);
		$this->db->update($table_name, $data);
		$this->db->stop_cache();
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			log_message('error', 'Could not process toggle_data in member_model. UPDATE '.$table_name.':'.$colname.' Query Failed.');
			$this->db->trans_rollback();
			return false;
		}
		else {
			$this->db->trans_commit();
			return true;
		}		
		$this->db->flush_cache();
	}	
    
}

/* End of file member_model.php */
/* Location: ./system/application/models/member_model.php */
?>