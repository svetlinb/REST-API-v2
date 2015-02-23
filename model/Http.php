<?php
/**
 * Created by PhpStorm.
 * User: svetlin.betsinski
 * Date: 20.2.2015 г.
 * Time: 15:53 ч.
 */

namespace model;
use \PDO;

class Http {
    protected $fnContainer;
    protected $dbh;
    protected $validator;
    protected $response;

    public function __construct(DBManager $db, Validator $validator, ResponseHandler $response) {
        $this->dbh = $db->getDB();
        $this->validator = $validator;
        $this->response = $response;
    }

    public function get($regEx, $fn) {
        $this->fnContainer['get'][$regEx] = $fn;
    }

    public function put($regEx, $fn) {
        $this->fnContainer['put'][$regEx] = $fn;
    }

    public function post($regEx, $fn) {
        $this->fnContainer['post'][$regEx] = $fn;
    }

    public function delete($regEx, $fn) {
        $this->fnContainer['delete'][$regEx] = $fn;
    }

    public function handle($method, $queryString) {
        $isValid = false;
        if(!empty($this->fnContainer) && array_key_exists($method, $this->fnContainer)
                                      && $this->validator->validateHttpMethod($method)) {
            foreach($this->fnContainer[$method] as $regEx => $fn) {
                $pattern = str_ireplace("/", "\/", $regEx);

                if(preg_match('@^'.$pattern.'$@i', $queryString, $match)) {
                    $id = (!empty($match[1])) ? $match[1] : null;
                    $fn($id);
                    $isValid = true;
                    break;
                }
            }
        }

        if(!$isValid) {
            throw new \Exception("Not found.", 404);
        }
    }

    /*
     * Check is record exist in DB
     *
     * @Return Boolean
     */
    private function isIdExist($id) {
        if(!$id){
            return false;
        }

        $stmt = $this->dbh->prepare("SELECT * FROM jobs WHERE id=:id ");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute(array(":id"=>$id));
        $row = $stmt->fetch();

        return !empty($row);
    }

    public function setRoutes() {
        $this->get('jobs/list', function ($id) {
            $query = 'SELECT * FROM jobs ORDER BY id ASC';
            $stmt = $this->dbh->query($query);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $rows = $stmt->fetchAll();

            if(empty($rows)) {
                throw new \Exception("There is no records.", 404);
            }

            $this->response->sendHeader($rows, 200);
        });

        $this->get('jobs/([0-9]+)', function ($id) {
            $checkId = $this->isIdExist($id);
            if(!$checkId) {
                throw new \Exception("Not Found", 404);
            }
            $stmt = $this->dbh->prepare("SELECT * FROM jobs WHERE id=:id ");
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute(array(":id"=>$id));
            $row = $stmt->fetch();

            $this->response->sendHeader($row, 200);
        });

        $this->post('jobs', function ($id) {
            if(empty($_POST) || empty($_POST['position']) || empty($_POST['description'])) {
                throw new \Exception("Invalid arguments", 400);
            }

            $query = "INSERT INTO jobs SET position = :position, description = :description";
            $stmt = $this->dbh->prepare($query);
            $stmt->bindParam(':position', $_POST['position'], PDO::PARAM_STR);
            $stmt->bindParam(':description', $_POST['description'], PDO::PARAM_STR);
            $stmt->execute();

            $this->response->sendHeader("Created", 201);
        });

        $this->put('jobs/([0-9]+)', function ($id) {
            $checkId = $this->isIdExist($id);
            parse_str(file_get_contents('php://input'), $data);
            if(!$checkId || empty($data['position']) || empty($data['description'])) {
                throw new \Exception("Not Found", 404);
            }

            $query = "UPDATE jobs SET position = :position, description = :description WHERE id = :id";
            $stmt = $this->dbh->prepare($query);
            $stmt->bindParam(':position', $data['position'], PDO::PARAM_STR);
            $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $this->response->sendHeader("Updated", 200);
        });

        $this->delete('jobs/([0-9]+)', function ($id) {
            $checkId = $this->isIdExist($id);
            if(!$checkId) {
                throw new \Exception("Not Found", 404);
            }

            $stmt = $this->dbh->prepare("DELETE FROM jobs WHERE id = :id ");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $this->response->sendHeader("Deleted", 200);
        });

        $this->get('candidates/review/([0-9]+)', function ($id) {
            $checkId = $this->isIdExist($id);
            if(!$checkId) {
                throw new \Exception("Not Found", 404);
            }
            $stmt = $this->dbh->prepare("SELECT * FROM candidates WHERE id=:id ");
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute(array(":id"=>$id));
            $row = $stmt->fetch();

            $this->response->sendHeader($row, 200);
        });

        $this->get('candidates/search/([0-9]+)', function ($id) {
            $checkId = $this->isIdExist($id);
            if(!$checkId) {
                throw new \Exception("Not Found", 404);
            }
            $stmt = $this->dbh->prepare("SELECT * FROM candidates WHERE id=:id ");
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute(array(":id"=>$id));
            $row = $stmt->fetch();

            $this->response->sendHeader($row, 200);
        });
    }
} 