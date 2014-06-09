<?php
namespace Gnuk;

/**
 * IPSecurity class
 * @author Anthony
 * @since 02/04/2014
 */
class IPSecurity
{
    private $directoryName;
    
    private $informations;

    /**
    * IPSecurity constructor
    */
    public function __construct($name = 'default')
    {
        $this->directoryName = __DIR__.'/cache/' . $name;
        if(!is_dir($this->directoryName))
        {
            mkdir($this->directoryName, 0777, true);
        }
        $this->informations = null;
    }

    /**
    * Check if the time is out
    * @param integer $time Timeout in second
    * @return boolean If the time is out
    */
    public function timeout($time)
    {
        if($this->fileExist() AND $this->currentTime() > time() - $time) // Check if current time is in the timeout interval
        {
            // The current time is in the timeout interval
            return false;
        }
        // The current time is out of the timeout interval
        return true;
    }
    
    /**
    * Get the current time
    * @return integer
    */
    public function currentTime()
    {
        if(!$this->fileExist())
        {
            $this->update();
            return time();
        }
        $this->processInformations();
        return $this->informations['time'];
    }
    
    /**
    * Update time
    */
    public function update()
    {
        $this->ipFile();
        $this->informations = array('time' => time()); // Set current time to informations
        file_put_contents($this->ipFile(), json_encode($this->informations)); // Add serialized informations about time
    }
    
    /**
    * Get informations from file
    */
    private function processInformations()
    {
        if(!isset($this->informations))
        {
            $this->informations = $this->jsonLoad($this->ipFile()); // Receive informations from file
        }
    }
    
    /**
    * Load a json file
    * @param string $file Path to the file to load
    * @return array|null
    */
    private function jsonLoad($file)
    {
        if(!is_file($file))
        {
            return null;
        }
        return json_decode(file_get_contents($file), true);;
    }

    /**
    * Name of the IP file containing informations
    * @return string
    */
    private function ipFile()
    {
        if(empty($_SERVER['REMOTE_ADDR'])) // Create special file we can't get IP
        {
            return $this->directoryName.'/noIP.json';
        }
        return $this->directoryName.'/'.$_SERVER['REMOTE_ADDR'].'.json';
    }
    
    /**
    * Check if the ip file exist
    * @return boolean
    */
    private function fileExist()
    {
        return is_file($this->ipFile());
    }
}

