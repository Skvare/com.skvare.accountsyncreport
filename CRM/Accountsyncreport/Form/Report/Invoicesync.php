<?php
use CRM_Accountsyncreport_ExtensionUtil as E;

class CRM_Accountsyncreport_Form_Report_Invoicesync extends CRM_Report_Form {

  protected $_addressField = FALSE;

  protected $_emailField = FALSE;

  protected $_summary = NULL;

  protected $_customGroupExtends = [
    'Contact',
    'Individual',
    'Household',
    'Organization',
    'Contribution',
  ];
  protected $_customGroupGroupBy = FALSE;

  public $_drilldownReport = ['price/contributionbased' => 'Link to Detail Report'];

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
          'id' => [
            'no_display' => TRUE,
            'required' => TRUE,
          ],
          'first_name' => [
            'title' => E::ts('First Name'),
            'no_repeat' => TRUE,
          ],
          'id' => [
            'no_display' => TRUE,
            'required' => TRUE,
          ],
          'last_name' => [
            'title' => E::ts('Last Name'),
            'no_repeat' => TRUE,
          ],
          'id' => [
            'no_display' => TRUE,
            'required' => TRUE,
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
      'civicrm_contribution' => [
        'dao' => 'CRM_Contribute_DAO_Contribution',
        'fields' => [
          'contribution_id' => [
            'name' => 'id',
            'no_display' => TRUE,
            'required' => TRUE,
          ],
          'contribution_status_id' => [
            'title' => ts('Contribution Status'),
          ],
          'contribution_source' => ['title' => ts('Source')],
          'currency' => [
            'required' => TRUE,
            'no_display' => TRUE,
          ],
          'contribution_page_id' => [
            'title' => ts('Contribution Page'),
          ],
          'total_amount' => [
            'title' => ts('Contribution Amount'),
            'default' => TRUE,
          ],
          'trxn_id' => NULL,
          'receive_date' => ['default' => TRUE],
          'receipt_date' => NULL,
          'financial_type_id' => [
            'title' => ts('Financial Type'),
            'default' => TRUE,
          ],
          'payment_instrument_id' => [
            'title' => ts('Payment Type'),
          ],
          'check_number' => [
            'title' => ts('Check Number'),
          ],
          'non_deductible_amount' => [
            'title' => ts('Non-deductible Amount'),
          ],
        ],
        'grouping' => 'contri-fields',
        'filters' => [
          'receive_date' => ['operatorType' => CRM_Report_Form::OP_DATE],
          'receipt_date' => ['operatorType' => CRM_Report_Form::OP_DATE],
          'thankyou_date' => ['operatorType' => CRM_Report_Form::OP_DATE],
          'contribution_status_id' => [
            'title' => ts('Contribution Status'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => CRM_Contribute_BAO_Contribution::buildOptions('contribution_status_id', 'search'),
            'default' => [1],
            'type' => CRM_Utils_Type::T_INT,
          ],
          'contribution_page_id' => [
            'title' => ts('Contribution Page'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => CRM_Contribute_PseudoConstant::contributionPage(),
            'type' => CRM_Utils_Type::T_INT,
          ],
          'currency' => [
            'title' => ts('Currency'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => CRM_Core_OptionGroup::values('currencies_enabled'),
            'default' => NULL,
            'type' => CRM_Utils_Type::T_STRING,
          ],
          'financial_type_id' => [
            'title' => ts('Financial Type'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => CRM_Contribute_BAO_Contribution::buildOptions('financial_type_id', 'search'),
            'type' => CRM_Utils_Type::T_INT,
          ],
          'total_amount' => [
            'title' => ts('Contribution Amount'),
          ],
        ],
        'grouping' => 'contrib-fields',
      ],
      'civicrm_email' => [
        'dao' => 'CRM_Core_DAO_Email',
        'fields' => ['email' => NULL],
        'grouping' => 'contact-fields',
      ],
      'civicrm_account_invoice' => [
        'dao' => 'CRM_Accountsync_DAO_AccountInvoice',
        'fields' => [
          'accounts_invoice_id' => ['title' => E::ts('Invoice External Reference ID'), 'default' => TRUE,],
          'last_sync_date' => ['title' => E::ts('Invoice Last Updated'), 'default' => TRUE,],
          'accounts_modified_date' => ['title' => E::ts('Invoice Synced Date'), 'default' => TRUE,],
        ],
        'filters' => [
          'accounts_invoice_id' => [
            'title' => E::ts('Invoice External Reference ID'),
            'type' => CRM_Utils_Type::T_INT,
          ],
          'last_sync_date' => [
            'title' => E::ts('Invoice Last Updated'),
            'operatorType' => CRM_Report_Form::OP_DATE,
            'type' => CRM_Utils_Type::T_DATE
          ],
          'accounts_modified_date' => [
            'title' => E::ts('Invoice Synced Date'),
            'operatorType' => CRM_Report_Form::OP_DATE,
            'type' => CRM_Utils_Type::T_DATE
          ],
          'accounts_needs_update' => [
            'title' => E::ts('Invoice Sync Status'),
            'type' => CRM_Utils_Type::T_INT,
            'operatorType' => CRM_Report_Form::OP_SELECT,
            'options' => [
              '' => ts('Any'),
              '0' => ts('Completed'),
              '1' => ts('Pending'),
            ],
          ],
        ],
        'grouping' => 'account-invoice-fields',
      ],
    ];
    $this->_groupFilter = TRUE;
    $this->_tagFilter = TRUE;
    parent::__construct();
  }

  function preProcess() {
    $this->assign('reportTitle', E::ts('Membership Detail Report'));
    parent::preProcess();
  }

  function from() {
    $this->_from = NULL;

    $this->_from = "
         FROM  civicrm_contribution {$this->_aliases['civicrm_contribution']}
               INNER JOIN civicrm_contact {$this->_aliases['civicrm_contact']} 
                    ON {$this->_aliases['civicrm_contact']}.id = {$this->_aliases['civicrm_contribution']}.contact_id
               INNER JOIN civicrm_account_invoice {$this->_aliases['civicrm_account_invoice']}
                    ON {$this->_aliases['civicrm_contribution']}.id = {$this->_aliases['civicrm_account_invoice']}.contribution_id ";


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
    $contributionTypes = CRM_Contribute_PseudoConstant::financialType();
    $paymentInstruments = CRM_Contribute_PseudoConstant::paymentInstrument();
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

      if (array_key_exists('civicrm_membership_membership_type_id', $row)) {
        if ($value = $row['civicrm_membership_membership_type_id']) {
          $rows[$rowNum]['civicrm_membership_membership_type_id'] = CRM_Member_PseudoConstant::membershipType($value, FALSE);
        }
        $entryFound = TRUE;
      }
      if ($value = CRM_Utils_Array::value('civicrm_contribution_financial_type_id', $row)) {
        $rows[$rowNum]['civicrm_contribution_financial_type_id'] = $contributionTypes[$value];
        $entryFound = TRUE;
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

      if ($value = CRM_Utils_Array::value('civicrm_contribution_payment_instrument_id', $row)) {
        $rows[$rowNum]['civicrm_contribution_payment_instrument_id'] = $paymentInstruments[$value];
        $entryFound = TRUE;
      }

      if (array_key_exists('civicrm_contribution_contribution_id', $row) &&
        $rows[$rowNum]['civicrm_contribution_contribution_id'] &&
        array_key_exists('civicrm_contribution_total_amount', $row)
      ) {
        $url = CRM_Report_Utils_Report::getNextUrl('price/contributionbased',
          'reset=1&force=1&contribution_id_op=eq&contribution_id_value=' . $row['civicrm_contribution_contribution_id'],
          $this->_absoluteUrl, $this->_id, $this->_drilldownReport
        );
        $rows[$rowNum]['civicrm_contribution_total_amount_link'] = $url;
        $rows[$rowNum]['civicrm_contribution_total_amount_hover'] = E::ts("View Line item related to this record.");
      }

      if (array_key_exists('civicrm_contact_sort_name', $row) &&
        $rows[$rowNum]['civicrm_contact_sort_name'] &&
        array_key_exists('civicrm_contact_id', $row)
      ) {
        $url = CRM_Utils_System::url("civicrm/contact/view",
          'reset=1&cid=' . $row['civicrm_contact_id'],
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
