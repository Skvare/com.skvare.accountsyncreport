<?php
use CRM_Accountsyncreport_ExtensionUtil as E;

class CRM_Accountsyncreport_Form_Report_Contactsync extends CRM_Report_Form {

  protected $_addressField = FALSE;

  protected $_emailField = FALSE;

  protected $_summary = NULL;

  protected $_customGroupExtends = [
    'Contact',
    'Individual',
    'Household',
    'Organization',
  ];

  protected $_customGroupGroupBy = FALSE;

  function __construct() {
    $this->_columns = [
      'civicrm_contact' => [
        'dao' => 'CRM_Contact_DAO_Contact',
        'fields' => [
          'sort_name' => [
            'title' => E::ts('Contact Name'),
            'required' => TRUE,
            'default' => TRUE,
            'no_repeat' => TRUE,
          ],
          'id_a' => [
            'title' => ts('Contact ID'),
            'no_display' => TRUE,
            'name' => 'id',
            'required' => TRUE,
          ],
          'first_name' => [
            'title' => E::ts('First Name'),
            'no_repeat' => TRUE,
          ],
          'last_name' => [
            'title' => E::ts('Last Name'),
            'no_repeat' => TRUE,
          ],
        ],
        'filters' => [
          'sort_name' => [
            'title' => E::ts('Contact Name'),
            'operator' => 'like',
          ],
          'id' => [
            'no_display' => TRUE,
          ],
        ],
        'grouping' => 'contact-fields',
      ],
      'civicrm_address' => [
        'dao' => 'CRM_Core_DAO_Address',
        'fields' => [
          'street_address' => NULL,
          'city' => NULL,
          'postal_code' => NULL,
          'state_province_id' => ['title' => E::ts('State/Province')],
          'country_id' => ['title' => E::ts('Country')],
        ],
        'grouping' => 'contact-fields',
      ],
      'civicrm_email' => [
        'dao' => 'CRM_Core_DAO_Email',
        'fields' => ['email' => NULL],
        'grouping' => 'contact-fields',
      ],
      'civicrm_account_contact' => [
        'dao' => 'CRM_Accountsync_DAO_AccountContact',
        'fields' => [
          'id' => [
            'name' => 'id',
            'title' => E::ts('Account Contact Internal ID')
          ],
          'accounts_contact_id' => ['title' => E::ts('External Reference ID'), 'default' => TRUE,],
          'last_sync_date' => ['title' => E::ts('Contact Last Updated'), 'default' => TRUE,],
          'accounts_modified_date' => ['title' => E::ts('Last Synced Date'), 'default' => TRUE,],
        ],
        'filters' => [
          'accounts_contact_id' => [
            'title' => E::ts('External Reference ID'),
            'type' => CRM_Utils_Type::T_INT,
          ],
          'last_sync_date' => [
            'title' => E::ts('Contact Last Updated'),
            'operatorType' => CRM_Report_Form::OP_DATE,
            'type' => CRM_Utils_Type::T_DATE
          ],
          'accounts_modified_date' => [
            'title' => E::ts('Last Synced Date'),
            'operatorType' => CRM_Report_Form::OP_DATE,
            'type' => CRM_Utils_Type::T_DATE
          ],
          'accounts_needs_update' => [
            'title' => E::ts('Contact Sync Status'),
            'type' => CRM_Utils_Type::T_INT,
            'operatorType' => CRM_Report_Form::OP_SELECT,
            'options' => [
              '' => ts('Any'),
              '0' => ts('Completed'),
              '1' => ts('Pending'),
            ],
          ],
        ],
        'grouping' => 'account-contact-fields',
      ],
    ];
    $this->_groupFilter = TRUE;
    $this->_tagFilter = TRUE;
    parent::__construct();
  }

  function preProcess() {
    $this->assign('reportTitle', E::ts('Account Sync Contact Report'));
    parent::preProcess();
  }

