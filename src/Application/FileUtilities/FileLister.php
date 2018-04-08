<?php
namespace Application\FileUtilities;

interface FileLister
{
	public function getFiles() : array;

}