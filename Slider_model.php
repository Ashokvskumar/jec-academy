<?php
class slider_model extends CI_Model {
	
    public $SliderData;
    public $Jointer;
    
        public function __construct()
        {
			parent::__construct();
			$this->Pvar=$this->config->item('Config');
            $this->SliderFolder=$this->config->item('Upload')['PageSlides'];
            $this->Jointer=$this->config->item('Jointer');
            
        }
		


public function Collect($Table,$Id,$Val){
   	
    
		
		$SQLData=array('Table'=>$Table,
                        'Where'=>array($Id=>$Val)
                            );
		$OB=$this->common_model->GetRecord($SQLData,'Single');
    
        if($OB->menu_slider_status==2){ #Status Inactive
            return false;
        }
    
        if(!empty($OB->menu_slider_img)){
			$SliderIMageData = explode($this->Jointer,trim($OB->menu_slider_img));
			foreach($SliderIMageData as $img){
				if(is_file($this->SliderFolder.$img)){
					$this->SliderData[]=array('Img'=>$this->SliderFolder.$img);
				}
            }
		
	   }
    
    if(empty($this->SliderData) && $OB->menu_id != 1){
		  $this->Collect($Table,$Id,'1');
    }
    
    #print_r($this->SliderData);
        
    }
    
public function OutHtml(){
    if(empty($this->SliderData)){
        return false;
    }
    
            
                
    $Str='<div id="promo-slider" class="slider flexslider">
       		<ul class="slides">';
             						
    foreach($this->SliderData as $Data){
        $Str.='<li><img src="'.$Data['Img'].'"></li>';
    }
    
    $Str.='</ul>
        </div>';
    
    return $Str;
}
}