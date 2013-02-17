<?php
trait GetSet {

    private $_aData = array();

    public function __call($sFunction, $aParam) {
        $sCallType   = substr($sFunction, 0, 3);
        $sFieldName  = substr($sFunction, 3);
        $sFieldName  = strtolower($sFieldName);
        return $this->$sCallType($sFieldName, $aParam);
    }

    private function get ($sField) {
        if (isset($this->_aData[$sField])) {
            return $this->_aData[$sField];
        }
        return null;
    }

    private function set ($sField, $sValue) {
        $sFieldValue = is_array($sValue) && !empty($sValue) ? $sValue[0] : $sValue;
        $this->_aData[$sField]    = $sFieldValue;
        return true;
    }

    private function add ($sField, $sValue) {
        if (empty($this->_aData[$sField])) {
            $this->_aData[$sField] = array();
        }

        if (is_scalar($this->_aData[$sField])) {
            $this->_aData[$sField] = array($this->_aData[$sField]);
        }

        $sFieldValue = !empty($sValue) && is_array($sValue) ? $sValue[0] : $sValue;

        return array_push($this->_aData[$sField], $sFieldValue);
    }

    private function has ($sField) {
        return isset($this->_aData[$sField]);
    }

    public function getClassFields() {
        return array_keys($this->_aData);
    }
}
?>