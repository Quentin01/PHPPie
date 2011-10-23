<?php
/*
* Classe abstraite qui sera héritée par XML, JSON, etc..
* Créée le 23/10/11 à 09:34
*/

namespace PHPPie\File;

abstract class File
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
		return file_exists($this->file);
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
	public function getDir()
	{
		return realpath(dirname($this->file));
	}
	/**
	 * Supprime un fichier
	 * @return bool True si le fichier est supprimé, False sinon.
	 */
	public function del()
	{
		return unlink($this->file);
	}

	/**
	 * Renomme un fichier
	 * @param string $newName Le nouveau nom du fichier
	 * @return bool True si le renommage a marché, false sinon
	 */
	public function rename($newName)
	{
            $newName = $this->getDir().DIRECTORY_SEPARATOR.$newName;
            if(rename($this->file, $newName))
            {
                $this->file = $newName;
                return true;
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
                if(!is_dir(realpath(dirname($newPath))))
                {
                    if(!mkdir(realpath(dirname($newPath)), 0755, true))
                        return false;
                }
                
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
	 * @param string $newPath le nouveau chemin avec le nom du fichier
	 * @return bool True si ça marche et False si ça ne marche pas
	 */
	public function copy($newPath)
	{
                if(!is_dir(realpath(dirname($newPath))))
                {
                    if(!mkdir(realpath(dirname($newPath)), 0755, true))
                        return false;
                }
                
		return copy($this->file, $newPath);
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
		 	return chmod($this->file, $permissions);
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
		return is_writable($this->file);
	}

	/**
	 * Retourne si le fichier est disponible en lecture
	 * @return bool True si oui, False sinon
	 */
	public function isReadable()
	{
		return is_readable($this->file);
	}
	
	abstract public function toArray();
}