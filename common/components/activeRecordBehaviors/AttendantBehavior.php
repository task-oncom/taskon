<?php

class AttendantBehavior extends CActiveRecordBehavior
{
    public $attendant_table;
    
    public $columnItem = 'item_id';
    
    public $columnAttendant = 'attendant_id';
    
    public $connectionID = 'db';
    
    protected $_data = NULL;
    
    public function save($items)
    {
        if($items === NULL)
            return;
            
        if(is_string($items))
            $items = explode(',', $items);
        elseif(!is_array($items))
            $items = array($items);
            
        $values = array();
        foreach($items as $item)
        {
            if(!empty($item))
                $values[] = $item;
        }
        $items = array_unique($values);
        
        //удаляем старые привязки
        $this->deleteAttendant();
        $db = Yii::app()->{$this->connectionID};
        $pk = $this->owner->primaryKey;
        //назначаем новые
        if($n = count($items))
        {
            $values = array();
            foreach($items as $item)
            {
                 $values[] = strtr('( {item}, {attendant}), ({attendant}, {item})', array(
                 // $values[] = strtr('( {item}, {attendant})', array(
                     '{item}' => $db->quoteValue($pk), 
                     '{attendant}' => $db->quoteValue($item)
                 ));
            }
            $sql = 'INSERT INTO {table} ({item}, {attendant}) VALUES {values}';
            $db->createCommand(strtr($sql, array(
                '{table}' => $db->quoteTableName($this->attendant_table),
                '{item}' => $db->quoteColumnName($this->columnItem),
                '{attendant}' => $db->quoteColumnName($this->columnAttendant),
                '{values}' => implode(', ', $values)
            )))->execute();
        }
        
    }
    
    public function afterDelete($event)
    {
        parent::afterDelete($event);
    }
    
    public function getAttendants()
    {
        if($this->_data === NULL)
        {
            $db = Yii::app()->{$this->connectionID};
            $this->_data = $db->createCommand()
                ->select($this->columnAttendant)
                ->from($this->attendant_table)
                ->where("{$this->columnItem} = :id", array(
                    'id' => $this->owner->primaryKey
                ))
                ->queryColumn();
        }
        return $this->_data;
    }
    
    protected function deleteAttendant()
    {
        $db = Yii::app()->{$this->connectionID};
        $pk = $this->owner->primaryKey;
		
		$db->createCommand()->delete($this->attendant_table, "{$this->columnItem} = :id OR {$this->columnAttendant} = :id", array(':id'=>$pk));
                // "{$this->columnItem} = :id OR {$this->columnAttendant} = :id", array('id' => $pk)
	}

}

?>