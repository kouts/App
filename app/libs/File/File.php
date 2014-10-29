<?php namespace libs\File;

class File {

	public function br_del_file_or_folder($item){
		if(is_file($item)){
			if(unlink($item)){
				return true;
			}else{
				return false;
			}
		}else{
			if($this->br_rmdir($item)==true){
				return true;
			}else{
				return false;
			}
		}
	}
	 
	public function br_rmdir($dir){
		if(is_dir($dir)){
			$objects = scandir($dir);
			foreach($objects as $object){
				if($object != "." && $object != ".."){
					if(filetype($dir."/".$object) == "dir"){
						$this->br_rmdir($dir."/".$object);
					}else{
						unlink($dir."/".$object);
					}
				}
			} 
	     reset($objects);
	     if(rmdir($dir)){
			 return true;
		 }else{
			 return false;
		 }
	   } 
	}

	public function br_copy($src, $dst){
		if (is_dir($src)){
			$old_mask = umask(0);
			if(@mkdir($dst)){
				umask($old_mask);
			}
			$files = scandir($src);
			foreach ($files as $file){
				if ($file != "." && $file != ".."){
					$this->br_copy("$src/$file", "$dst/$file");
				}
			}
		}else if (file_exists($src)){
			copy($src, $dst);
		}
	}

	public function get_product_name($file_name){
	   $key = "P\x00r\x00o\x00d\x00u\x00c\x00t\x00N\x00a\x00m\x00e\x00\x00\x00\x00\x00";
	   $fptr = fopen($file_name, "rb");
	   $data = "";
	   while (!feof($fptr))
	   {
	      $data .= fread($fptr, 65536);
	      if (strpos($data, $key)!==FALSE)
	         break;
	      $data = substr($data, strlen($data)-strlen($key));
	   }
	   fclose($fptr);
	   if (strpos($data, $key)===FALSE)
	      return "";
	   $pos = strpos($data, $key)+strlen($key);
	   $version = "";
	   for ($i=$pos; $data[$i]!="\x00"; $i+=2)
	      $version .= $data[$i];
	   return mb_convert_encoding($version, "UTF-8");
	   //return $version;
	}

	public function get_product_version($file_name){
	   $key = "P\x00r\x00o\x00d\x00u\x00c\x00t\x00V\x00e\x00r\x00s\x00i\x00o\x00n\x00\x00\x00";
	   $fptr = fopen($file_name, "rb");
	   $data = "";
	   while (!feof($fptr))
	   {
	      $data .= fread($fptr, 65536);
	      if (strpos($data, $key)!==FALSE)
	         break;
	      $data = substr($data, strlen($data)-strlen($key));
	   }
	   fclose($fptr);
	   if (strpos($data, $key)===FALSE)
	      return "";
	   $pos = strpos($data, $key)+strlen($key);
	   $version = "";
	   for ($i=$pos; $data[$i]!="\x00"; $i+=2)
	      $version .= $data[$i];
	   return mb_convert_encoding($version, "UTF-8");
	   //return $version;
	}

	public function format_file_fize_size($size, $display_bytes=false){
	   if( $size < 1024 )
	      $filesize = $size . ' bytes';
	   elseif( $size >= 1024 && $size < 1048576 )
	      $filesize = round( $size/1024, 2 ) . ' KB';
	   elseif( $size >= 1048576 )
	      $filesize = round( $size/1048576, 2 ) . ' MB';
	   if( $size >= 1024 && $display_bytes )
	      $filesize = $filesize . ' (' . $size . ' bytes)';
	   return $filesize;
	}

}