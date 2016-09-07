<?php

class Geocoding
{
	const RESULT_JSON = 'json';
	const RESULT_XML  = 'xml';

	const REQUEST_GEOCODE = 'address';
	const REQUEST_REVERSE = 'latlng';
	const REQUEST_PLACE   = 'place_id';
	//////////////////////////////////////////////////////////////////
	private $apiUrl         = "https://maps.googleapis.com/maps/api/geocode/";
	private $apiKey         = false;
	private $components     = [];
	private $result_types   = [];
	private $location_types = [];
	private $output         = self::RESULT_JSON;
	private $results        = false;
	private $lang           = "language=ru";
	private $region         = false;
	private $bounds         = false;

	///////////////////////////
	//////////Set api key//////
	public function apiKey( $param )
	{
		$this->apiKey = "key=".$param;
	}
	//////////Set Lang//////
	public function lang( $param )
	{
		$this->lang = "language=".$param;
	}
	public function bounds( $array )
	{
		/////////rewrite validate///////
		$this->bounds = "bounds=".$param;
	}
	public function region( $param )
	{
		/////////rewrite validate///////
		$this->region = "region=".$param;
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
	////////////////////////////////////////
	/*
			Components
										  */
	////////////////////////////////////////
	////////////////////////////////////////
	/*	
			Set country component
										  */
	////////////////////////////////////////
	public function country( $param )
	{
		$component = implode( ":" , ['country' , $param] );
		array_push( $this->components, $component );
	}
	//////////////////////////////////////
	/*
			Set postal_code component
										*/
	//////////////////////////////////////
	public function postal_code( $param )
	{
		$component = implode( ":" , ['postal_code' , $param] );
		array_push( $this->components, $component );
	}
	////////////////////////////////////////////
	/*
			Set administrative_area component
											  */
	////////////////////////////////////////////
	public function administrative_area( $param )
	{
		$component = implode( ":" , ['administrative_area' , $param] );
		array_push( $this->components, $component );
	}
	////////////////////////////////////
	/*
			Set locality component
									  */
	////////////////////////////////////
	public function locality( $param )
	{
		$component = implode( ":" , ['locality' , $param] );
		array_push( $this->components, $component );
	}
	////////////////////////////////////
	/*
			Set route component
									  */
	////////////////////////////////////
	public function route( $param )
	{
		$component = implode( ":" , ['route' , $param] );
		array_push( $this->components, $component );
	}
	///////////////////////////////////
	/*
			End Components
									 */
	///////////////////////////////////
	public function result_type( $param )
	{
		if(is_string( $param ))
		{
			$param = explode( "," , $param );
		}
		$this->result_types = array_merge( $this->result_types , $param );
	}
	public function location_type( $param )
	{
		if(is_string( $param ))
		{
			$param = explode( "," , $param );
		}
		$this->location_types = array_merge( $this->location_types , $param );
	}
	public function geocode( $address )
	{
		$address = urlencode( $address );
		$this->request = self::REQUEST_GEOCODE;
		$url = $this->generateUrl( $address );
		$this->results = $this->getResponse( $url ); 
	}
	public function reverse( $param )
	{
			$this->checkPermission();
			$this->request = self::REQUEST_REVERSE;
			$latlng = $this->latlng( $param );
			$url = $this->generateUrl( $latlng );
			$this->results = $this->getResponse( $url );
	}
	public function place( $place_id )
	{
			$this->checkPermission();
			$this->request = self::REQUEST_PLACE;
			$url = $this->generateUrl( $place_id );
			$this->results = $this->getResponse( $url );
	}
	/////////////////////////////////////////////////////////
	public function get( $key = null )
	{
		$result = current( $this->results );
		return ArrayHelper::getElementByKey( $result , $key ); 
	}
	public function next()
	{
		if( next( $this->results ) )
		{
			return true;
		}
		return false;
	}
	public function prev()
	{
		if( prev( $this->results ) )
		{
			return true;
		}
		return false;
	}
	public function first()
	{
		if( ArrayHelper::first( $this->results ) )
		{
			return true;
		}
		return false;
	}
	public function last()
	{
		if( ArrayHelper::last( $this->results ) )
		{
			return true;
		}
		return false;
	}
	////generate url for loading/////
	private function generateUrl( $q )
	{
		switch($this->request){
			case self::REQUEST_GEOCODE:
				$params = [
					$this->generateComponents(),
					$this->bounds,
					$this->region,
					$this->lang
				];
				$url = implode( "?", [ $this->apiUrl.$this->output , self::REQUEST_GEOCODE."=".$q ] );
				foreach($params as $key)
				{
					if($key)
						$url = implode( "&" , [ $url , $key ]);
				}
				var_dump($url);
				return $url;

			break;
			case self::REQUEST_REVERSE:
				$params = [
					$this->generateResultTypes(),
					$this->generateLocationTypes(),
					$this->lang
				];
				$url = implode( "?", [ $this->apiUrl.$this->output , self::REQUEST_REVERSE."=".$q ] );
				foreach($params as $key)
				{
					if($key)
						$url = implode( "&" , [ $url , $key ]);
				}
				var_dump($url);
				return $url;

			break;
			case self::REQUEST_PLACE:
					$params = [
						$this->lang
				];
				$url = implode( "?", [ $this->apiUrl.$this->output , self::REQUEST_PLACE."=".$q ] );
				foreach($params as $key)
				{
					if($key)
						$url = implode( "&" , [ $url , $key ]);
				}
				return $url;
			break;
		}
	}
	/////generate components filters for google api////////
	private function generateComponents()
	{
		if(count($this->components) > 0)
			return "components=".implode( "|" , $this->components );
		return false;
	}
	private function generateResultTypes()
	{
		if(count($this->result_types) > 0)
			return "result_type=".implode( "|" , $this->result_types );
		return false;
	}
	private function generateLocationTypes()
	{
		if(count($this->location_types) > 0)
			return "location_type=".implode( "|" , $this->location_types );
		return false;
	}
	///////////////////////////////////////////////////////
	/////////////////////parse result//////////////////////
	private function parse( $data )
	{
		if($this -> output === self::RESULT_JSON){
			return json_decode( $data, true );
		}
		elseif($this -> output === self::RESULT_XML){
			$xml = simplexml_load_string( $data );
			$json = json_encode( $xml );
			$data = json_decode( $json , true );
			return ['results' => $data['result'],"status" => $data["status"]]; 
		}
	}
	////////////////////////////////////////////////////////
	//////////////////////Load api results//////////////////
	private function getResponse( $url )
	{
		$cache = new FileCache();
		$result = $cache->get( $url );
		if(!$result)
		{
			$getpage = new GetPage;
			$request = $getpage->get( $url, $post = false, $this->apiKey );
			if(!$request['error'])
			{
				$result = $this->parse( $request['data'] );
				if( $result['status'] == "OK" )
				{
					$cache->set( $url , $result["results"] );
					return $result["results"];	
				}
				else
				{
					throw new Exception("Geocoding error : ".$result['status']."\r\n".$url ); 
				}
			}
			else
			{
				throw new Exception(" Geocoding error : ".$request['error']."\r\n" );
			}	
				
		}
		else
		{
			return $result;
		}
	}
	//////////////////////////////////////////////////////
	private function latlng( $param )
	{
		if(is_array( $param )){
			$param = implode( "," , [ArrayHelper::first($param) , ArrayHelper::last($param)] );
		}
		return $param;
	}
	private function latlngBounds( $northeast , $southwest )
	{
		return implode ( "|" , [ $northeast , $southwest ] );
	}
	private function checkPermission()
	{
		if( (count( $this->location_types ) > 0 || count( $this->result_types ) > 0) && $this->apiKey == false  )
		{
			throw new Exception(" You cant use location_types and result_types without api key" );
		}
		else 
		{
			return true;
		}
	}
	//////////////////////////////////////////////////////
	//////////////////////////////////////////////////////
	//////////////////////////////////////////////////////    
}

?>
