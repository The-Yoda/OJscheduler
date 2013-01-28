<?php
class GenModel {
    use GetSet;

    public function __construct($aDefaults = array()) {
        $this->_aData = $aDefaults;
    }

    public function asArray() {
	    return array_map(array($this, 'parseArray'), $this->_aData);
    }

    private function parseArray($aData)	{
        if (is_scalar($aData)) {
		    return $aData;
	    } elseif (is_array($aData)) {
		    return array_map(array($this, __FUNCTION__), $aData); // recursion
	    } elseif (is_object($aData)) {
			return $aData->asArray();
	    }
    }

    public function asJson() {
        return json_encode($this->asArray());
    }
}
?>
