<?php namespace App\Repositories;

interface TicketActionInterface {

	public function create(array $attr);

	public function createAndUpdateTicket(array $attrs);
	
}