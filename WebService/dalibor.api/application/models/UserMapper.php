<?php
require_once "Application_Model_User.php";

class Application_Model_UserMapper
{
    protected $_dbTable;
 
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }
 
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_User');
        }
        return $this->_dbTable;
    }
 
    public function save(Application_Model_User $user)
    {
        $data = array(
            'password'   => $user->getPassword(),
            'id' => $user->getId(),
            'sessionkey'   => $user->getSessionkey(),
            'sessiontime' => $user->getSessiontime()
        );
 
        if (null === ($id = $user->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
 
    public function find($id, Application_Model_User $user)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $user->setId($row->id)
                ->setPassword($row->password)
                ->setSessionkey($row->sessionkey)
                ->setSessiontime($row->sessiontime);
    }
 
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_User();
            echo $row->id;
            $entry->setId($row->id)
                  ->setPassword($row->password)
                  ->setSessionkey($row->sessionkey)
                  ->setSessiontime($row->sessiontime);
            $entries[] = $entry;
        }
        return $entries;
    }
}
?>
