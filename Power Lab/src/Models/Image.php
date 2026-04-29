<?php
    namespace App\Models;

    class Image
    {
        public function __construct(
            private string $name = "none",
            private string $data,
            private string $mimeType
            ) { }


        public function getBase64Src() : string
        {
            $base64 = base64_encode($this->data);
            return "data:{$this->mimeType};base64,{$base64}";
        }

        public function getRawData() : string
        {
            return $this->data;
        }

        public function getMimeType() : string 
        {
            return $this->mimeType;
        }

        public function getName() : string
        {
            return $this->name;
        }
    }
?>
