<?php
class common_model extends CI_Model {
	
	public $Table;

        public function __construct()
        {
                $this->load->database();
				#$this->load->library('session');
                //$this->load->model('class_model');
                $this->load->helper('url_helper');
				$this->load->helper('date');
				
				$this->load->helper('form');
    			$this->load->library('form_validation');
				
				$this->load->library('TimeDate');
            
                $this->SetConfig();
                
            $this->Jointer=$this->config->item('Jointer');

        }
		
	function RunSql($Sql){
		return $this->db->query($Sql);
	}
	
	function ProcessPost($FormData){
		$Return = array();
			foreach($FormData as $CName => $Par){
				
			$Field=$FormData[$CName]['Control']["name"];
			$Label=$FormData[$CName]["Label"];
			
			
			if(!empty($FormData[$CName]['Rules'])){
				$Rules=$FormData[$CName]['Rules'];
				$this->form_validation->set_rules($Field, $Label, $Rules);
			};
			$Return['PostData'][$Field]=$this->input->post($Field);
			
			if(!empty($FormData[$CName]['UserFunction'])){
				if($FormData[$CName]['UserFunction']=='Date'){
					$Return['PostData'][$Field]=nice_date($Return['PostData'][$Field], 'Y-m-d');
				}
			}
			
			//$Return['PostmultiData'][$Field]=$this->input->post($Field);
		}
	$Return ['Validation']=$this->form_validation->run();
	return $Return;
	}
	

	public function GetRecord($SqlData = NULL,$Type=NULL,$DUBB=NULL)
	{
		
		/*$SqlData=array(
		'Col'=>'',
		'Table'=>'',
		'Join'=>'',
		'Where'=>'',
		'Order'=>'',
		'Group'=>'',
		'Limit'=>'',
		);*/
		
		if(!empty($SqlData['Col'])){
			$this->db->select($SqlData['Col']);
		}
		if(!empty($SqlData['Table'])){
			$this->db->from($SqlData['Table']);
		}else{
			$this->db->from($this->Table);
		}
		
				
		if(!empty($SqlData['Join'])){
			if(is_array($SqlData['Join'])){
				foreach($SqlData['Join'] as $JoinData){
					#print_r($JoinData);
				$this->db->join($JoinData[0],$JoinData[1],(!empty($JoinData[2])?$JoinData[2]:NULL));
				}
				
			}else{
				$SqlData['Join']=explode(',',$SqlData['Join']);
				$this->db->join($SqlData['Join'][0],$SqlData['Join'][1],(!empty($SqlData['Join'][2])?$SqlData['Join'][2]:NULL));
			}
		}

		
				
		if(!empty($SqlData['Where'])){
			if(is_array($SqlData['Where'])){
				$this->db->where($SqlData['Where']);
				#echo'array';
			}else{
				$SqlData['Where']=explode(',',$SqlData['Where']);
				$this->db->where($SqlData['Where'][0],$SqlData['Where'][1]);
			}
		}

		
		if(!empty($SqlData['Limit'])){
			$SqlData['Limit']=explode(',',$SqlData['Limit']);
			$this->db->limit($SqlData['Limit'][0],isset($SqlData['Limit'][1])?$SqlData['Limit'][1]:0);
		}
		
		if(!empty($SqlData['Order'])){
			$SqlData['Order']=explode(',',$SqlData['Order']);
			$this->db->order_by($SqlData['Order'][0],(!empty($SqlData['Order'][1])?$SqlData['Order'][1]:'ASC'));
		}

		
		
		if($DUBB){
			echo $this->db->get_compiled_select(NULL,FALSE);
		}
		
		if(empty($Type)){
			return $this->db->get()->result();
		}
		if($Type==='Count'){
			return $this->db->count_all_results();
		}
		if($Type==='Single'){
			return $this->db->get()->row();
		}
		if($Type==='Sql'){
			$this->db->get_compiled_select(NULL,TRUE);
		}

		
		return $query = $this->db->get($this->Table);
			if ($Where === FALSE)
			{
					return $query = $this->db->get($this->Table);
					//return $query->result();
					
			}
	
			return $this->db->get_where($this->Table, $Where);
			//print_r($query->result());
			//return $query->row();
	}
	
	public function InsertRecord($Data)
	{
		//$data=$this->FormData();
		$this->db->insert($this->Table, $Data);
		return $this->db->insert_id();
	}
	
	public function UpdateRecord($Data,$Where){
		//$data=$this->FormData();
		$this->db->where($Where);
		$this->db->update($this->Table, $Data);
	}

	public function DeleteRecord($Where){
		$this->db->where($Where);
   		$this->db->delete($this->Table); 
	}
    
    public function DeleteImages($ImgData,$SqlData){
        $OB=$this->GetRecord($SqlData,'Single');
        foreach($ImgData as $key => $val){
            $File=$val.$OB->$key;
                            
                if(strpos($OB->$key, $this->Jointer) !== false) {
                    $this->DeleteMultiImg($val,explode($this->Jointer,$OB->$key));
                }else{
                
                    if(is_file($File)){
                        unlink($File);
                    }
                }
        }
    }
    
    public function DeleteMultiImg($Path,$Data){
						  
							foreach($Data as $img){
										if(is_file($Path.$img)){
                                            unlink($Path.$img);
                                        }
                            }
    }
    
    public function SetConfig(){
        
        $Res=$this->GetRecord(array('Table'=>'siteconfig'));
            
            foreach($Res as $OB){
                $Config[$OB->conf_name]=$OB->conf_des;
            }
        
			 $this->config->set_item('Config',$Config);	
            
    }

}
