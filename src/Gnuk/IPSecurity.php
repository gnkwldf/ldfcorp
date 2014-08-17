<?php
namespace Gnuk;
use DateTime;

/**
 * IPSecurity class
 * @author Anthony
 * @since 02/04/2014
 */
class IPSecurity
{
    private $directoryName;
    
    private $informations;
    
    private $limit = null;

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
        $this->processInformations();
    }

    /**
    * Check if the time is out of the interval
    * @param integer $time Timeout in second
    * @return boolean If the time is out of the interval
    */
    public function timeout($time)
    {
        if($this->currentTime() !== null AND $this->currentTime() > time() - $time) // Check if current time is in of the timeout interval
        {
            // The current time is in of the timeout interval
            return false;
        }
        if($this->limit !== null)
        {
            if($this->getTodayNumber() !== null AND $this->getTodayNumber() > $this->limit)
            {
                // The current time is up to time limit
                return false;
            }
        }
        // The current time is out of the timeout interval
        return true;
    }
    
    public function setLimit($number)
    {
        $this->limit = $number;
    }
    
    /**
    * Get the current time
    * @return integer
    */
    public function currentTime()
    {
        return $this->getInformationRow('time');
    }
    
    /**
    * Get the current number
    * @return integer
    */
    public function getNumber()
    {
        return $this->getInformationRow('number');
    }
    
    /**
    * Get today number
    * @return integer
    */
    public function getTodayNumber()
    {
        $day = new DateTime('now');
        return $this->getInformationRow($day->format('Ymd'));
    }
    
    public function setExtraInformation($key, $value)
    {
        if(!isset($this->informations['extra']))
        {
            $this->informations['extra'] = array();
        }
        $this->informations['extra'][$key] = $value;
    }
    
    public function getExtraInformation($key)
    {
        $extra = $this->getInformationRow('extra');
        if(null === $extra OR !isset($extra[$key]))
        {
            return null;
        }
        return $extra[$key];
    }
    
    private function getInformationRow($row)
    {
        if(!isset($this->informations[$row]))
        {
            return null;
        }
        return $this->informations[$row];
    }
    
    /**
    * Update time
    */
    public function update()
    {
        $this->ipFile();
        $date = new DateTime('now');
        $dateFormatted = $date->format('Ymd');
        $this->informations['time'] = time(); // Set current time to informations
        $this->incrementRow($dateFormatted);
        $this->incrementRow('number');
        $this->writeToFile();
    }
    
    private function incrementRow($name)
    {
        if(isset($this->informations[$name]))
        {
            $this->informations[$name]++; 
        }
        else
        {
            $this->informations[$name] = 1;
        }
    }
    
    private function writeToFile()
    {
        file_put_contents($this->ipFile(), json_encode($this->informations)); // Add serialized informations about time
    }
    
    /**
    * Get informations from file
    */
    private function processInformations()
    {
        if(!$this->fileExist())
        {
            $this->informations = array(
                'time' => null,
                'number' => 0
            );
            $this->writeToFile();
        }
        else if(!isset($this->informations))
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

