<?php
//Script to randomly generate entries in a db. createCompany calls createCustomer a random number of times.
//createCustomer calls createLicence a random number of times. A company can have multiple customers,
//who in turn can have multiple licences. On the bottom, createCompany is being called a predetermined
//number of times.

//COMPANY
// com_nr indexer
$company_index = 0;

//variable for random customer id
function randomCompanyId() {
    return rand(100000, 199999);
};

function createCompany() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "licensecenter";

    //Create random kd_id
    $company_id = randomCompanyId();

    global $company_index;
    $company_index += 1;

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO company (com_id, com_nr, com_name)
    VALUES ($company_id, $company_index, 'Company XXXX')";

    if ($conn->query($sql) === TRUE) {
        echo "New company record created successfully \n";
        $random_number_of_customers = rand(1, 3);
        echo "How many customers? $random_number_of_customers \n";
        $count = 1;
        while ($count <= $random_number_of_customers) {
            createCustomer($company_index);
            $count += 1;
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();}

//KUNDE

// kd_nr indexer
$customer_index = 0;

//variable for random customer id
function randomCustomerId() {
    return rand(200000, 299999);
};

function createCustomer($company_index) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "licensecenter";

    //Create random kd_id
    $customer_id = randomCustomerId();

    global $customer_index;
    $customer_index += 1;

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO kunden (kd_id, kd_nr, kd_ansprechpartner, com_id)
    VALUES ($customer_id, $customer_index, 'Herr XXXX', $company_index)";

    if ($conn->query($sql) === TRUE) {
        echo "New customer record created successfully \n";
        $random_number_of_lics = rand(1, 3);
        echo "How many licenses? $random_number_of_lics \n";
        $count = 1;
        while ($count <= $random_number_of_lics) {
            createLicence($customer_index);
            $count += 1;
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();}

//LICENCE

//Create random date
function randomDateInRange($start, $end) {
    $randomTimestamp = mt_rand($start->getTimestamp(), $end->getTimestamp());
    $randomDate = new DateTime();
    $randomDate->setTimestamp($randomTimestamp);
    $result = $randomDate->format('Y-m-d');
    return $result;
}

//Create random licence id
function randomLicenceId() {
    return rand(300000, 399999);
};


function createLicence($liz_kd) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "licensecenter";

    //variables for licence date randomization
    $licence_start = new DateTime('2020-05-31');
    $licence_end = new DateTime('2025-12-31');
    $licence_date_result = randomDateInRange($licence_start, $licence_end);

    //create random licence id
    $licence_id = randomLicenceId();
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO lizenz (liz_id, liz_kd, liz_valid_bis)
    VALUES ($licence_id, $liz_kd, '$licence_date_result')";

    if ($conn->query($sql) === TRUE) {
        echo "New licence record created successfully\n";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();}

    $company_count = 1000;
    $count = 1;
    while ($count <= $company_count) {
        createCompany();
        $count += 1;
    }
?>

