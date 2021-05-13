<?php
// This file declares a managed database record of type "ReportTemplate".
// The record will be automatically inserted, updated, or deleted from the
// database as appropriate. For more details, see "hook_civicrm_managed" at:
// https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
return [
  [
    'name' => 'CRM_Accountsyncreport_Form_Report_Invoicesync',
    'entity' => 'ReportTemplate',
    'params' => [
      'version' => 3,
      'label' => 'Account Sync for Invoice',
      'description' => 'Account Sync for Contact',
      'class_name' => 'CRM_Accountsyncreport_Form_Report_Invoicesync',
      'report_url' => 'accountsync/invoice',
      'component' => 'CiviContribute',
    ],
  ],
];