  function from() {
    $this->_from = NULL;

    $this->_from = "
         FROM  civicrm_contact {$this->_aliases['civicrm_contact']} {$this->_aclFrom}
               INNER JOIN civicrm_account_contact {$this->_aliases['civicrm_account_contact']}
                          ON {$this->_aliases['civicrm_contact']}.id = {$this->_aliases['civicrm_account_contact']}.contact_id ";

    $this->joinAddressFromContact();
    $this->joinEmailFromContact();
  }

  /**
   * Add field specific select alterations.
   *
   * @param string $tableName
   * @param string $tableKey
   * @param string $fieldName
   * @param array $field
   *
   * @return string
   */
  function selectClause(&$tableName, $tableKey, &$fieldName, &$field) {
    return parent::selectClause($tableName, $tableKey, $fieldName, $field);
  }

  /**
   * Add field specific where alterations.
   *
   * This can be overridden in reports for special treatment of a field
   *
   * @param array $field Field specifications
   * @param string $op Query operator (not an exact match to sql)
   * @param mixed $value
   * @param float $min
   * @param float $max
   *
   * @return null|string
   */
  public function whereClause(&$field, $op, $value, $min, $max) {
    return parent::whereClause($field, $op, $value, $min, $max);
  }

  function alterDisplay(&$rows) {
    // custom code to alter rows
    $entryFound = FALSE;
    $checkList = [];
    foreach ($rows as $rowNum => $row) {

      if (!empty($this->_noRepeats) && $this->_outputMode != 'csv') {
        // not repeat contact display names if it matches with the one
        // in previous row
        $repeatFound = FALSE;
        foreach ($row as $colName => $colVal) {
          if (CRM_Utils_Array::value($colName, $checkList) &&
            is_array($checkList[$colName]) &&
            in_array($colVal, $checkList[$colName])
          ) {
            $rows[$rowNum][$colName] = "";
            $repeatFound = TRUE;
          }
          if (in_array($colName, $this->_noRepeats)) {
            $checkList[$colName][] = $colVal;
          }
        }
      }

      if (array_key_exists('civicrm_address_state_province_id', $row)) {
        if ($value = $row['civicrm_address_state_province_id']) {
          $rows[$rowNum]['civicrm_address_state_province_id'] = CRM_Core_PseudoConstant::stateProvince($value, FALSE);
        }
        $entryFound = TRUE;
      }

      if (array_key_exists('civicrm_address_country_id', $row)) {
        if ($value = $row['civicrm_address_country_id']) {
          $rows[$rowNum]['civicrm_address_country_id'] = CRM_Core_PseudoConstant::country($value, FALSE);
        }
        $entryFound = TRUE;
      }

      if (array_key_exists('civicrm_account_contact_id', $row) && $rows[$rowNum]['civicrm_account_contact_id']) {
        $url = CRM_Utils_System::url('civicrm/accountsync/contacterror',
          'reset=1&force=1&id=' . $row['civicrm_account_contact_id'],
          $this->_absoluteUrl
        );
        $rows[$rowNum]['civicrm_account_contact_id_link'] = $url;
        $rows[$rowNum]['civicrm_account_contact_id_hover'] = E::ts("View Error details related to this record");
      }

      if (array_key_exists('civicrm_contact_sort_name', $row) &&
        $rows[$rowNum]['civicrm_contact_sort_name'] &&
        array_key_exists('civicrm_contact_id_a', $row)
      ) {
        $url = CRM_Utils_System::url("civicrm/contact/view",
          'reset=1&cid=' . $row['civicrm_contact_id_a'],
          $this->_absoluteUrl
        );
        $rows[$rowNum]['civicrm_contact_sort_name_link'] = $url;
        $rows[$rowNum]['civicrm_contact_sort_name_hover'] = E::ts("View Contact Summary for this Contact.");
        $entryFound = TRUE;
      }

      if (!$entryFound) {
        break;
      }
    }
  }

}
