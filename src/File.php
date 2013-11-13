<?php
/**
* 
*/
class File
{

	static function deleteOnDisk($path) {
		try {
			unlink($path);
			return true;

		} catch (Exception $e) {
			return false;
		}
	}

	function __construct($params = array())
	{
		$this->file = $params["file"];
		$this->allowedExtensions = isset($params["allowedExtensions"])? $params["allowedExtensions"] : null;
		$this->directory = isset($params["directory"])? $params["directory"] : "";

		$temp = explode(".", $this->file["name"]);
		$this->extension = end($temp);
		$this->title = implode("",array_slice($temp, 0, -1));		

		$this->name = $this->generateName();
		$this->originalName = $this->file["name"];
	}

	function isValid() {
		$result = $this->file["error"] == 0;
		if($result && !is_null($this->allowedExtensions)) {
			$result = in_array($this->getExtension(), $this->allowedExtensions);
		}

		return $result; 
	}

	function saveOnDisk() {
		if($this->isValid()) {
			try {
				Log::console($this->directory .$this->getName());
				move_uploaded_file($this->file["tmp_name"], $this->directory.$this->getName());
				Log::console("movido ok");
				return true;

			} catch (Exception $e) {}
		}
		return false;
	}

	function getExtension() {
		return $this->extension;
	}

	function getTitle() {
		return $this->title;
	}

	function getName() {
		return $this->name;
	}

	function getOriginalName() {
		return $this->originalName;
	}

	function getPath() {
		return $this->directory . $this->name;
	}

	protected function generateName() {
		$result = $this->getTitle().rand().".".$this->getExtension();
		return $result;
	}
}

?>