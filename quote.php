<?php

class Quote{
	// internal properties
	private $mysqli, $errorMsg;
	
		// Does event exist. Return boolean, true means yes, false means no
	public static function exist($author_id = 0){
		$mysqli   = Database::connect();
		$select   = "SELECT 1 FROM Authors WHERE author_id = '$author_id'";
		$result   = $mysqli->query($select);
		return  (mysqli_num_rows($result) > 0) ? true : false;
	}

	// Constructor
	public function __construct($event_id = 0){
		$this->mysqli         = Database::connect();
		$this->errorMsg    = '';
	}

	// Return all quotes
	public static function get($args = null){
		extract($args);
		$mysqli   = Database::connect();

		// Modify the search query accordingly
		switch($filterBy){
	        case 'id':
	            $select = "SELECT * FROM Quotes WHERE quote_id = '$search'";
	            break;
	        case 'author':
	            $select = "SELECT q.* 
	            		   FROM Quotes AS q
	            		   INNER JOIN Authors AS a
	            		   ON (q.author_id = a.author_id)
	            		   WHERE UPPER(a.author_name) = UPPER('$search')";
	            break;
	        default:
	       		// Get all inforamation about quote
	          	$select = "SELECT * FROM Quotes";
	            break;
        }
		
        $select   .= " ORDER BY timestamp DESC";
		$res      = $mysqli->query($select);
		$quotes   = array();

		while($quote = $res->fetch_assoc()) {
            // Search for author information base on author id
            $args 			 = array('filterBy' => 'id', 'search' => $quote['author_id']);
		 	$author          = Author::get($args); // get author from author class
		    $quote['author'] = $author['authors'][0]; // append the first author to  $quote['author'
		    $quotes[] = $quote; // append each quote to quotes
		}
		$data = array('quotes' => $quotes);
		return $data;
	}

	// Post quote into databse. $args include 'author_name', 'quote'
	public static function post($args = null){
		extract($args); 
		$mysqli  	 = Database::connect();
		$author_name = trim($mysqli->real_escape_string($author_name));
        $quote       = $mysqli->real_escape_string($quote);

        // Get Id of author base on name
        $args 	     = array('filterBy' => 'name', 'search' => $author_name);
        $authors   	 = Author::get($args); // returns {'authors' : array()}
        $author  	 = $authors['authors'][0]; // get first author
        $author_id 	 = empty($author['author_id']) ? -1 : $author['author_id'];

        // Insert into database
       	$insert 	 = "INSERT INTO Quotes (content,author_id ) VALUES ('$quote', '$author_id')";      
		$mysqli->query($insert);

		// Retrn quote id
		return $mysqli->insert_id;
	}

	// Update event into database and filesystem
	public function update(){
	}
	
	// Removes event
	public function remove(){
		// Delete author information and quotes associated with author
		$delete  = "DELETE a,q
					FROM Authors AS a
					INNER JOIN Quotes AS q
					ON (a.author_id = q.author_id)
					WHERE a.author_id = '$this->id'";					 
		$mysqli->query($delete);
	}

}
?>