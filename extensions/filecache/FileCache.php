<?php
	class FileCache
	{
		private $path = 'realtime/cache';
		private $experition = 60;

     	
     	function __construct(){
     		$this->checkPath($this->path);
     	}
   		public function setPath($param){
   			if($path = $this->checkPath($param)){
				$this->path = $path;
   			}
   		}
   		public function get( $key )
   		{
   			$file = implode("/",[$this->getPath(),md5($key).'.bin']);
   			if(file_exists( $file ) && filemtime($file) > time())
   			{
   				return unserialize( file_get_contents( $file ) );
   			}
   			else
   			{
   				return null;
   			}
   		}
   		public function set( $key, $value, $experition = null)
   		{
   			if(!$experition)
   			{
   				$experition = $this->experition;
   			}
   			$experition = time() + $experition;	
   			$file = implode("/",[$this->getPath(),md5($key).'.bin']);
   			file_put_contents( $file, serialize( $value ) );
			touch( $file, $experition );
   		}
   		public function flush(){

   			$files = glob( $this->getPath()."/*" );
			foreach( $files as $file )
			{
			  	if( is_file($file) )
			  	{
			    	unlink( $file );
				}
   			}
   		}
   		/////////////////////////////////////////////////
   		private function basePath()
     	{
     		return $_SERVER[DOCUMENT_ROOT];
     	}
   		private function getPath(){
			return $this->path;
   		}
   		private function checkPath( $path ){
     		$path = explode( "/" , $path );
     		$_path = $this->basePath();
     		if( ArrayHelper::first( $path ) && ArrayHelper::first( $path ) == '..' )
     		{
				array_shift( $path );
				$_path = explode( "/" , $_path );
     			array_pop( $_path );
     			$_path = implode( '/', $_path );
     		}
     		foreach ( $path as $key )
     		{

     			if( $key && !file_exists( $_path = implode( '/', [ $_path , $key ] )))
     			{
     				if( !mkdir( $_path, 0777, true))
     				{
     					throw new Exception( 'Can\'t create cache dir' );
     				}
     			}
     		}
     		return $_path."/";
     	}

	}
?>