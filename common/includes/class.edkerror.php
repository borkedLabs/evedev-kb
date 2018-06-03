<?php
/**
 * $Date$
 * $Revision$
 * $HeadURL$
 * @package EDK
 */

/**
 * @package EDK
 */
class EDKError
{
    protected static $LOG_FILE = "cache/error.log";
    /** the maximum allowed log file size in megabytes before rolling */
    protected static $LOG_FILE_SIZE_MAX = 10;
    
    /**
     * Checks the size of the error log file.
     * If it is above the maximum allowed size, it is renamed to .old.
     */
    protected static function checkAndRollLogFile()
    {
        if(filesize(self::$LOG_FILE) > 1024*1024*self::$LOG_FILE_SIZE_MAX)
        {
            @unlink(self::$LOG_FILE.".old");
            rename(self::$LOG_FILE, self::$LOG_FILE.".old");
        }
    }
    
    /**
     * Logs the given error text to the log file.
     * 
     * @param string $errorText the text to log
     */
    public static function log($errorText)
    {
        if(class_exists('config') && config::get('cfg_log'))
        {
            self::checkAndRollLogFile();
            error_log(sprintf("EDK %s:  %s\n", gmdate("Y-m-d H:i:s"), $errorText), 3, self::$LOG_FILE);
        }
    }
}