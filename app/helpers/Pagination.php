<?php

namespace Helper;

class Pagination {
	protected $uri;
	protected $configBDD = array();
	
	protected $nbrRow = null;
	protected $nbrPerPage = 5;
	protected $currentPage = 1;
	
	public function __construct($uri, $currentPage, $nbrPerPage = 5, $configBDD = array()) {
		$this->uri = $uri;
		$this->nbrPerPage = $nbrPerPage;
		$this->currentPage = $currentPage;
		$this->configBDD = $configBDD;
	}
	
	public function getDataWithArray($data = array()) {
		$this->nbrRow = count($data);
		
		if($this->currentPage > ceil($this->nbrRow / $this->nbrPerPage)) $this->currentPage = ceil($this->nbrRow / $this->nbrPerPage);
		elseif($this->currentPage <= 0) $this->currentPage = 1;
		
		$offset = ($this->currentPage - 1) * $this->nbrPerPage;
		return array_slice($data, $offset, $this->nbrPerPage);
	}
	
	public function getDataWithSQL($table, $options = '', $data = array()) {
		if(empty($this->configBDD)) {
			return false;
		}
		
		$pdo = new \PDO($this->configBDD['dsn'], $this->configBDD['user'], $this->configBDD['password']);
		$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		
		$sqlCount = "SELECT COUNT(*) FROM " . $table . " " . $options;
		
		$req = $pdo->prepare($sqlCount);
		$req->execute($data);
		$data = $req->fetch();
		
		$this->nbrRow = $data['COUNT(*)'];
		
		if($this->currentPage > ceil($this->nbrRow / $this->nbrPerPage)) $this->currentPage = ceil($this->nbrRow / $this->nbrPerPage);
		elseif($this->currentPage <= 0) $this->currentPage = 1;
		
		$offset = ($this->currentPage - 1) * $this->nbrPerPage;
		$sqlSelect = "SELECT * FROM " . $table . " " . $options  . " LIMIT " . $this->nbrPerPage . " OFFSET " . $offset;
		
		$req = $pdo->prepare($sqlSelect);
		$req->execute($data);
		
		return $req->fetchAll();
	}
	
	public function getLinks($nbrDisplay = 3) {
		if($this->nbrRow === null) {
			return false;
		}
		
		$links = array();
		$nbrPage = ceil($this->nbrRow / $this->nbrPerPage);
		
		if($this->currentPage > $nbrPage) $this->currentPage = $nbrPage;
		elseif($this->currentPage <= 0) $this->currentPage = 1;
		
		$currentPage = $this->currentPage;
		
		for ($i = 1; $i <= $nbrPage; $i++) {
			if (($i <=  $nbrDisplay) OR ($i > $nbrPage - $nbrDisplay) OR (($i <= $currentPage + ($nbrDisplay / 2)) AND ($i >= $currentPage - ($nbrDisplay / 2)))) {
				$links[] = ($i == $currentPage) ? '[ ' . $i . ' ]' : '<a href="'.str_replace('{page}', $i, $this->uri).'">'.$i.'</a>';
			} elseif($links[count($links) - 1] !== '[...]') {
				$links[] = '[...]';
			}
		}
		
		return $links;
	}
	
	public function getFormatedLinks($nbrDisplay = 3, $separator = ", ") {
		return implode($separator, $this->getLinks($nbrDisplay));
	}
}
