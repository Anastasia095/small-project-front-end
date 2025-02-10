## To get into db container
```docker exec -it mariadb bash```
### login to maria
```mysql -u root -p```
### Access @
```http://localhost:8080/```

## DB Stuff
## Users table
```CREATE TABLE `Users` (
  `ID` int(11) NOT NULL,
  `DateCreated` datetime NOT NULL DEFAULT current_timestamp(),
  `DateLastLoggedIn` datetime NOT NULL DEFAULT current_timestamp(),
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Login` varchar(50) NOT NULL,
  `Password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;```

## Contacts table

```CREATE TABLE `Contacts` (
  `ID` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Phone` varchar(50) NOT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `UserID` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;```
