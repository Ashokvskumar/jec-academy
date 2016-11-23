<?php
class Seo_model extends CI_Model {
	
	public $Title;
    public $KeyWord;
    public $Description;
    public $SeoData;
    public $Img;
    public $Favicon;
    
        public function __construct()
        {
			parent::__construct();
			$this->Pvar=$this->config->item('Config');
            $this->SettingFolder=ltrim($this->config->item('Upload')['Settings'],'./');
            $this->Logo=base_url().$this->SettingFolder.$this->Pvar['Logo'];
            $this->Img=$this->Logo;
        }
		


public function Collect($Obj=NULL){
    
    $this->Title=$this->Pvar['SiteName'];
    $this->KeyWord=$this->Title;
    $this->Description=$this->Title;
                
    
	$this->Favicon=base_url().$this->SettingFolder.$this->Pvar['Favicon'];
        
    if(!empty($Obj->menu_name)){
        $this->Title=$Obj->menu_name.' | '.$this->Title;
    }
    
    if(!empty($Obj->menu_seo_title)){
        $this->Title=$Obj->menu_seo_title;
    }
    
    if(!empty($Obj->menu_seo_keyword)){
        $this->KeyWord=$Obj->menu_seo_keyword;
    }
    
    if(!empty($Obj->menu_seo_des)){
        $this->Description=$Obj->menu_seo_des;
    }
    
    if(!empty($Obj->menu_img)){
        if(is_file($this->config->item('Upload')['Page'].$Obj->menu_img)){
            $this->Img=base_url().ltrim($this->config->item('Upload')['Page'],'./').$Obj->menu_img;
        }
    }
    
    
}

function Generate(){
    if(empty($this->Title)){
        $this->Collect();
    }
    $this->DefaultSeo();
    $this->OpenGraph();
    $this->MicroData();
    $this->Twitter();
    return $this;
}
    
function DefaultSeo(){
    $this->SeoData.='
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name=viewport content="width=device-width, initial-scale=1, maximum-scale=1">
        
        <link rel="shortcut icon" href="'.$this->Favicon.'">  
        
        <base href="'.base_url().'">
        <title>'.$this->Title.'</title>
        <meta name="description" content="'.$this->Description.'">
        <meta name="keywords" content="'.$this->KeyWord.'">
        
        <meta name="robots" CONTENT="noodp">
        <meta name="rating" content="general">
        <meta name="YahooSeeker" CONTENT="all">
        <meta name="msnbot" CONTENT="all">
        <meta name="allow-search" content="yes">
        <meta name="distribution" content="global">
        <meta name="language" content="en_US">
        
        
        <link rel="shortlink" href="'.current_url().'">
        <link rel="canonical" href="'.current_url().'">';
        
         
        
        
}
    
function OpenGraph(){
    if(!empty($this->Pvar['FacebookPageId'])){
        $this->SeoData.='<meta property="fb:page_id" content="'.$this->$this->Pvar['FacebookPageId'].'">';
    };
    
    $this->SeoData.='
        <meta property="og:locale" content="en_US">
        <meta property="og:type" content="Website">
        <meta property="og:title" content="'.$this->Title.'">
        <meta property="og:description" content="'.$this->Description.'">
        <meta property="og:url" content="'.current_url().'">
        <meta property="og:site_name" content="'.$this->Pvar['SiteName'].'">
        <meta property="og:image" content="'.$this->Img.'">
        <meta property="og:image" content="'.$this->Logo.'">
    ';
    }
    
 function Twitter(){   
    $this->SeoData.='

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="'.$this->Pvar['SiteName'].'">
    <meta name="twitter:title" content="'.$this->Title.'">
    <meta name="twitter:description" content="'.$this->Description.'">';
     if(!empty($this->Pvar['TwitterId'])){
         $this->SeoData.='<meta name="twitter:creator" content="'.$this->Pvar['TwitterId'].'">';
     }
    $this->SeoData.='<meta name="twitter:image" content="'.$this->Img.'">';
    }
function MicroData(){
        $this->SeoData.='
        <meta itemprop="pageType" content="LocalBusiness">
        <meta itemprop="name" content="'.$this->Title.'">
        <meta itemprop="description" content="'.$this->Description.'">
        <meta itemprop="image" content="'.$this->Img.'">';
        
        $this->SeoData.='
            <script type="application/ld+json">{
                "@context":"http://schema.org",
                "@type":"WebSite",
                "url":"'.base_url().'",
                "name":"'.$this->Pvar['SiteName'].'"}
            </script>';
    
    }
}