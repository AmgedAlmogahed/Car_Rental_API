<?php

class DbOperations {

    private $con;

    function __construct() {
        require_once dirname( __FILE__ ) . '/DbConnect.php';
        $db = new DbConnect;

        $this->con = $db->connect();

    }

    //accounts opreatoins

    public function createUser( $name, $password, $type ) {
        if ( !$this->isNameExist( $name ) ) {
            $stmt = $this->con->prepare( "INSERT INTO accounts (name, password, type) VALUES (?, ?, ?)" );
            $stmt->bind_param( "sss", $name, $password, $type );
            if ( $stmt->execute() ) {
                return USER_CREATED;

            } else {
                return USER_FAILURE;
            }
        }
        return USER_EXISTS;

    }
    //accounts opreatoins

    public function userLogin( $name, $password ) {
        if ( $this->isNameExist( $name ) ) {
            $hashed_password = $this->getUsersPasswordByName( $name );

            if ( password_verify( $password, $hashed_password ) ) {
                return USER_AUTHENTICATED;
            } else {
                return USER_PASSWORD_DO_NOT_MATCH;

            }
        } else {
            return USER_NOT_FOUND;

        }
    }
    //accounts opreatoins

    private function getUsersPasswordByName( $email ) {
        $stmt = $this->con->prepare( "SELECT password FROM accounts WHERE name = ?" );
        $stmt->bind_param( "s", $email );
        $stmt->execute();

        $stmt->bind_result( $password );
        $stmt->fetch();

        return $password;

    }
    //accounts opreatoins

    public function getAllUsers() {
        $stmt = $this->con->prepare( "SELECT id, name, type FROM accounts;" );
        $stmt->execute();

        $stmt->bind_result( $id, $name, $type );
        $users = array();

        while( $stmt->fetch() ) {

            $user = array();

            $user['id'] = $id;

            $user['name'] = $name;

            $user['type'] = $type;

            array_push( $users, $user );
        }

        return $users;

    }
    //accounts opreatoins

    public function getUserByName( $name ) {
        $stmt = $this->con->prepare( "SELECT id, name, type FROM accounts WHERE name = ?" );
        $stmt->bind_param( "s", $name );
        $stmt->execute();

        $stmt->bind_result( $id, $name, $type );
        $stmt->fetch();

        $user = array();

        $user['id'] = $id;

        $user['name'] = $name;

        $user['type'] = $type;

        return $user;

    }
    //accounts opreatoins

    public function updateUser( $name, $type, $id ) {
        $stmt = $this->con->prepare( "UPDATE accounts SET name = ?, type = ? WHERE id = ?" );
        $stmt->bind_param( "ssi", $name, $type, $id );
        if ( $stmt->execute() )
        return true;

        return false;

    }
    //accounts opreatoins

    public function updatePassword( $currentpassword, $newpassword, $name ) {
        $hashed_password = $this->getUsersPasswordByName( $name );

        if ( password_verify( $currentpassword, $hashed_password ) ) {

            $hash_password = password_hash( $newpassword, PASSWORD_DEFAULT );
            $stmt = $this->con->prepare( "UPDATE accounts SET password = ? WHERE name = ?" );
            $stmt->bind_param( "ss", $hash_password, $name );

            if ( $stmt->execute() )
            return PASSWORD_CHANGED;
            return PASSWORD_NOT_CHANGED;

        } else {
            return PASSWORD_DO_NOT_MATCH;

        }
    }
    //accounts opreatoins

    public function deleteUser( $id ) {
        $stmt = $this->con->prepare( "DELETE FROM accounts WHERE id = ?" );
        $stmt->bind_param( "i", $id );
        if ( $stmt->execute() )
        return true;

        return false;

    }
    //accounts opreatoins

    private function isNameExist( $name ) {
        $stmt = $this->con->prepare( "SELECT id FROM accounts WHERE name = ?" );
        $stmt->bind_param( "s", $name );
        $stmt->execute();

        $stmt->store_result();

        return $stmt->num_rows > 0;

    }

    //car opreations

    public function createCar( $name, $platNamuber, $baseNumber, $model, $type, $minRent, $color, $status, $image ) {
        if ( !$this->isCarExist( $platNamuber ) ) {
            $stmt = $this->con->prepare( "INSERT INTO car (name, plat_namber, base_number, model, type, color, status, image, min_rent) VALUES (?,?,?,?,?,?,?,?,?)" );
            $stmt->bind_param( "ssssssssi", $name, $platNamuber, $baseNumber, $model, $type, $color, $status, $image, $minRent );
            if ( $stmt->execute() ) {
                return CAR_CREATED;
            } else {
                return CAR_FAILURE;
            }
        }
        return CAR_EXISTS;
    }

    //car opreatoins

