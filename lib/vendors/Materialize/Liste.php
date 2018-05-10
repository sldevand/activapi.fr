<?php
namespace Materialize;
class Liste extends Widget{	


	protected $titre;	
	protected $contenu;
	protected $idTitre;
	protected $coche=false;
	
	
	public function titre(){return $this->titre;}	
	public function contenu(){return $this->contenu;}	
	public function idTitre(){return $this->idTitre;}	
	public function coche(){return $this->coche;}
		  
	public function setTitre($titre){		
		$this->titre=$titre;
	}
	
	public function setContenu($contenu){
		$this->contenu=$contenu;
		
	}
	public function setisTitre($idTitre){
		$this->idTitre=$idTitre;		
	}
	
	public function setCoche($coche){	
		$coche = (bool) $coche;
		$this->coche=$coche;
	}
	
	public function getHtml(){
		return '<a href="index.php?idListe='.$this->_id.'">
					<li id="'.$this->_id.'" class="collection-header">					
						<h5>'.$this->titre().'</h5>					
					</li>
				</a>
					';
	}
	
	public function getItemHtml(){	
		
		if($this->coche){
			$decoration = 'line-through';
			$color = 'lightgray';	
		}else {
			$decoration = 'none';
			$color = 'black';				
		}
		
		return '<li id="'.$this->_id.'" class="collection-item" style="text-decoration:'.$decoration.';color:'.$color.';">					
					'.$this->contenu().'
					<a id="'.$this->_id.'"><i class="material-icons right">delete</i></a> 
				</li>';
	}	
}

	