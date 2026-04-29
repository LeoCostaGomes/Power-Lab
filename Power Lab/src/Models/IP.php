<?php
    namespace App;
    
    class IP
    {   
        public function __construct(private string $IP)
        {
            if (!filter_var($this->ip, FILTER_VALIDATE_IP)) {
            throw new \InvalidArgumentException("O endereço IP '$this->ip' é inválido.");
        }
        }

        public function getIP() : string
        {
            return $this->IP;
        }

        public function compareIP(IP $ipToCompare) : bool
        {
            return $this->getIP() == ipToCompare->getIP();
        }
    }
?>