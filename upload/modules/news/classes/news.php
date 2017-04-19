<?php

class news
{
	/**
     * @var Singleton The reference to *Singleton* instance of this class
     */
    private static $instance;
    
    public $display_details = false;
    public $displayed_news = 0;
    
    /**
	 *	Return the »internal« instance of the class
	 *
	 */
	public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        
        return static::$instance;
    }
}
?>