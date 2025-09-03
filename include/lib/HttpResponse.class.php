<?php
defined('ACC') || exit('ACC Denied');
class HttpResponse
{
	public $status, $headers, $data;

	public function __construct($status, $headers, $data)
	{
		$this->status  = $status;
		$this->headers = $headers;
		$this->data    = $data;
	}
}
