<?php

namespace Materialize;

/**
 * Class CardPanel
 * @package Materialize
 */
class CardPanel extends Widget
{
    /**
     * @var string $bgColor
     */
    protected $bgColor = 'teal';

    /**
     * @var string $textColor
     */
    protected $textColor = 'white-text';

    /**
     * @var string $shade
     */
    protected $shade = '';

    /**
     * @var string $content
     */
    protected $content = '';

    /**
     * @var LinkNavbar $link
     */
    protected $link;

    /**
     * @return string
     */
    public function getHtml()
    {
        return '		   
		   <div class="square ' . $this->bgColor . ' ' . $this->shade . ' ">		  
		        <div class="card-content center">		
				    <div class="table ">
					    <div class="table-cell">				
						    <span class="' . $this->textColor . ' flow-text">' . $this->content() . '</span>			
					    </div>
				    </div>
			    </div>
		    </div>';
    }


    /**
     * @return string
     */
    public function content()
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function bgColor()
    {
        return $this->bgColor;
    }

    /**
     * @return string
     */
    public function textColor()
    {
        return $this->textColor;
    }

    /**
     * @return string
     */
    public function shade()
    {
        return $this->shade;
    }

    /**
     * @return LinkNavbar
     */
    public function link()
    {
        return $this->link;
    }

    /**
     * @param string $bgColor
     * @return CardPanel
     */
    public function setBgColor($bgColor)
    {
        if (is_string($bgColor) && !empty($bgColor)) {
            $this->bgColor = $bgColor;
        }

        return $this;
    }

    /**
     * @param string $textColor
     * @return CardPanel
     */
    public function setTextColor($textColor)
    {
        if (is_string($textColor) && !empty($textColor)) {
            $this->textColor = $textColor;
        }

        return $this;
    }

    /**
     * @param string $shade
     * @return CardPanel
     */
    public function setShade($shade)
    {
        if (is_string($shade) && !empty($shade)) {
            $this->shade = $shade;
        }

        return $this;
    }

    /**
     * @param string $content
     * @return CardPanel
     */
    public function setContent($content)
    {
        if (is_string($content) && !empty($content)) {
            $this->content = $content;
        }

        return $this;
    }

    /**
     * @param LinkNavBar $link
     * @return CardPanel
     */
    public function setLink(LinkNavBar $link)
    {
        $this->link = $link;

        return $this;
    }
}
