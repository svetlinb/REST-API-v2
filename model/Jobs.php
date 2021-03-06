<?php
namespace model;

class Jobs {
    private $dbh;
    private $validator;
    private $response;
    private $className;

    public function __construct(DBManager $db,  Validator $validator, ResponseHandler $response) {
        $this->dbh = $db->getDB();
        $this->validator = $validator;
        $this->response = $response;
        $reflect = new \ReflectionClass(__CLASS__);
        $this->className = strtolower($reflect->getShortName());
    }
	
	public function get($id) {
        $checkId = $this->validator->isIdExist($id, $this->dbh, $this->className);
		if(!$checkId) {
			throw new \Exception("Not Found", 404);
		}
		$stmt = $this->dbh->prepare("SELECT * FROM jobs WHERE id=:id ");
		$stmt->setFetchMode(\PDO::FETCH_ASSOC);
		$stmt->execute(array(":id"=>$id));
		$row = $stmt->fetch();
		
	    $this->response->sendHeader($row, 200);
	}

	public function post() {
		if(empty($_POST)) {
			throw new \Exception("Invalid arguments", 400);
		}
	
		$query = "INSERT INTO jobs SET position = :position, description = :description";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(':position', $_POST['position'], \PDO::PARAM_STR);
		$stmt->bindParam(':description', $_POST['description'], \PDO::PARAM_STR);
		$stmt->execute();
		
	  	$this->response->sendHeader("Created", 201);
	}
	
	public function put($id) {
        $checkId = $this->validator->isIdExist($id, $this->dbh, $this->className);
		parse_str(file_get_contents('php://input'), $data);
		if(!$checkId || empty($data)) {
			throw new \Exception("Not Found", 404);
		}
		
		$query = "UPDATE jobs SET position = :position, description = :description WHERE id = :id";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(':position', $data['position'], \PDO::PARAM_STR);
		$stmt->bindParam(':description', $data['description'], \PDO::PARAM_STR);
		$stmt->bindParam(':id', $id, \PDO::PARAM_INT);
		$stmt->execute();
		
		$this->response->sendHeader("Updated", 200);
	}
	
	public function delete($id) {
        $checkId = $this->validator->isIdExist($id, $this->dbh, $this->className);
		if(!$checkId) {
			throw new \Exception("Not Found", 404);
		}

		$stmt = $this->dbh->prepare("DELETE FROM jobs WHERE id = :id ");
		$stmt->bindParam(':id', $id, \PDO::PARAM_INT);
		$stmt->execute();
		
		$this->response->sendHeader("Deleted", 200);
	}
	
	public function show() {
		$query = 'SELECT * FROM jobs ORDER BY id ASC';
		$stmt = $this->dbh->query($query);
		$stmt->setFetchMode(\PDO::FETCH_ASSOC);
		$rows = $stmt->fetchAll();
		
		if(empty($rows)) {
			throw new \Exception("There is no records.", 404);
		}
		
	  
		$this->response->sendHeader($rows, 200);
	}
}

?>