<?php 

use Psr\Log\AbstractLogger;

class LoggerMock extends AbstractLogger
{
    
    private $count = 0;
    
    
    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    public function log($level, $message, array $context = array())
    {
        $this->count++;
    }
    
    
    
    public function times(){
        return $this->count;
    }
    
    public function reset(){
        $this->count = 0;
    }
}
