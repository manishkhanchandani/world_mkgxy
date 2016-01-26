<?php
class App_doctors_add extends App_base
{
    public function execute()
    {
      $data = $_POST;
      $help_doctors = $data;
      if (empty($help_doctors['name'])) {
        throw new Exception('Missing name');
      }
      if (empty($help_doctors['description'])) {
        throw new Exception('Missing description');
      }
      if (empty($help_doctors['email'])) {
        throw new Exception('Missing email');
      }
      if (empty($help_doctors['phone'])) {
        throw new Exception('Missing phone');
      }
      if (empty($help_doctors['therapy'])) {
        throw new Exception('Missing therapy');
      }
      if (empty($help_doctors['specialist'])) {
        throw new Exception('Missing speciality');
      }
      if (empty($help_doctors['age'])) {
        throw new Exception('Missing age');
      }
      if (empty($help_doctors['address'])) {
        throw new Exception('Missing address');
      }
      $this->_connMain->StartTrans(); 
      $insertSQL = $this->_connMain->AutoExecute('help_doctors', $help_doctors, 'INSERT');
      $doctor_id = $this->_connMain->Insert_ID();
        if (!empty($data['specialist'])) {
          foreach ($data['specialist'] as $k => $v) {
            $tmp = array();
            $tmp['doctor_id'] = $doctor_id;
            $tmp['specialist'] = $v;
            $insertSQL = $this->_connMain->AutoExecute('help_doctors_specialist', $tmp, 'INSERT');
          }
        }
        if (!empty($data['therapy'])) {
          foreach ($data['therapy'] as $k => $v) {
            $tmp = array();
            $tmp['doctor_id'] = $doctor_id;
            $tmp['therapy'] = $v;
            $insertSQL = $this->_connMain->AutoExecute('help_doctors_therapy', $tmp, 'INSERT');
          }
        }
      $this->_connMain->CompleteTrans();
      $this->return = array('New record created successfully.');
        
    }
}