    public function getAllCars() {
        $stmt = $this->con->prepare( "SELECT * FROM car;" );
        $stmt->execute();

        $stmt->bind_result( $id, $name, $plat_namber, $base_number, $model, $type, $min_rent, $color, $status, $image );
        $cars = array();

        while( $stmt->fetch() ) {

            $car = array();

            $car['id'] = $id;

            $car['name'] = $name;

            $car['plat_namber'] = $plat_namber;
            $car['base_number'] = $base_number;
            $car['model'] = $model;
            $car['type'] = $type;
            $car['min_rent'] = $min_rent;
            $car['color'] = $color;
            $car['status'] = $status;

            $car['image'] = $image;

            array_push( $cars, $car );
        }

        return $cars;

    }

    //car opreatoins

    public function updateCar( $name, $platNamuber, $baseNumber, $model, $type, $minRent, $color, $status, $image, $id ) {
        $stmt = $this->con->prepare( "UPDATE car SET name = ?, plat_namber = ?, base_number = ?, model = ?, type = ?, min_rent = ?, color = ?, status = ?, image = ? WHERE id = ?" );
        $stmt->bind_param( "sssssisssi", $name, $platNamuber, $baseNumber, $model, $type, $minRent, $color, $status, $image, $id );
        if ( $stmt->execute() )
        return true;

        return false;

    }

    //car opreatoins

    public function getCarById( $id ) {
        $stmt = $this->con->prepare( "SELECT * FROM car WHERE id = ?;" );
        $stmt->bind_param( "s", $id );
        $stmt->execute();

        $stmt->bind_result( $id, $name, $platNamuber, $baseNumber, $model, $type, $minRent, $color, $status, $image );
        $stmt->fetch();

        $user = array();

        $user['id'] = $id;

        $user['name'] = $name;

        $user['plat_namber'] = $platNamuber;
        $user['base_number'] = $baseNumber;
        $user['model'] = $model;
        $user['type'] = $type;
        $user['min_rent'] = $minRent;
        $user['color'] = $color;
        $user['status'] = $status;

        $user['image'] = $image;

        return $user;

    }
    
    //car operations

    public function deleteCar( $id ) {
        $stmt = $this->con->prepare( "DELETE FROM car WHERE id = ?" );
        $stmt->bind_param( "i", $id );
        if ( $stmt->execute() )
        return true;

        return false;

    }

    //car opreations

    private function isCarExist( $carNamePlatNumber ) {
        $stmt = $this->con->prepare( "SELECT id FROM car WHERE plat_namber = ?" );
        $stmt->bind_param( "s", $carNamePlatNumber );
        $stmt->execute();

        $stmt->store_result();

        return $stmt->num_rows > 0;

    }

    //createCustomer opreations

    public function createCustomer( $name, $passportNumber, $image, $passport, $license ) {
        if ( !$this->isCustomerExist( $passportNumber ) ) {
            $stmt = $this->con->prepare( "INSERT INTO customers (name, passport_number, image, passport, license) VALUES (?,?,?,?,?)" );
            $stmt->bind_param( "sssss", $name, $passportNumber, $image, $passport, $license );
            if ( $stmt->execute() ) {
                return CUSTOMER_CREATED;
            } else {
                return CUSTOMER_FAILURE;
            }
        }
        return CUSTOMER_EXISTS;
    }

    //getAllCustomers opreatoins

    public function getAllCustomers() {
        $stmt = $this->con->prepare( "SELECT * FROM customers;" );
        $stmt->execute();

        $stmt->bind_result( $id, $name, $passportNumber, $image, $passport, $license );
        $customers = array();

        while( $stmt->fetch() ) {

            $customer = array();

            $customer['id'] = $id;

            $customer['name'] = $name;

            $customer['passport_number'] = $passportNumber;
            $customer['image'] = $image;
            $customer['passport'] = $passport;
            $customer['license'] = $license;

            array_push( $customers, $customer );
        }

        return $customers;

    }

    //updateCustomer opreatoin

    public function updateCustomer( $name, $passportNumber, $image, $passport, $license, $id ) {
        $stmt = $this->con->prepare( "UPDATE customers SET name = ?, passport_number = ?, image = ?, passport = ?, license = ? WHERE id = ?" );
        $stmt->bind_param( "sssssi", $name, $passportNumber, $image, $passport, $license, $id );
        if ( $stmt->execute() )
        return true;

        return false;

    }

    //getCustomerById opreatoin

    public function getCustomerById( $id ) {
        $stmt = $this->con->prepare( "SELECT * FROM customers WHERE id = ?;" );
        $stmt->bind_param( "i", $id );
        $stmt->execute();

        $stmt->bind_result( $id, $name, $passportNumber, $image, $passport, $license );
        $stmt->fetch();

        $customer = array();

        $customer['id'] = $id;

        $customer['name'] = $name;

        $customer['passport_number'] = $passportNumber;
        $customer['image'] = $image;
        $customer['passport'] = $passport;
        $customer['license'] = $license;

        return $customer;

    }

    //deleteCustomer opreatoin

    public function deleteCustomer( $id ) {
        $stmt = $this->con->prepare( "DELETE FROM customers WHERE id = ?" );
        $stmt->bind_param( "i", $id );
        if ( $stmt->execute() )
        return true;

        return false;

    }

