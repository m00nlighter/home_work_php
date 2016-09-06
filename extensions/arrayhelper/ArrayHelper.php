<?php

	class ArrayHelper
	{
		public static function first( $arr ){
    		if(is_array( $arr ))
    		{
        		return reset( $arr );
    		}
    		else
    		{
      			return null;
    		}
		}
		public static function last( $arr ){
			if(is_array( $arr ))
			{
			  return end( $arr );
			}
		    else
		    {
		      return null;
		    }
		}
		public static function getElementByKey( $array, $key = null ){
			if($key){
				$request = explode( ".", $key );	
			
				foreach ( $request as $requestKey )
				{
					if( is_array( $array ) && isset($array[$requestKey]) ){
						$array = $array[$requestKey];
					}
					else
					{
						return null;
					}
				}
			}
			return $array;
			
		}
	}
?>