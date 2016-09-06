<?php

class Geocoding
{
	private $apiUrl = "https://maps.googleapis.com/maps/api/geocode/";

	const RESULT_JSON = 'json';
	const RESULT_XML  = 'xml';
	private $key = null;
	private $filters = [];
	private $output = self::RESULT_JSON;
	private $result = null;
	private $search = null;
	///////////////////////////
	//////////Set api key//////
	public function key($param)
	{
		$this->key = "&key=".$param;
	}
	///////////////////////////
	///////Set api to Json/////
	public function json()
	{
		$this->output = self::RESULT_JSON;
	}
	//////////////////////////
	/////Set api to xml///////
	public function xml()
	{
		$this->output = self::RESULT_XML;
	}
	/////////////////////////////////////
	////////Set country filter///////////
	public function country( $param )
	{
		$filter = implode( ":" , ['country' , $param] );
		array_push( $this->filters, $filter );
	}
	/////////////////////////////////////
	////////Set postal_code filter///////////
	public function postal_code( $param )
	{
		$filter = implode( ":" , ['postal_code' , $param] );
		array_push( $this->filters, $filter );
	}
	/////////////////////////////////////////////////
	////////Set administrative_area filter///////////
	public function administrative_area( $param )
	{
		$filter = implode( ":" , ['administrative_area' , $param] );
		array_push( $this->filters, $filter );
	}
	/////////////////////////////////////
	////////Set locality filter///////////
	public function locality( $param )
	{
		$filter = implode( ":" , ['locality' , $param] );
		array_push( $this->filters, $filter );
	}
	/////////////////////////////////////
	////////Set route filter///////////
	public function route( $param )
	{
		$filter = implode( ":" , ['route' , $param] );
		array_push( $this->filters, $filter );
	}

	public function get( $address, $key = null )
	{
		$this->search = $key;
		$response = $this->getResponse( $address );
		$response = ArrayHelper::first($response);
		return ArrayHelper::getElementByKey( $response , $key);
	}
	public function next(){
		$key = $this->search;
		$result = $this->result;
		$response = next($result);
		if($response){
			return ArrayHelper::getElementByKey( $response , $key); 
		}
		return false;
	}
	public function prev(){
		$key = $this->search;
		$result = $this->result;
		$response = prev($result);
		if($response){
			return ArrayHelper::getElementByKey( $response , $key); 
		}
		return false;
	}
	public function first(){
		$key = $this->search;
		$result = $this->result;
		$response = ArrayHelper::first($result);
		if($response){
			return ArrayHelper::getElementByKey( $response , $key); 
		}
		return false;
	}
	public function last(){
		$key = $this->search;
		$result = $this->result;
		$response = ArrayHelper::last($result);
		if($response){
			return ArrayHelper::getElementByKey( $response , $key); 
		}
		return false;
	}
	////////////////////////////////
	/////return results count //////
	private function results()
	{
		return count($this->result);
	}
	/////////////////////////////////
	////generate url for loading/////
	private function generateUrl( $address )
	{
		$address = urlencode( $address );
		$url = $this->apiUrl.$this->output."?address=".$address;
		if( count($this->filters) > 0){
			$filters = $this->generateFilters();
			$url.="&components=".$filters;
		}
		return $url; 
	}
	///////////////////////////////////////////////////////
	/////generate components filters for google api////////
	private function generateFilters(){
		$filters = $this->filters;
		return implode( "|" , $filters );
	}
	///////////////////////////////////////////////////////
	/////////////////////parse result//////////////////////
	private function parse( $data )
	{
		if($this -> output === self::RESULT_JSON){
			return json_decode( $data, true );
		}
		if($this -> output === self::RESULT_XML){
			$xml = simplexml_load_string( $data );
			$json = json_encode( $xml );
			$data = json_decode( $json , true );
			return ['results' => $data['result'],"status" => $data["status"]]; 
		}
	}
	////////////////////////////////////////////////////////
	//////////////////////Load api results//////////////////
	private function getResponse( $address ){
		$cache = new FileCache();
		$url = $this->generateUrl( $address );
		$result = $cache->get( $url );
		if(!$result)
		{
			$getpage = new GetPage;
			$request = $getpage->get( $url, $post = false, $this->key );
			if(!$request['error'])
			{
				$result = $this->parse( $request['data'] );
				if( $result['status'] == "OK" )
				{
					$cache->set( $url , $result["results"] );
					$this->result = $result["results"];
					return $result["results"];	
				}
				else
				{
					throw new Exception("Geocoding error : ".$result['status']."\r\n".$url ); 
				}
			}	
				
		}
		else
		{
			$this->result = $result;
			return $result;
		}
	}    
}

?>
