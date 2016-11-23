<?php
class multi_model extends CI_Model {
	
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

        }
		
		function ProcessPost($FormData){
			$this->MultData=array();
			//$DefaultCol='invent_in_multi_cat';
			
		foreach($FormData as $CName => $Par){
			$$CName=$this->input->post($CName);/*Get AllPost Data In Array*/
		}
		
			foreach($$CName as $key => $val){ /*foreach Last Col FOR COUNT COL*/
				$n=array();
					foreach($FormData as $CName => $Par){  /*Forach Array Col*/
					$n[$CName]=${$CName}[$key]; /*Store Data In Array*/
							
								if(!empty($FormData[$CName]['UserFunction'])){
									if($FormData[$CName]['UserFunction']=='Date'){ 
										$n[$Field]=nice_date($n[$Field], 'Y-m-d');	/*Change Date*/
									}
								}
					
								if(!empty($FormData[$CName]['Rules'])){ /*Check Valid*/
									if(empty($n[$CName])){
										unset($n);
									}
								}
					
					}
			
					if(!empty($n)){
						$this->MultData[]=$n;
					}
			}
	}
	
	/*function ProcessPost2($FormData){
		$this->MultData=array();
			
		$FormData['Col']=explode(',',$FormData['Col']);
		$FormData['Required']=explode(',',$FormData['Required']);
		
	
		$DefaultCol=$FormData['Col'][0];
			
		foreach( $_POST[$DefaultCol] as $key => $val) { 
				$n=array();
				foreach($FormData['Col'] as $p=>$Col){
					$n[$Col]=$_POST[$Col][$key];
				}
				
				foreach($FormData['Required'] as $p=>$Col){
					if(empty($n[$Col])){
						$n=array();
					}
				}
				
				if(!empty($n)){
					$this->MultData[]=$n;
				}
			}
	
		}*/
	public function AddCol($Data)
    {
		foreach($this->MultData as $key => $val){
			foreach($Data as $nkey => $nval){
				$this->MultData[$key][$nkey]=$nval;
			}
		}
    }

	public function InsertMultiRecord($Data,$DeleteWhere)
	{
		$this->DeleteMultiRecord($DeleteWhere);
		return $this->db->insert_batch($this->Table, $Data);
	}

	
	public function DeleteMultiRecord($Where){
		$this->db->where($Where);
   		$this->db->delete($this->Table); 
	}
	
	public function Js($CallBack=NULL){
		return "<script type='text/javascript'> 
$(document).ready(function(e) {
	
			//$('.clone_box .add').attr('href','javascript:void(0)').die();
			$(document).off('click keyup', '.clone_box .add')
		$('.clone_box .clone_row').addClass('clone_line').hide();
		
		$(document).on('click keyup', '.clone_box .add', function(event) {
			$(this).unbind('click');
			
			{$CallBack}

			var data =$(this).closest('.clone_box').find('.clone_row').clone(true,true).removeClass('clone_row').show();
			data.find( '.dt' ).removeClass('hasDatepicker').removeAttr('id').datepicker();
			
			$(this).closest('.clone_line').after(data);
			
			if($(this).closest('.clone_box').find('.add').length>2){
				//$(this).switchClass('add','delete',10);
			};
			
			$('.clone_box .add').removeClass('add').addClass('delete');
			$('.clone_box .delete:last').removeClass('delete').addClass('add');
			
			if($(this).closest('#fancybox-content')){	$.fancybox.update();	}
			
			//e.preventDefault();
			$(this).closest('.clone_line').next('.clone_line').find('input[type=text],textarea,select').filter(':first').focus();
			return false;
		})
		
		$(document).on('click', '.clone_box .delete', function(event) {
			$(this).closest('.clone_line').remove();
			{$CallBack}
			if($(this).closest('#fancybox-content')){	$.fancybox.update();	}
		});
		
	$('.clone_box .add').removeClass('add').addClass('delete');
	$('.clone_box .delete:last').removeClass('delete').addClass('add');
	$('.clone_box .add').click();
	

	
});
</script>";
	}

}