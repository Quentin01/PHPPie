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
	 *	Retourne si le fichier existe
	 *	@return bool 	Si le fichier existe, true sinon false;
	 */
	public function exists()
	{
		if (file_exists($this->file))
		{
			return false;
		}

		else
		{
			return false;
		}
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
				$this->file = $newName;
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

	/**
	 * Deplace un fichier
	 * @param string $newPath le dossier de destination
	 * @return bool True si ça marche et False en cas d'échec
	 */
	public function move($newPath)
	{
		if (copy($this->file, $newPath))
		{
			$this->del($this->file);
			$this->file = $newPath;
			return true;
		}

		else
		{
			return false;
		}
	}
	/**
	 * Copie un fichier
	 * @param string $newName le dossier et le nom nouveau nom
	 * @return bool True si ça marche et False si ça ne marche pas
	 */
	public function copy($newName)
	{
		if (copy($this->file, $newName))
		{
			return true;
		}

		else
		{
			return false;
		}
	}

	/**
	 * Change les permissions d'un fichier
	 * @param int $permissions Nouvelles permissions. Du type 0755.  
	 * @return bool True si ça marche False sinon
	 */
	 public function chmod($permissions)
	 {
	 	if (is_int($permissions))
	 	{
		 	if (chmod($this->file, $permissions))
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
			//A faire avec des exceptions pour le gestionnaire d'erreurs
			return false;
		}
	 }

	/**
	 * Retourne si le fichier est disponible en écriture
	 * @return bool True si oui, False sinon
	 */
	public function isWritable()
	{
		if (is_writable($this->file))
		{
			return true;
		}

		else
		{
			return false;
		}
	}

	/**
	 * Retourne si le fichier est disponible en lecture
	 * @return bool True si oui, False sinon
	 */
	public function isReadable()
	{
		if (is_readable($this->file))
		{
			return true;
		}

		else
		{
			return false;
		}
	}
	
	abstract public function toArray()
	{
		
	}
}