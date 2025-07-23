# com.skvare.accountsyncreport

## Overview

The Account Sync Report extension provides comprehensive reporting and monitoring capabilities for financial account synchronization between CiviCRM and external accounting systems. This extension offers detailed insights into contact-level and invoice-level synchronization status, helping administrators track data integration, identify synchronization failures, and maintain accurate financial records across multiple systems.

**Key Features:**
- Contact-level account synchronization reporting
- Invoice-level synchronization status tracking
- Customizable report templates for different business needs
- Real-time synchronization status monitoring
- Export capabilities for external audit and analysis

## Benefits

- **Financial Accuracy:** Ensure consistent financial data across CiviCRM and accounting systems
- **Operational Visibility:** Monitor synchronization health and identify issues proactively
- **Data Integrity:** Track and resolve data discrepancies between systems

## Use Cases

This extension is essential for organizations that need:

### Financial System Integration
- **QuickBooks Integration:** Track contact and invoice sync with QuickBooks Online/Desktop
- **Xero Synchronization:** Monitor financial data synchronization with Xero accounting

## Requirements

- **CiviCRM:** 5.0 or higher (recommended 5.39+)
- **PHP:** 7.0 or higher (recommended 7.4+)
- **Permissions:** Access to CiviReport
- **Integration:** Active account synchronization system (QuickBooks, Xero, Sage, etc.)

## Installation (Web UI)

This extension has not yet been published for installation via the web UI.

## Installation (CLI, Zip)

Sysadmins and developers may download the `.zip` file for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
cd <extension-dir>
cv dl com.skvare.accountsyncreport@https://github.com/Skvare/com.skvare.accountsyncreport/archive/master.zip
```

## Installation (CLI, Git)

Sysadmins and developers may clone the [Git](https://en.wikipedia.org/wiki/Git) repo for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
git clone https://github.com/Skvare/com.skvare.accountsyncreport.git
cv en accountsyncreport
```

## Configuration and Usage

### Creating Account Sync Reports

After installation, the extension provides two specialized report templates:

1. **Navigate to Report Creation:**
  - Go to **Administer > CiviReport > Create New Report from Template**
  - Or visit: `/civicrm/admin/report/template/list`

2. **Available Report Templates:**
  - **Account Sync for Contact:** Contact-level synchronization reporting
  - **Account Sync for Invoice:** Invoice-level synchronization tracking

### Account Sync for Contact Report

This report provides detailed information about contact synchronization status with external accounting systems.

#### Report Features

**Contact Synchronization Status:**
- **Sync Status:** Current synchronization state (Synced, Pending, Failed, Not Synced)
- **Last Sync Date:** Timestamp of most recent synchronization attempt
- **External Account ID:** Reference ID in the external accounting system
- **Sync Errors:** Detailed error messages for failed synchronizations
- **Contact Details:** Name, email, phone, and other identifying information

**Filter Options:**
- **Date Range:** Filter by sync date, creation date, or modification date
- **Sync Status:** Show only contacts with specific synchronization states
- **Contact Type:** Filter by Individual, Household, or Organization
- **Account System:** Filter by specific external accounting system
- **Error Types:** Focus on specific types of synchronization errors

## Support and Contributing

- **Issues:** Report bugs and feature requests on [GitHub Issues](https://github.com/Skvare/com.skvare.accountsyncreport/issues)

## Credits

Developed by [Skvare, LLC](https://skvare.com/contact) for the CiviCRM community.

## About Skvare

Skvare LLC specializes in CiviCRM development, Drupal integration, and providing technology solutions for nonprofit organizations, professional societies, membership-driven associations, and small businesses. We are committed to developing open source software that empowers our clients and the wider CiviCRM community.

**Contact Information**:
- Website: [https://skvare.com](https://skvare.com)
- Email: info@skvare.com
- GitHub: [https://github.com/Skvare](https://github.com/Skvare)

## Support

[Contact us](https://skvare.com/contact) for support or to learn more.

---

## Related Extensions

You might also be interested in other Skvare CiviCRM extensions:

- **Database Custom Field Check**: Prevents adding custom fields when table limits are reached
- **Image Resize**: Automatically resizes contact images for consistent display
- **Registration Button Label**: Customize button labels on event registration pages
- **Unlink User Account**: Safely unlink user accounts from contacts without deleting data

For a complete list of our open source contributions, visit our [GitHub organization page](https://github.com/Skvare).

