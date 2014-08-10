<?php

class Author{
	// internal properties
	private $mysqli, $errorMsg;
	
		// Does event exist. Return boolean, true means yes, false means no
	public static function exist($author_id = 0){
		$mysqli     = Database::connect();
		$select   = "SELECT 1 FROM Authors WHERE author_id = '$author_id'";
		$result   = $mysqli->query($select);
		return  (mysqli_num_rows($result) > 0) ? true : false;
	}

	// Constructor
	public function __construct($event_id = 0){
		$this->mysqli         = Database::connect();
		$this->errorMsg    = '';
	}

	// Return author information. $args include 'filterBy', 'search'
	public static function get($args){
		extract($args);
		$mysqli   = Database::connect();

		// Modify the search query accordingly
		switch($filterBy){
	  		case 'autocomplete':
	            $select = "SELECT author_name FROM Authors WHERE author_name LIKE '$search%'";
	            break;
	        case 'id':
	            $select = "SELECT * 
	            		   FROM Authors AS a
						   INNER JOIN Categories AS c
						   ON (a.category_id = c.category_id)
						   INNER JOIN Titles As t
						   ON (a.title_id = t.title_id)
						   WHERE author_id = '$search'";
	            break;
	        case 'name':
	            $select = "SELECT author_name, author_id FROM Authors WHERE UPPER(author_name) = UPPER('$search')";
	            break;
	        default:
	       		// Get all inforamation about author
	            $select = "SELECT * 
						   FROM Authors AS a
						   INNER JOIN Categories AS c
						   ON (a.category_id = c.category_id)
						   INNER JOIN Titles As t
						   ON (a.title_id = t.title_id)";
	            break;
        }
		$res 		= $mysqli->query($select);
		$authors  	= array();
        while ($author = $res->fetch_assoc()){
             $authors[] = $author; // append each author to authors
        }

		$data = array('authors' => $authors);
		return $data;
	}

	// Post author information. $args include 'name', 'biography', 'img_url'
	public static function post($args){
		extract($args);
		$mysqli  		= Database::connect();
		$name       	= $mysqli->real_escape_string($name);
        $pattern    	= '/\[[0-9]+\]/'; // remove [numbers]
        $biography  	= $mysqli->real_escape_string(mb_convert_encoding(preg_replace($pattern, " ", $biography),"HTML-ENTITIES","UTF-8"));
        $img_url    	= $mysqli->real_escape_string($img_url);
        $category_id 	=  intval($category_id);
        $title_id 		= intval($title_id);
		$insert 		= "INSERT INTO Authors (author_name, biography, category_id, title_id) VALUES ('$name', '$biography', '$category_id', '$category_id')";
		$mysqli->query($insert);
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