<?php namespace Tlr\Menu;

use ArrayAccess;

/**
 * $key = 'Home';
 * $properties = [
 * 	'title' => 'Menu Title', // defaults to $key
 * 	'link' => 'http://boobs.com',
 * 	'class' => 'woop',
 * ];
 * $menu->addItem( $key, $properties )
 */
class MenuItem implements ArrayAccess {

	/**
	 * Is the current item active
	 * @var boolean
	 */
	protected $active;

	/**
	 * Submenu Items
	 * @var array
	 */
	protected $items = array();

	/**
	 * Render Properties
	 * @var array
	 */
	protected $properties = array();

	/**
	 * Element Attributes
	 * @var array
	 */
	protected $attributes = array();

	public function __construct( $properties = array() ) {
		$this->properties = $properties;
	}

	/**
	 * Get the sub menu items
	 * @author Stef Horner       (shorner@wearearchitect.com)
	 * @return array
	 */
	public function getItems() {
		return $this->items;
	}

	/**
	 * Get the given item, creating a new one if it doesn't exist
	 * @author Stef Horner     (shorner@wearearchitect.com)
	 * @param  string   $key
	 * @param  array    $properties
	 * @return Tlr\Menu\MenuItem
	 */
	public function item( $key, $properties = array() ) {
		if ( isset( $this->items[ $key ] ) )
			return $this->items[ $key ];

		return $this->addItem( $key, $properties );
	}

	/**
	 * Get the items attributes for HTML rendering
	 * @author Stef Horner       (shorner@wearearchitect.com)
	 * @return array
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}

	/**
	 * Batch set the item's attributes
	 * @author Stef Horner     (shorner@wearearchitect.com)
	 * @param  array   $attributes
	 * @param  boolean  $merge whether or not to merge the arrays
	 * @return $this
	 */
	public function setAttributes( $attributes, $merge = false )
	{
		$this->attributes = ( $merge ? array_merge($this->attributes, (array) $attributes) : (array) $attributes );
		return $this;
	}

	/**
	 * Set an individual attribute
	 * @author Stef Horner (shorner@wearearchitect.com)
	 * @param  string   $key
	 * @param  mixed   $value
	 */
	public function addAttribute( $key, $value )
	{
		$this->attributes[ $key ] = $value;

		return $this;
	}

	/**
	 * Get the element's rendering properties
	 * @author Stef Horner       (shorner@wearearchitect.com)
	 * @return array
	 */
	public function getProperties( ) {
		return $this->properties;
	}

	/**
	 * Create a new item and add it as a sub item, overwriting any
	 * that already exist
	 * @author Stef Horner     (shorner@wearearchitect.com)
	 * @param  string   $key
	 * @param  array    $properties
	 */
	public function addItem( $key, $properties = array() ) {
		$properties = array_merge( array( 'title' => $key ), $properties );

		return $this->items[ $key ] = new MenuItem( $properties );
	}

	/**
	 * Get an individual property
	 * @author Stef Horner   (shorner@wearearchitect.com)
	 * @param  string   $property
	 * @param  mixed  $default
	 * @return mixed
	 */
	public function getProperty( $property, $default = false ) {
		return isset( $this->properties[ $property ] ) ? $this->properties[ $property ] : $default;
	}

	/**
	 * Set an individual property
	 * @author Stef Horner (shorner@wearearchitect.com)
	 * @param  string   $key
	 * @param  mixed   $value
	 * @return $this
	 */
	public function setProperty( $key, $value ) {
		$this->properties[ $key ] = $value;
		return $this;
	}

	/// ARRAY ACCESS ///

	public function offsetExists ( $key ) {
		return isset( $this->properties[ $key ] );
	}

	public function offsetGet ( $key ) {
		return $this->getProperty( $key );
	}

	public function offsetSet ( $key , $value ) {
		$this->properties[ $key ] = $value;
	}

	public function offsetUnset ( $key ) {
		unset( $this->properties[ $key ] );
	}

	public function isActive()
	{
		if ( $this->active === true )
			return true;

		foreach ( $this->items as $item )
		{
			if ( $item->isActive() )
				return true;
		}

		return false;
	}

	public function setActive( $value = true )
	{
		$this->active = $value;

		return $this;
	}

	public function activate( $value, $key = 'link' )
	{
		if ( $this->getProperty( $key ) == $value )
		{
			$this->setActive();
		}

		return $this;
	}

}
