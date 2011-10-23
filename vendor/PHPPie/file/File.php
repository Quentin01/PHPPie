<?php
/*
* Classe abstraite qui sera héritée par XML, JSON, etc..
* Créée le 23/10/11 à 09:34
*/

//namespace PHPPie\File;

class File
{
	protected $file;

	public function __construct($file)
	{
		$this->file = $file;
	} 
	
	/**
	 *	Retourne la taille d'un fichier
	 *	@return int 	La taille du fichier
	 */
	public function getSize()
	{
		return filesize($this->file);
	}

	/**
	 *  
	 */
	public function getDir($rel = false)
	{
		if ($rel)
		{
			return dirname($this->file);
		}

		else
		{
			return realpath($this->file);	
		}
	}
	/**
	 * Supprime un fichier
	 * @return bool True si le fichier est supprimé, False sinon.
	 */
	public function del()
	{
		if (unlink($this->file))
		{
			return true;
		}

		else
		{
			return false;
		}
	}

	/**
	 * Renomme un fichier
	 * @param string $newName Le nouveau nom du fichier
	 * @return bool True si le renommage a marché, false sinon
	 */
	public function rename($newName = null)
	{
		if ($newName != null)
		{
			if (rename($this->file, $newName))
			{
				return true;
			}

			else
			{
				return false;
			}
		}

		else
		{
			return false;
		}
	}
}