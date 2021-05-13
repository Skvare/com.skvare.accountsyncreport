# com.skvare.accountsyncreport

This extension provide report for Account Sync on Contact and Invoice table.


The extension is licensed under [AGPL-3.0](LICENSE.txt).

## Requirements

* PHP v7.0+
* CiviCRM (*FIXME: Version number*)

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

## Usage

After installing the extension, visit 'Administer CiviCRM' -> 'CiviReport' -> 'Create New Report from Template'  (`civicrm/admin/report/template/list`)

There are 2 report templates available with following name: 
* Account Sync for Contact	
* Account Sync for Invoice

Use this template to create reports.