### **Access the Application**
Open your browser and go to:  
```http://localhost:8080/```

## **Database Setup**
I didn't add any scripts to auto create tables so you would have to do this once on an initial start
### **Access the Database Container**
```docker exec -it mariadb bash```
### login to maria
```mysql -u root -p```

## create your tables
I think the smallproject database will create automatically
## Users table
````sql
USE smallproject;
CREATE TABLE `Users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DateCreated` datetime NOT NULL DEFAULT current_timestamp(),
  `DateLastLoggedIn` datetime NOT NULL DEFAULT current_timestamp(),
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Login` varchar(50) NOT NULL,
  `Password` varchar(250) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;```
````
## Contacts table

````sql
 USE smallproject; 
 CREATE TABLE `Contacts` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Phone` varchar(50) NOT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `UserID` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;```
````
