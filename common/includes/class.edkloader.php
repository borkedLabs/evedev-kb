<?php
/**
 * $Date$
 * $Revision$
 * $HeadURL$
 * @package EDK
 */

/**
 * Autoloader for EDK classes.
 *
 * @package EDK
 */
class edkloader
{
    /** @var array */
    private static $classes = array();
    /** @var string */
    private static $dir = "";

	/**
	 * Load a class file.
	 *
	 * Filenames for a class start with "class." followed by either the
	 * lowercased form of the name or the file is in a subdirectory and the
	 * filename is "class." followed by the lowercased portion of the name
	 * after the first underscore.
	 *
	 * e.g.
	 * API_KillLog is in common/includes/api/class.killlog.php
	 *
	 * http_request is in common/includes/class.httprequest.php
	 *
	 * @param string $name
	 */
	public static function load($name)
	{
        // check for EsiClient-specific files
        // project-specific namespace prefix
        $prefix = 'EsiClient\\';
        
        // does the class use the namespace prefix?
        $len = strlen($prefix);
        if (strncmp($prefix, $name, $len) === 0) 
        {
            // get the relative class name
            $relative_class = substr($name, $len);
            $esiLibPath = __DIR__."common/esi/lib/EsiClient/".$relative_class.".php";
            if(file_exists($esiLibPath)) 
            {
                require_once $esiLibPath;
            }
        }
		
		$name = strtolower($name);
		$splitpos = strpos($name, "_");
		$subdirname = '';

		if ($splitpos > 0)
		{
			$subdirname = substr($name, 0, $splitpos);
			
			if ($subdirname == 'smarty')
			{
				return false;
			}
			$subfilename = substr($name, $splitpos + 1);
		}
		$name = str_replace("_", "", $name);
		
		if (isset(self::$classes[$name])) 
		{
			require_once(__DIR__.'/'.self::$classes[$name]);
			return true;
		} 
		
		$subFilePath = __DIR__.'/'.$subdirname."/class.".$subfilename.".php";
		
		if ($splitpos && is_file($subFilePath))
		{
			require_once $subFilePath;
			return true;
		} 
		else 
		{
			if( file_exists(__DIR__.'/class.'.$name.".php") )
			{
				require_once(__DIR__.'/class.'.$name.".php");
				return true;
			}
		}

		return false;
	}

    /**
     * Register a given file as containing the given class.
     * Re-registering a class name replaces the previous entry with the new.
     *
     * @param string $name
     * @param string $file
     */
    public static function register($name, $file)
    {
        self::$classes[strtolower($name)] = $file;
    }

    /**
     * Remove a registered classname. The default handler will be used instead.
     *
     * @param string $name
     */
    public static function unregister($name)
    {
        unset(self::$classes[strtolower($name)]);
    }

    /**
     * Set the root directory to be used for class files.
     *
     * @param string $dir The root directory to use for includes.
     */
    public static function setRoot($dir)
    {
        if (substr($dir, -1) != "/") {
            $dir .= "/";
        }

        self::$dir = $dir;
    }
}
