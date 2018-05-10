<?php
//Depends on LinkNavbar
namespace Materialize;
class NavbarMaterialize extends Navbar{	
	
	public function getHTML(){
		
		$htmlReturn = '
			<nav>
				<div class="nav-wrapper">
					<a href="index.php" class="brand-logo">'.$this->logo().'</a>
					<a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
					<ul class="right hide-on-med-and-down">';					
						//getLinks
						foreach($this->_links as $link){						
							$htmlReturn.= $link->getHtml();						
						}						
		$htmlReturn .='
					</ul>
					<ul class="side-nav" id="mobile-demo">';
					
						//getLinks
						foreach($this->_links as $link){						
							$htmlReturn.= $link->getHtml();						
						}
						
		$htmlReturn .='			
					</ul>
				</div>
		  </nav>			
		<script>
			$(".button-collapse").sideNav();
		</script>
		';	
		
		return $htmlReturn; 		
	}	
	
	public function addLink(LinkNavbar $link){		
		$this->_links[]=$link;		
	}	
	public function getLinks(){
	
		return $this->_links;
	}
	
}