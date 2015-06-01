<?php
/**
 * Class for User
 *
 */
 
class User {
    
	private $FirstName;
	private $LastName;
	
	public function setFirstName($firstname)
	{
		$this->FirstName = $firstname;
	}
	
	public function setLastName($lastname)
	{
		$this->LastName = $lastname;
	}
	
	public function getFirstName()
	{
		return $this->FirstName;
	}
	
	public function getLastName()
	{
		return $this->LastName;
	}
	
	/**
	 * Array of this user's stocks
	 */
    private $stocks = array();
	
	/**
	 * Array of this user's friends 
	 */
	private $friends = array();
 	
	/**
	 * Array of this stock's weight in the portfolio.
	 */
	private $weights = array();
	
	public function getWeight() 
	{
		return $this->weights;
	}
	
    /**
     * Populate stock array with stock code
	 *
     * @param string $weight Weight of the stock at this index in the stocksp[] array    
     * @return void
     */
	 
    public function addWeight($weight)
    {
        $this->weights[] = $weight;
    }    
	
	/**
     * Populate stock array with stock code
	 *
     * @param string $stock Stock code of company    
     * @return void
     */
    public function addStock($stock)
    {
        $this->stocks[] = $stock;
    }
	    
	/**
     * Populate friends array with friend user name
	 *
     * @param string $friend Friend's user name    
     * @return void
     */
    public function addFriend($friend)
    {
        $this->friends[] = $friend;
    }

	public function getFriends()
    {
        return $this->friends;
    }
	
} 

