<?php
namespace Application\Persistence;

interface PersistenceInterface 
{
	public function store(\Models\Event $event);
}