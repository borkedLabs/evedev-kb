<?php

abstract class CronCommand
{
	final public function __construct()
	{
		event::setCron(TRUE);
	}
	
    abstract public function execute();
}