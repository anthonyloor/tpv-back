<?php

namespace App\Redsys\Model;

	if(!interface_exists('RESTRequestInterface')){
		interface RESTRequestInterface{

			public function getTransactionType();
		}
	}