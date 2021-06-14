<?php
use CRM_Accountsyncreport_ExtensionUtil as E;

class CRM_Accountsyncreport_Page_Invoicesyncerror extends CRM_Core_Page {

  public function run() {
    CRM_Utils_System::setTitle(E::ts('Invoice Sync Error Details'));
    $id = CRM_Utils_Request::retrieve('id', 'Positive', $this);
    $id = CRM_Utils_Type::escape($id, 'Integer');
    $sqlQuery = "SELECT * from civicrm_account_invoice where id = $id";
    $dao = CRM_Core_DAO::executeQuery($sqlQuery);

    $invoiceSyncDetails = [];
    while ($dao->fetch()) {
      $invoiceSyncDetails = $dao->toArray();
      if (!empty($invoiceSyncDetails['error_data'])) {
        $invoiceSyncDetails['error_data'] = json_decode($invoiceSyncDetails['error_data'], TRUE);
        $invoiceSyncDetails['error_data_date'] = $invoiceSyncDetails['error_data']['0'];
        $invoiceSyncDetails['error_data_message'] = $invoiceSyncDetails['error_data']['1']['message'];
      }
      else {
        $invoiceSyncDetails['error_data_date'] = 'No';
        $invoiceSyncDetails['error_data_message'] = 'No';
      }
    }

    // Example: Assign a variable for use in a template
    foreach ($invoiceSyncDetails as $elementName => $detail) {
      $this->assign($elementName, $detail);
    }

    parent::run();
  }

}
