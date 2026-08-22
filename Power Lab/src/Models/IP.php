<?php
    namespace App\Models;
    
    class IP
    {   
        public function __construct(private string $IP)
        {
            if (!filter_var($this->IP, FILTER_VALIDATE_IP)) {
            throw new \InvalidArgumentException("invalide address IP: '$this->IP'.");
        }
        }

        public function getIP() : string
        {
            return $this->IP;
        }

        public function compareIP(IP $ipToCompare) : bool
        {
            return $this->getIP() === $ipToCompare->getIP();
        }
    }
?>