# Wiise (Microsoft Business Central) PHP Wrapper
 
This package provides an integration (OAuth 2.0) to Wiise (Microsoft Business Central).

[![Latest Version](https://img.shields.io/github/release/glasswalllab/wiise-connector.svg?style=flat-square)](https://github.com/glasswalllab/wiise-connector/releases)

## Installation

You can install the package via composer:

```bash
composer require glasswalllab/wiiseconnector
```

## Usage

1. Setup Web App in Microsoft Azure AD to obtain required credentials.

2. Include the following variables in your .env

```
WIISE_COMPANY_NAME=YOUR_COMAPNY_NAME
WIISE_TENANT_ID=YOUR_TENANT_ID
WIISE_APP_ID=YOUR_APP_ID
WIISE_APP_SECRET=YOUR_APP_SECRET
WIISE_REDIRECT_URI=YOUR_REDIRECT_URKL

WIISE_PROVIDER=wiise
WIISE_SCOPES='Financials.ReadWrite.All offline_access'
WIISE_AUTHORITY=https://login.microsoftonline.com/
WIISE_AUTHORISE_ENDPOINT=/oauth2/authorize?resource=https://api.businesscentral.dynamics.com
WIISE_TOKEN_ENDPOINT=/oauth2/token?resource=https://api.businesscentral.dynamics.com
WIISE_RESOURCE=https://api.businesscentral.dynamics.com
WIISE_BASE_API_URL=https://wiise.api.bc.dynamics.com/v2.0/
```

3. Publish the migration file for the api_token database table

```
php artisan vendor:publish --provider="glasswalllab\wiiseconnector\WiseConnectorServiceProvider" --tag="migrations"
```

3. Run **php artisan migrate** to create the api_token database table

4. Optional: Export the welcome view blade file

```
php artisan vendor:publish --provider="glasswalllab\wiiseconnector\WiiseConnectorServiceProvider" --tag="views"
```

### Sample Usage (Laravel)

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use glasswalllab\wiiseconnector\wiiseconnector;

class WiiseTest extends Controller
{
    //Get list of jobs
    public function getJobs()
    {
        $wiise = new WiiseConnector();
        $response = $wiise->CallWebServiceSync('/Job_List?\$select=No,Description,Bill_to_Customer_No,Status','GET','');
        dd(json_decode($response)->value);
    }

    //Get list of job tasks for each job
    public function getJobsTasks()
    {
        $wiise = new WiiseConnector();
        $response = $wiise->CallWebServiceSync('/Job_Task_Lines?$select=Job_No,Job_Task_No,Description,Job_Task_Type&$filter=Job_Task_Type eq \'Posting\'','GET','');
        dd(json_decode($response)->value);
    }

    //create a new resource
    public function createResource($name,$rate)
    {
        //Ensure HOUR is a UOM iun Wiise
        $data = json_encode(array(
            'Name' => $name,
            'Direct_Unit_Cost' => round(floatval($rate),2),
            'Base_Unit_of_Measure' =>'HOUR'
        ));

        $wiise = new WiiseConnector();

        //Add Page 76 Resource Card as a Web Service - Called Resource
        $response = $wiise->CallWebServiceSync('/Resource','POST',$data);
        dd(json_decode($response)->No);
    }

    //Update Resource
    //updateResource('R0100','Stephen Reid Test3','27.53','false');
    public function updateResource($resourceNo,$name,$rate,$blocked)
    {
        if($blocked != 'true')
        {
            $blocked = 'false';
        }
        //Ensure HOUR is a UOM iun Wiise
        $data = json_encode(array(
            'Name' => $name,
            'Direct_Unit_Cost' => round(floatval($rate),2),
            'Base_Unit_of_Measure' =>'HOUR', //Ensure this is setup in Wiise
            'Blocked' => $blocked
        ));

        $wiise = new WiiseConnector();

        //Add Page 76 Resource Card as a Web Service - Called Resource
        $call = $wiise->CallWebServiceQueue('/Resource(No=\''.$resourceNo.'\')','PATCH',$data);
        return "Resource $resourceNo Updated";
    }

    //Create job journal line
    public function createJobJournal()
    {
        $data = json_encode(array(
            'Journal_Template_Name' => 'JOB', //Ensure this is setup in Wiise
            'Journal_Batch_Name' => 'DEFAULT', //Ensure this is setup in Wiise
            'Gen_Bus_Posting_Group' => 'DOMESTIC', //Ensure this is setup in Wiise
            'Document_No' => 'LJOB', //Ensure this is setup in Wiise
            'Posting_Date' => '2018-03-13', //test values
            'Job_No' => 'JOB00010', //test values
            'Job_Task_No' => '1010', //test values
            'No' => 'R0020', //test values
            'Quantity' => floatval(8.3)
        ));

        $wiise = new WiiseConnector();

        //Add Page 201 Job Journals as a Web Service - Called Job_Journals
        $call = $wiise->CallWebServiceQueue('/Job_Journals','POST',$data);
        return "Job Journal Exported";
    }
}
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email sreid@gwlab.com.au instead of using the issue tracker.