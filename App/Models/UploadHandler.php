<?php

namespace App\Models;

class UploadHandler 
{
    private $name;
    private $temp;
    private $type;
    private $size;
    private $error;
    private $allowedExtension = ['jpg','jpeg','png', 'webp'];
    private $disallowedExtension = ['pdf'];
    private $relativePath;

    public function __construct($array) 
    {
        $this->name = $array['fileName'];
        $this->type = $array['fileType'];
        $this->temp = $array['fileTmpName'];
        $this->error = $array['fileError'];
        $this->size = $array['fileSize'];
    }

    public function upload() 
    {
        if (!$this->checkExtension()) {
            return false;
        }

        $this->name = str_replace(' ','', $this->name);

        $this->relativePath = "/image/" . basename($this->name);
        $uploadPath = $_ENV['BASE_UPLOAD'] . $this->relativePath;

        if (move_uploaded_file($this->temp, $uploadPath)) {
            return true;
        } else {
            return false;
        }
    }

    private function checkFile() 
    {
        if (!$this->checkExtension() || !$this->checkError() || !$this->checkSize()) {
            return false;
        }
        return true;
    }

    private function checkExtension() 
    {
        $extension = pathinfo($this->name, PATHINFO_EXTENSION);

        if (!in_array($extension, $this->allowedExtension)) {
            return false;
        }
        return true;
    }
    
    private function checkError() 
    {
        if ($this->error !== 0) {
            return false;
        }
        return true;
    }

    private function checkSize() 
    {
        if ($this->size === 0) {
            return false;
        }
        return true;
    }

    public function getPath() { return $this->relativePath; }
}