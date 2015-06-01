<?php
/**
 * Class to fetch stock data from Yahoo! Finance
 *
 */
 
class YahooStock {
    
	/**
	 * Array of stock code
	 */
    private $stocks = array();
	
	/**
	 * Parameters string to be fetched	 
	 */
	private $format;
 
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
     * Populate parameters/format to be fetched
	 *
     * @param string $param Parameters/Format to be fetched
     * @return void
     */
	public function addFormat($format)
    {
        $this->format = $format;
    }
 
    /**
     * Get Stock Data
	 *
     * @return array
     */
    public function getQuotes()
    {        
        $result = array();		
		$format = $this->format;
        
        foreach ($this->stocks as $stock)
        {            
			/**
			 * fetch data from Yahoo!
			 * s = stock code
			 * f = format
			 * e = filetype
			 */
            $s = file_get_contents("http://finance.yahoo.com/d/quotes.csv?s=$stock&f=$format&e=.csv");
            
			/** 
			 * convert the comma separated data into array
			 */
            $data = explode( ',', $s);
			
			/** 
			 * populate result array with stock code as key
			 */	
            $result[$stock] = $data;
        }
        return $result;
    }
	
	public function getUserNews()
	{
        $result = array();		
		$format = $this->format;
        
		$s = "http://finance.yahoo.com/rss/headline?s=";
        foreach ($this->stocks as $stock)
        {            
			/**
			 * fetch data from Yahoo!
			 * s = stock code
			 * http://finance.yahoo.com/rss/headline?s=stock1,stock2,stock3
			 */
			 $s = $s.$stock.",";
		}

		$rss = new DOMDocument();
		$rss->load($s);
		$feed = array();
		foreach ($rss->getElementsByTagName('item') as $node) {
			$item = array ( 
				'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
				'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
				'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
				'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
				);
			array_push($feed, $item);
		}
		return $feed;
	}
} 

