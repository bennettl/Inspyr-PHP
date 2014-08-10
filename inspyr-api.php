<?php
require_once('database.php');
require_once('rest-api.php');
require_once('author.php');
require_once('quote.php');

class Inspyr_API extends Rest_API{
    protected $User;

    public function __construct($request, $origin) {
        parent::__construct($request);

        // Security purposes Only known and allowed external hosts will be able to connect to our API service through a pairing of their domain name and a uniquely generated API Key
        // Abstracted out for example
        // $APIKey = new Models\APIKey();
        // $User = new Models\User();

        // if (!array_key_exists('apiKey', $this->request)) {
        //     throw new Exception('No API Key provided');
        // } else if (!$APIKey->verifyKey($this->request['apiKey'], $origin)) {
        //     throw new Exception('Invalid API Key');
        // } else if (array_key_exists('token', $this->request) &&
        //      !$User->get('token', $this->request['token']))

        //     throw new Exception('Invalid User Token');
        // }

        // $this->User = $User;
    }

    //  Quote endpoint: http://bennettl.com/inspyr/index.php?request=quote
    public function quote(){
        // GET Request
        if ($this->method == 'GET') {
            $args = array('filterBy' => $this->args[0], 'search' => $this->args[1]);
            $data = Quote::get($args); 
            return $this->formatData($data); // return formatted data
        } else if ($this->method == 'POST'){
            // Insert new quote tot database
            $args        = array('author_name' => $_POST['author'], 'quote' => $_POST['quote']);
            $data        = array();
            $quote_id    = Quote::post($args);

            // Return a json format data of entire quote information from get method
            $args = array('filterBy' => 'id', 'search' => $quote_id);
            $data = Quote::get($args); 
            return $this->formatData($data); // return formatted data
        }
    }

     // Author endpoint: http://bennettl.com/inspyr/index.php?request=author/filter/search/{print}
     public function author(){
        // GET Request
        if ($this->method == 'GET') {
            $args = array('filterBy' => $this->args[0], 'search' => $this->args[1]);
            $data = Author::get($args);
            return $this->formatData($data); // return formatted data
        } else if ($this->method == 'POST'){
            // Insert new authors into database
            $args = array('name' => $_POST['name'], 
                         'biography' => $_POST['biography'], 
                         'img_url' => $_POST['image_url'],
                         'category_id' => $_POST['category_id'],
                         'title_id' => $_POST['title_id']);
            Author::post($args);
        } 
     }

      // Return data in print_r format (for degubggin purposes) or json_encode
     private function formatData($data){
        if (in_array('print', $this->args)){
            return print_r($data);
        } else{
            return json_encode($data);
        }
     }

    /**
     * Example of an Endpoint
     */
     // protected function example() {
     //    if ($this->method == 'GET') {
     //        return "Your name is " . $this->User->name;
     //    } else {
     //        return "Only accepts GET requests";
     //    }
     // }
}

// Note:  For each additional endpoint you wish to have in your API, simply add new functions into the MyAPI class whose name match the endpoint. You can then use the $method and $verb and $args to create flow paths within those endpoints.
?>