    //isCustomerExist opreatoin
    
    private function isCustomerExist( $passportNumber ) {
        $stmt = $this->con->prepare( "SELECT id FROM customers WHERE passport_number = ?" );
        $stmt->bind_param( "s", $passportNumber );
        $stmt->execute();

        $stmt->store_result();

        return $stmt->num_rows > 0;

    }
    
    
     public function createService($carId, $name, $prcie, $date) {
            $stmt = $this->con->prepare( "INSERT INTO service (car_id , name, price, date) VALUES (?,?,?,?)");
            $stmt->bind_param( "isis", $carId, $name, $prcie,$date );
            if($stmt->execute())
                return true;
            return false;
            
    }

    //getAllServices opreatoins

    public function getAllServices() {
        $stmt = $this->con->prepare( "SELECT * FROM service;" );
        $stmt->execute();

        $stmt->bind_result($id, $carId, $name, $prcie, $date);
        $services = array();

        while( $stmt->fetch() ) {

            $service = array();

            $service['id'] = $id;
            $service['car_id'] = $carId;
            $service['name'] = $name;
            $service['prcie'] = $prcie;
            $service['date'] = $date;

            array_push( $services, $service );
        }

        return $services;

    }

    //updateService opreatoin

    public function updateService($name, $price, $date, $id ) {
        $stmt = $this->con->prepare( "UPDATE service SET name = ?, price = ?, date = ? WHERE id = ?" );
        $stmt->bind_param( "sisi", $name, $price, $date, $id );
        if ($stmt->execute())
        return true;

        return false;

    }

    //getServiceById opreatoin

    public function getServiceById( $id ) {
        $stmt = $this->con->prepare( "SELECT * FROM service WHERE id = ?;" );
        $stmt->bind_param( "i", $id );
        $stmt->execute();
        $stmt->bind_result($id, $carId, $name, $prcie, $date);
        $stmt->fetch();

        $service = array();

        $service['id'] = $id;
        $service['car_id'] = $carId;
        $service['name'] = $name;
        $service['prcie'] = $prcie;
        $service['date'] = $date;t;

        return $service;

    }

    //deleteService opreatoin

    public function deleteService($id) {
        $stmt = $this->con->prepare( "DELETE FROM service WHERE id = ?" );
        $stmt->bind_param( "i", $id );
        if ( $stmt->execute() )
        return true;

        return false;

    }
    
    //createRent

    public function createRent($carId , $customerId , $startDate, $endDate, $price, $moveAgent, $details) {
        $stmt = $this->con->prepare( "INSERT INTO renting (car_id , customer_id , start_date, end_date, price, move_agent, details) VALUES (?,?,?,?,?,?,?)");
        $stmt->bind_param( "iississ",$carId , $customerId , $startDate, $endDate, $price, $moveAgent, $details);
        if($stmt->execute())
            return true;
        return false;
        
    }
    
    //getAllRents opreatoins

    public function getAllRents() {
    $stmt = $this->con->prepare( "SELECT * FROM renting;" );
    $stmt->execute();
    $stmt->bind_result($id, $carId , $customerId , $startDate, $endDate, $price, $move_moveAgentagent, $details);
    $rents = array();
    while( $stmt->fetch() ) {
        $rent = array();
        $rent['id'] = $id;
        $rent['car_id'] = $carId;
        $rent['customer_id'] = $customerId;
        $rent['start_date'] = $startDate;
        $rent['end_date'] = $endDate;
        $rent['price'] = $price;
        $rent['move_agent'] = $moveAgent;
        $rent['details'] = $details;

        array_push( $rents, $rent );
    }
    return $rents;
}

     //updateRent opreatoin
     
     public function updateRent($endDate, $price, $id) {
        $stmt = $this->con->prepare( "UPDATE renting SET end_date = ?, price = ? WHERE id = ?" );
        $stmt->bind_param("sii", $endDate, $price, $id );
            if ($stmt->execute())
                return true;
                
                return false;
}
//getRentById opreatoin
public function getRentById($id) {
    $stmt = $this->con->prepare( "SELECT * FROM renting WHERE id = ?;" );
    $stmt->bind_param( "i", $id );
    $stmt->execute();
    $stmt->bind_result($id, $carId , $customerId , $startDate, $endDate, $price, $moveAgent, $details);
    $stmt->fetch();
   
    $rent = array();
    $rent['id'] = $id;
    $rent['car_id'] = $carId;
    $rent['customer_id'] = $customerId;
    $rent['start_date'] = $startDate;
    $rent['end_date'] = $endDate;
    $rent['price'] = $price;
    $rent['move_agent'] = $moveAgent;
    $rent['details'] = $details;

    return $rent;
}
//deleteRents opreatoin
public function deleteRent($id) {
    $stmt = $this->con->prepare( "DELETE FROM renting WHERE id = ?" );
    $stmt->bind_param( "i", $id );
    if ($stmt->execute())
    return true;
    return false;
}
    

    
}