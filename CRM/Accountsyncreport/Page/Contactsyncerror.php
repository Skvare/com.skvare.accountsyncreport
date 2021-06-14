<?php
use CRM_Accountsyncreport_ExtensionUtil as E;

class CRM_Accountsyncreport_Page_Contactsyncerror extends CRM_Core_Page {

  public function run() {
    CRM_Utils_System::setTitle(E::ts('Contact Sync Error Details'));
    $id = CRM_Utils_Request::retrieve('id', 'Positive', $this);
    $id = CRM_Utils_Type::escape($id, 'Integer');
    $sqlQuery = "SELECT * from civicrm_account_contact where id = $id";
    $dao = CRM_Core_DAO::executeQuery($sqlQuery);

    $contactSyncDetails = [];
    while ($dao->fetch()) {
      $contactSyncDetails = $dao->toArray();
      $contactUrl = CRM_Utils_System::url("civicrm/contact/view",
        'reset=1&cid=' . $contactSyncDetails['contact_id']);
      $displayName = CRM_Core_DAO::getFieldValue('CRM_Contact_DAO_Contact', $contactSyncDetails['contact_id'], 'display_name');
      $contactSyncDetails['contact_id'] = "<a href='{$contactUrl}' target='_blank'>{$displayName}</a>";
      if (!empty($contactSyncDetails['error_data'])) {
        $contactSyncDetails['error_data'] = json_decode($contactSyncDetails['error_data'], TRUE);
        $contactSyncDetails['error_data_number'] = $contactSyncDetails['error_data']['failures'];
        $contactSyncDetails['error_data_message'] = $contactSyncDetails['error_data']['error']['0'];
      }
      else {
        $contactSyncDetails['error_data_number'] = 'No';
        $contactSyncDetails['error_data_message'] = 'No Error';
      }
    }
    // Example: Assign a variable for use in a template
    foreach ($contactSyncDetails as $elementName => $detail) {
      $this->assign($elementName, $detail);
    }

    parent::run();
  }

}
