<?php
class MongoBase {
    use Config;
    
    private $database;
    function __construct(){
        $server = self::conf("Mongo")->getServer();
        $database = self::conf("Mongo")->getDatabase();
        $connection = new Mongo($server);
        $this->database = $connection->$database;
    }

    public function save($oNewObj, $type, $query = null) {
        try {
            $id = null;
            $collection = $this->database->$type;
            if (empty($query)){
                $id = (!$oNewObj->hasId())? IDGenerator::getNewId() : $oNewObj->getId();
                $pattern = array('id' => $id);
            }
            else $pattern = $this->getQueries($query->getQueries()->asArray())[0];
            $options = array('multiple' => true, 'safe' => true, 'upsert' => true);
            $collection->update($pattern, array('$set' => $oNewObj->asArray()), $options);
            return $id;
        } catch(Exception $e){
            throw GenericExceptionFactory::getException("ERR_DB_1002", $e);
        }
    }

    public function delete($id, $type){
        try {
            $collection = $this->database->$type;
            return $collection->remove(array('id' => $id));
        } catch(Exception $e){
            throw GenericExceptionFactory::getException("ERR_DB_1002", $e);
        }
    }

    public function findByID($id, $type){
        try{
            $collection = $this->database->$type;
            $result = $collection->findOne(array("id" => $id), array('_id'=>0));
            return ObjectFactory::getGenModelInstance($type, $result);
        } catch(Exception $e){
            throw GenericExceptionFactory::getException("ERR_DB_1001", $e);
        }
    }

    function getQueries($aqueries) {
        $cond = array('and' => '$and' , 'or'=>'$or');
        foreach ($aqueries as $key => $value){
            if (array_key_exists($key, $cond))
                $query[] = array($cond[$key] => $this->getQueries($value));
            else $query[] = array($key =>  $value);
        }
        return $query;
    }

    function getFields($oFields, $bInclude){
        $bInclude = ($bInclude === null)? true : $bInclude;
        $aFields = ($oFields  === null)? array() : $oFields->asArray();
        return array_fill_keys($aFields, $bInclude);
    }

    public function find(Genmodel $oQuery, $type){
        try {
            $collection = $this->database->$type;
            $fields = $this->getFields($oQuery->getFields(), $oQuery->getFieldInclusionType());
            $query = $this->getQueries($oQuery->getQueries()->asArray())[0];
            $result = $collection->find($query, $fields)->limit($oQuery->getLimit())->skip($oQuery->getOffset());
            $array = iterator_to_array($result, true);
            $results = array();
            foreach ($array as $key => $value) {
                unset($value['_id']);
                $results[] = ObjectFactory::getGenModelInstance($type, $value);
            }
            return $results;
        } catch(Exception $e){
            throw GenericExceptionFactory::getException("ERR_DB_1001", $e);
        }
    }
}
?>