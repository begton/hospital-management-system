CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'doctor', 'receptionist', 'labTech') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE reports (
    report_id INT AUTO_INCREMENT PRIMARY KEY,
    report_type VARCHAR(100) NOT NULL,
    content TEXT NOT NULL,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

CREATE TABLE patients (
    patients_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    gender ENUM('male', 'female') NOT NULL,
    dob DATE,
    phone VARCHAR(20),
    email VARCHAR(100),
    address TEXT NOT NULL,
    insures VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


Setting environment for using XAMPP for Windows.
Am__Begton@DESKTOP-645OJ1G c:\xampp
# mysql -u root
Welcome to the MariaDB monitor.  Commands end with ; or \g.
Your MariaDB connection id is 21
Server version: 10.4.32-MariaDB mariadb.org binary distribution

Copyright (c) 2000, 2018, Oracle, MariaDB Corporation Ab and others.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

MariaDB [(none)]> SHOW DATABASES;
+---------------------+
| Database            |
+---------------------+
| berwashop           |
| develop             |
| hospital_management |
| hotelmanagement     |
| information_schema  |
| my db               |
| mysql               |
| performance_schema  |
| phpmyadmin          |
| test                |
+---------------------+
10 rows in set (0.001 sec)

MariaDB [(none)]> Bye

Am__Begton@DESKTOP-645OJ1G c:\xampp
# mysql -u root
Welcome to the MariaDB monitor.  Commands end with ; or \g.
Your MariaDB connection id is 22
Server version: 10.4.32-MariaDB mariadb.org binary distribution

Copyright (c) 2000, 2018, Oracle, MariaDB Corporation Ab and others.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

MariaDB [(none)]> SHOW DATABASES;
+---------------------+
| Database            |
+---------------------+
| berwashop           |
| develop             |
| hospital_management |
| hotelmanagement     |
| information_schema  |
| my db               |
| mysql               |
| performance_schema  |
| phpmyadmin          |
| test                |
+---------------------+
10 rows in set (0.001 sec)

MariaDB [(none)]> USE hospital_management;
Database changed
MariaDB [hospital_management]> SHOW TABLES;
+-------------------------------+
| Tables_in_hospital_management |
+-------------------------------+
| appointments                  |
| billing                       |
| doctor_fees                   |
| doctors                       |
| lab_requests                  |
| lab_tests                     |
| laboratory_tests              |
| medical_records               |
| notifications                 |
| patients                      |
| reports                       |
| services                      |
| users                         |
+-------------------------------+
13 rows in set (0.001 sec)

MariaDB [hospital_management]> SELECT *FROM users;
+---------+--------------+------------------------------+--------------------------------------------------------------+--------------+----------------------------+---------------------+
| user_id | username     | email                        | password                                                     | role         | full_name                  | created_at          |
+---------+--------------+------------------------------+--------------------------------------------------------------+--------------+----------------------------+---------------------+
|       2 | alice        | alice@gmail.com              | $2y$10$glsrqS.8hrfcc3r9LwQROuMzlVkfw/rmBXyCSbp1sBM2GFuWi/z6m | doctor       | Alice Johnson              | 2025-04-11 10:33:29 |
|       3 | bob          | bob@gmail.com                | $2y$10$FkvJ4gN13Da2fcxZJY0xQOHz6yjPEqodEvAYYq9TKDZEzhpI9D1SC | receptionist | Bob Smith                  | 2025-04-11 10:33:29 |
|       4 | charlie      | charlie@gmail.com            | $2y$10$z29Zo/NaefgoSO.7EAmJNubVoyxZOaIyVWFuqpqRrzNmrEpw11Hti | labTech      | Charlie Brown              | 2025-04-11 10:33:29 |
|       5 | aime         | aime@gmail.com               | $2y$10$eTPmVsRN9ALr8sIgQ9hnreetSaToMYipDOJz1Q.Vsx06NTBUwP4.u | doctor       | aime jean                  | 2025-04-11 15:45:13 |
|       6 | begton       | begton@gmail.com             | $2y$10$uuUBxW.JC0emMORINKvhyeske2hhTmK7cNDJYRm3VKwWDXN3tyvn6 | admin        | begton own                 | 2025-04-11 15:53:47 |
|       7 | sum          | sum@gmail.com                | $2y$10$s7tdCwcg3yTzJPweLAjd5.1czq20WD.NPKQ8pL7WJSAh.QIzhN.zi | receptionist | sum bill                   | 2025-04-11 16:11:56 |
|       8 | bill         | bill@gmail.com               | $2y$10$q0Q4.9xMnj8dKKyIuF8breqgHCl4qNIuX0c7b.aofHvfAEV4WWMUy | labTech      | bill gates                 | 2025-04-11 16:16:06 |
|       9 | [Dr. Nadia]  | [Dr.NadiaUwase@gmail.com]    | $2y$10$JAKUd.oqLPx63jpTeK5CuutkoTNYi0cI6f.CPjLSyFisSEqNRoHPO | doctor       | Dr. Nadia Uwase            | 0000-00-00 00:00:00 |
|      10 | prof.eugene  | eugene.hakizimana@gmail.com  | $2y$10$OZCY4iD6cezVcWoGHkPhq..4c5Ric2zBWD/QVD2pAz0bt3iKpUD3y | doctor       | Prof. Eugene Hakizimana    | 2025-04-16 17:04:08 |
|      11 | prof.diane   | diane.mukamana@gmail.com     | $2y$10$kgMSBgNVZJFyZYSFFXY4geQVC9HwN/Y3neogH27ov/Nlh7hoDqqtS | doctor       | Prof. Diane Mukamana       | 2025-04-16 17:04:08 |
|      12 | prof.clement | jean.clement@gmail.com       | $2y$10$KzO2Jm3T.WLDtasuJ6kppe6mPoH0SYA6NIwh43Mw6dih/tSYoe2W. | doctor       | Prof. Jean Clement         | 2025-04-16 17:04:08 |
|      13 | dr.ingabire  | clarisse.ingabire@gmail.com  | $2y$10$wpJJPZrS57P8ErCb9zzQ7.hU74GywDsmXlK6Vjurmte7iPOIlIPAC | doctor       | Dr. Clarisse Ingabire      | 2025-04-16 17:04:08 |
|      14 | dr.uwase     | nadia.uwase@gmail.com        | $2y$10$g8hMEhl9eVRm2IiKWjQTyeV6ngTB5.cDU.gbuzeQeeSX2c4aRGogS | doctor       | Dr. Nadia Uwase            | 2025-04-16 17:04:08 |
|      15 | dr.claude    | jean.claude@gmail.com        | $2y$10$gU40psZY/15H.8RBWlVDlOqXFmMwa.7q5i8wOKW8N9UMIQDHSFOca | doctor       | Dr. Jean Claude Niyonsenga | 2025-04-16 17:04:08 |
|      16 | prof.uwineza | jacques.uwineza@gmail.com    | $2y$10$7iG.cY.FQmWNA.G0b2myvehuvc0GzIeodV1cXTg5V1uGONpca8Zyi | doctor       | Prof. Jacques Uwineza      | 2025-04-16 17:04:08 |
|      17 | Dr.Alice     |  Dr. Alice_Umutoni@gmail.com | $2y$10$EYUqRYdJRoC.lPXXDwVC/ufXALu56C5HQ64T01bJKB3bSXjgMfbyq | doctor       | Dr.Alice Umutoni           | 2025-04-20 14:39:40 |
+---------+--------------+------------------------------+--------------------------------------------------------------+--------------+----------------------------+---------------------+
16 rows in set (0.438 sec)

MariaDB [hospital_management]> SELECT * FROM  doctors;
+-----------+---------+------------------+------------+----------------------------+
| doctor_id | user_id | specialization   | phone      | full_name                  |
+-----------+---------+------------------+------------+----------------------------+
|        12 |       2 | Dentistry        | 0787778889 | Dr. Alice Umutoni          |
|        13 |       5 | Cardiology       | 0788811123 | Prof. Aime Uwimana         |
|        14 |       2 | Cardiology       | 0786111112 | Prof. Jacques Uwineza      |
|        15 |       3 | Dentistry        | 0788413151 | Prof. Eugene Hakizimana    |
|        16 |       4 | General Medicine | 0788811123 | Prof. Diane Mukamana       |
|        17 |       5 | General Medicine | 0788870051 | Dr. Jean Claude Niyonsenga |
|        18 |       6 | Gynecology       | 0783888901 | Prof. Jean Clement         |
|        19 |       7 | Gynecology       | 0783888900 | Dr. Clarisse Ingabire      |
|        20 |       8 | Orthopedics      | 0785093332 | Dr. Nadia Uwase            |
+-----------+---------+------------------+------------+----------------------------+
9 rows in set (0.001 sec)

MariaDB [hospital_management]> ALTER TABLE lab_tests ADD COLUMN department VARCHAR(100);
Query OK, 0 rows affected (0.524 sec)
Records: 0  Duplicates: 0  Warnings: 0

MariaDB [hospital_management]> INSERT INTO lab_tests (test_name, cost, department) VALUES
    -> ('Dental X-Ray', 8000, 'Dentistry'),
    -> ('Oral Swab Culture', 5000, 'Dentistry');
Query OK, 2 rows affected (0.139 sec)
Records: 2  Duplicates: 0  Warnings: 0

MariaDB [hospital_management]> INSERT INTO lab_tests (test_name, cost, department) VALUES
    -> ('ECG', 10000, 'Cardiology'),
    -> ('Echocardiogram', 20000, 'Cardiology'),
    -> ('Cholesterol Test', 7000, 'Cardiology'),
    -> ('Troponin Test', 9000, 'Cardiology');
Query OK, 4 rows affected (0.086 sec)
Records: 4  Duplicates: 0  Warnings: 0

MariaDB [hospital_management]> ALTER TABLE lab_tests DROP COLUMN department VARCHAR(100);
ERROR 1064 (42000): You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'VARCHAR(100)' at line 1
MariaDB [hospital_management]> ALTER TABLE lab_tests DROP COLUMN department;
Query OK, 0 rows affected (0.241 sec)
Records: 0  Duplicates: 0  Warnings: 0

MariaDB [hospital_management]> SELECT * FROM lab_tests;
+---------+-------------------+----------+
| test_id | test_name         | cost     |
+---------+-------------------+----------+
|       1 | Dental X-Ray      |  8000.00 |
|       2 | Oral Swab Culture |  5000.00 |
|       3 | ECG               | 10000.00 |
|       4 | Echocardiogram    | 20000.00 |
|       5 | Cholesterol Test  |  7000.00 |
|       6 | Troponin Test     |  9000.00 |
+---------+-------------------+----------+
6 rows in set (0.000 sec)

MariaDB [hospital_management]> ALTER TABLE lab_tests ADD COLUMN department VARCHAR(100);
Query OK, 0 rows affected (0.136 sec)
Records: 0  Duplicates: 0  Warnings: 0

MariaDB [hospital_management]> INSERT INTO lab_tests (test_name, cost, department) VALUES
    -> ('Dental X-Ray', 8000, 'Dentistry'),
    -> ('Oral Swab Culture', 5000, 'Dentistry');
Query OK, 2 rows affected (0.081 sec)
Records: 2  Duplicates: 0  Warnings: 0

MariaDB [hospital_management]> INSERT INTO lab_tests (test_name, cost, department) VALUES
    -> ('ECG', 10000, 'Cardiology'),
    -> ('Echocardiogram', 20000, 'Cardiology'),
    -> ('Cholesterol Test', 7000, 'Cardiology'),
    -> ('Troponin Test', 9000, 'Cardiology');
Query OK, 4 rows affected (0.103 sec)
Records: 4  Duplicates: 0  Warnings: 0

MariaDB [hospital_management]> INSERT INTO lab_tests (test_name, cost, department) VALUES
    -> ('Blood Pressure Check', 2000, 'General Medicine'),
    -> ('Complete Blood Count (CBC)', 5000, 'General Medicine'),
    -> ('Blood Sugar Test', 3000, 'General Medicine'),
    -> ('Urinalysis', 3500, 'General Medicine');
Query OK, 4 rows affected (0.084 sec)
Records: 4  Duplicates: 0  Warnings: 0

MariaDB [hospital_management]> INSERT INTO lab_tests (test_name, cost, department) VALUES
    -> ('Pregnancy Test', 2000, 'Gynecology'),
    -> ('PAP Smear', 6000, 'Gynecology'),
    -> ('Hormone Test', 10000, 'Gynecology'),
    -> ('Ultrasound Scan', 15000, 'Gynecology');
Query OK, 4 rows affected (0.147 sec)
Records: 4  Duplicates: 0  Warnings: 0

MariaDB [hospital_management]> INSERT INTO lab_tests (test_name, cost, department) VALUES
    -> ('Bone Density Test', 12000, 'Orthopedics'),
    -> ('X-Ray', 7000, 'Orthopedics'),
    -> ('Calcium Test', 5000, 'Orthopedics');
Query OK, 3 rows affected (0.114 sec)
Records: 3  Duplicates: 0  Warnings: 0

MariaDB [hospital_management]> SELECT * FROM lab_tests;
+---------+----------------------------+----------+------------------+
| test_id | test_name                  | cost     | department       |
+---------+----------------------------+----------+------------------+
|       1 | Dental X-Ray               |  8000.00 | NULL             |
|       2 | Oral Swab Culture          |  5000.00 | NULL             |
|       3 | ECG                        | 10000.00 | NULL             |
|       4 | Echocardiogram             | 20000.00 | NULL             |
|       5 | Cholesterol Test           |  7000.00 | NULL             |
|       6 | Troponin Test              |  9000.00 | NULL             |
|       7 | Dental X-Ray               |  8000.00 | Dentistry        |
|       8 | Oral Swab Culture          |  5000.00 | Dentistry        |
|       9 | ECG                        | 10000.00 | Cardiology       |
|      10 | Echocardiogram             | 20000.00 | Cardiology       |
|      11 | Cholesterol Test           |  7000.00 | Cardiology       |
|      12 | Troponin Test              |  9000.00 | Cardiology       |
|      13 | Blood Pressure Check       |  2000.00 | General Medicine |
|      14 | Complete Blood Count (CBC) |  5000.00 | General Medicine |
|      15 | Blood Sugar Test           |  3000.00 | General Medicine |
|      16 | Urinalysis                 |  3500.00 | General Medicine |
|      17 | Pregnancy Test             |  2000.00 | Gynecology       |
|      18 | PAP Smear                  |  6000.00 | Gynecology       |
|      19 | Hormone Test               | 10000.00 | Gynecology       |
|      20 | Ultrasound Scan            | 15000.00 | Gynecology       |
|      21 | Bone Density Test          | 12000.00 | Orthopedics      |
|      22 | X-Ray                      |  7000.00 | Orthopedics      |
|      23 | Calcium Test               |  5000.00 | Orthopedics      |
+---------+----------------------------+----------+------------------+
23 rows in set (0.000 sec)

MariaDB [hospital_management]> UPDATE lab_tests SET department = 'Dentistry' WHERE test_name IN ('Dental X-Ray', 'Oral Swab Culture');
Query OK, 2 rows affected (0.091 sec)
Rows matched: 4  Changed: 2  Warnings: 0

MariaDB [hospital_management]> UPDATE lab_tests SET department = 'Cardiology' WHERE test_name IN ('ECG', 'Echocardiogram', 'Cholesterol Test', 'Troponin Test');
Query OK, 4 rows affected (0.040 sec)
Rows matched: 8  Changed: 4  Warnings: 0

MariaDB [hospital_management]> DESCRIBE billing;
+----------------+---------------------------------+------+-----+---------------------+----------------+
| Field          | Type                            | Null | Key | Default             | Extra          |
+----------------+---------------------------------+------+-----+---------------------+----------------+
| bill_id        | int(11)                         | NO   | PRI | NULL                | auto_increment |
| patient_id     | int(11)                         | NO   | MUL | NULL                |                |
| total_amount   | decimal(10,2)                   | YES  |     | NULL                |                |
| paid_amount    | decimal(10,2)                   | YES  |     | 0.00                |                |
| payment_method | varchar(50)                     | YES  |     | NULL                |                |
| status         | enum('unpaid','partial','paid') | YES  |     | unpaid              |                |
| created_at     | timestamp                       | NO   |     | current_timestamp() |                |
+----------------+---------------------------------+------+-----+---------------------+----------------+
7 rows in set (0.016 sec)

MariaDB [hospital_management]> Bye

Am__Begton@DESKTOP-645OJ1G c:\xampp
# mysql -u root
Welcome to the MariaDB monitor.  Commands end with ; or \g.
Your MariaDB connection id is 99
Server version: 10.4.32-MariaDB mariadb.org binary distribution

Copyright (c) 2000, 2018, Oracle, MariaDB Corporation Ab and others.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

MariaDB [(none)]> USE hospital_management;
Database changed
MariaDB [hospital_management]> SHOW TABLES;
+-------------------------------+
| Tables_in_hospital_management |
+-------------------------------+
| appointments                  |
| billing                       |
| doctor_fees                   |
| doctors                       |
| lab_requests                  |
| lab_tests                     |
| laboratory_tests              |
| medical_records               |
| notifications                 |
| patients                      |
| reports                       |
| services                      |
| users                         |
+-------------------------------+
13 rows in set (0.001 sec)

MariaDB [hospital_management]> SELECT * FROM users;
+---------+--------------+------------------------------+--------------------------------------------------------------+--------------+----------------------------+---------------------+
| user_id | username     | email                        | password                                                     | role         | full_name                  | created_at          |
+---------+--------------+------------------------------+--------------------------------------------------------------+--------------+----------------------------+---------------------+
|       2 | alice        | alice@gmail.com              | $2y$10$glsrqS.8hrfcc3r9LwQROuMzlVkfw/rmBXyCSbp1sBM2GFuWi/z6m | doctor       | Alice Johnson              | 2025-04-11 10:33:29 |
|       3 | bob          | bob@gmail.com                | $2y$10$FkvJ4gN13Da2fcxZJY0xQOHz6yjPEqodEvAYYq9TKDZEzhpI9D1SC | receptionist | Bob Smith                  | 2025-04-11 10:33:29 |
|       4 | charlie      | charlie@gmail.com            | $2y$10$z29Zo/NaefgoSO.7EAmJNubVoyxZOaIyVWFuqpqRrzNmrEpw11Hti | labTech      | Charlie Brown              | 2025-04-11 10:33:29 |
|       5 | aime         | aime@gmail.com               | $2y$10$eTPmVsRN9ALr8sIgQ9hnreetSaToMYipDOJz1Q.Vsx06NTBUwP4.u | doctor       | aime jean                  | 2025-04-11 15:45:13 |
|       6 | begton       | begton@gmail.com             | $2y$10$uuUBxW.JC0emMORINKvhyeske2hhTmK7cNDJYRm3VKwWDXN3tyvn6 | admin        | begton own                 | 2025-04-11 15:53:47 |
|       7 | sum          | sum@gmail.com                | $2y$10$s7tdCwcg3yTzJPweLAjd5.1czq20WD.NPKQ8pL7WJSAh.QIzhN.zi | receptionist | sum bill                   | 2025-04-11 16:11:56 |
|       8 | bill         | bill@gmail.com               | $2y$10$q0Q4.9xMnj8dKKyIuF8breqgHCl4qNIuX0c7b.aofHvfAEV4WWMUy | labTech      | bill gates                 | 2025-04-11 16:16:06 |
|       9 | [Dr. Nadia]  | [Dr.NadiaUwase@gmail.com]    | $2y$10$JAKUd.oqLPx63jpTeK5CuutkoTNYi0cI6f.CPjLSyFisSEqNRoHPO | doctor       | Dr. Nadia Uwase            | 0000-00-00 00:00:00 |
|      10 | prof.eugene  | eugene.hakizimana@gmail.com  | $2y$10$OZCY4iD6cezVcWoGHkPhq..4c5Ric2zBWD/QVD2pAz0bt3iKpUD3y | doctor       | Prof. Eugene Hakizimana    | 2025-04-16 17:04:08 |
|      11 | prof.diane   | diane.mukamana@gmail.com     | $2y$10$kgMSBgNVZJFyZYSFFXY4geQVC9HwN/Y3neogH27ov/Nlh7hoDqqtS | doctor       | Prof. Diane Mukamana       | 2025-04-16 17:04:08 |
|      12 | prof.clement | jean.clement@gmail.com       | $2y$10$KzO2Jm3T.WLDtasuJ6kppe6mPoH0SYA6NIwh43Mw6dih/tSYoe2W. | doctor       | Prof. Jean Clement         | 2025-04-16 17:04:08 |
|      13 | dr.ingabire  | clarisse.ingabire@gmail.com  | $2y$10$wpJJPZrS57P8ErCb9zzQ7.hU74GywDsmXlK6Vjurmte7iPOIlIPAC | doctor       | Dr. Clarisse Ingabire      | 2025-04-16 17:04:08 |
|      14 | dr.uwase     | nadia.uwase@gmail.com        | $2y$10$g8hMEhl9eVRm2IiKWjQTyeV6ngTB5.cDU.gbuzeQeeSX2c4aRGogS | doctor       | Dr. Nadia Uwase            | 2025-04-16 17:04:08 |
|      15 | dr.claude    | jean.claude@gmail.com        | $2y$10$gU40psZY/15H.8RBWlVDlOqXFmMwa.7q5i8wOKW8N9UMIQDHSFOca | doctor       | Dr. Jean Claude Niyonsenga | 2025-04-16 17:04:08 |
|      16 | prof.uwineza | jacques.uwineza@gmail.com    | $2y$10$7iG.cY.FQmWNA.G0b2myvehuvc0GzIeodV1cXTg5V1uGONpca8Zyi | doctor       | Prof. Jacques Uwineza      | 2025-04-16 17:04:08 |
|      17 | Dr.Alice     |  Dr. Alice_Umutoni@gmail.com | $2y$10$EYUqRYdJRoC.lPXXDwVC/ufXALu56C5HQ64T01bJKB3bSXjgMfbyq | doctor       | Dr.Alice Umutoni           | 2025-04-20 14:39:40 |
+---------+--------------+------------------------------+--------------------------------------------------------------+--------------+----------------------------+---------------------+
16 rows in set (0.000 sec)

MariaDB [hospital_management]> SELECT * FROM medical_records;
+-----------+-------------+-----------+-----------+-----------+------------+-------+---------+
| record_id | patients_id | doctor_id | diagnosis | treatment | visit_date | notes | status  |
+-----------+-------------+-----------+-----------+-----------+------------+-------+---------+
|         1 |          29 |        13 |           |           | 2025-04-21 |       | Pending |
|         2 |          31 |        12 |           |           | 2025-04-22 |       | Pending |
+-----------+-------------+-----------+-----------+-----------+------------+-------+---------+
2 rows in set (0.001 sec)

MariaDB [hospital_management]> SELECT * FROM medical_records;
+-----------+-------------+-----------+-----------+-----------+------------+-------+---------+
| record_id | patients_id | doctor_id | diagnosis | treatment | visit_date | notes | status  |
+-----------+-------------+-----------+-----------+-----------+------------+-------+---------+
|         1 |          29 |        13 |           |           | 2025-04-21 |       | Pending |
|         2 |          31 |        12 |           |           | 2025-04-22 |       | Pending |
+-----------+-------------+-----------+-----------+-----------+------------+-------+---------+
2 rows in set (0.000 sec)

MariaDB [hospital_management]> SELECT * FROM doctors;
+-----------+---------+------------------+------------+----------------------------+
| doctor_id | user_id | specialization   | phone      | full_name                  |
+-----------+---------+------------------+------------+----------------------------+
|        12 |       2 | Dentistry        | 0787778889 | Dr. Alice Umutoni          |
|        13 |       5 | Cardiology       | 0788811123 | Prof. Aime Uwimana         |
|        14 |       2 | Cardiology       | 0786111112 | Prof. Jacques Uwineza      |
|        15 |       3 | Dentistry        | 0788413151 | Prof. Eugene Hakizimana    |
|        16 |       4 | General Medicine | 0788811123 | Prof. Diane Mukamana       |
|        17 |       5 | General Medicine | 0788870051 | Dr. Jean Claude Niyonsenga |
|        18 |       6 | Gynecology       | 0783888901 | Prof. Jean Clement         |
|        19 |       7 | Gynecology       | 0783888900 | Dr. Clarisse Ingabire      |
|        20 |       8 | Orthopedics      | 0785093332 | Dr. Nadia Uwase            |
+-----------+---------+------------------+------------+----------------------------+
9 rows in set (0.000 sec)

MariaDB [hospital_management]> SELECT * FROM medical_records;
+-----------+-------------+-----------+-----------+-----------+------------+-------+---------+
| record_id | patients_id | doctor_id | diagnosis | treatment | visit_date | notes | status  |
+-----------+-------------+-----------+-----------+-----------+------------+-------+---------+
|         1 |          29 |        13 |           |           | 2025-04-21 |       | Pending |
|         2 |          31 |        12 |           |           | 2025-04-22 |       | Pending |
|         3 |          32 |        15 |           |           | 2025-04-22 |       | Pending |
+-----------+-------------+-----------+-----------+-----------+------------+-------+---------+
3 rows in set (0.000 sec)

MariaDB [hospital_management]>  SELECT p.full_name, p.gender, p.dob, p.phone, p.email, p.address, p.insures, m.status, m.visit_date AS date_recorded
    ->         FROM medical_records m
    ->         JOIN patients p ON p.patients_id = m.patients_id
    ->         WHERE m.doctor_id = ?
    ->         ORDER BY m.visit_date DESC
    ->     ");
    ">     ");
ERROR 1064 (42000): You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near '?
        ORDER BY m.visit_date DESC
    ");
    ")' at line 4
MariaDB [hospital_management]>         SELECT p.full_name, p.gender, p.dob, p.phone, p.email, p.address, p.insures, m.status, m.visit_date AS date_recorded
    ->         FROM medical_records m
    ->         JOIN patients p ON p.patients_id = m.patients_id
    ->         WHERE m.doctor_id = ?
    ->         ORDER BY m.visit_date DESC
    ->     );
ERROR 1064 (42000): You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near '?
        ORDER BY m.visit_date DESC
    )' at line 4
MariaDB [hospital_management]>         SELECT p.full_name, p.gender, p.dob, p.phone, p.email, p.address, p.insures, m.status, m.visit_date AS date_recorded
    ->         FROM medical_records m
    ->         JOIN patients p ON p.patients_id = m.patients_id
    ->         WHERE m.doctor_id = ?
    ->         ORDER BY m.visit_date DESC;
ERROR 1064 (42000): You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near '?
        ORDER BY m.visit_date DESC' at line 4
MariaDB [hospital_management]> SHOW TABLES;
+-------------------------------+
| Tables_in_hospital_management |
+-------------------------------+
| appointments                  |
| billing                       |
| doctor_fees                   |
| doctors                       |
| lab_requests                  |
| lab_tests                     |
| laboratory_tests              |
| medical_records               |
| notifications                 |
| patients                      |
| reports                       |
| services                      |
| users                         |
+-------------------------------+
13 rows in set (0.001 sec)

MariaDB [hospital_management]> DESC medical_records;
+-------------+--------------+------+-----+---------+----------------+
| Field       | Type         | Null | Key | Default | Extra          |
+-------------+--------------+------+-----+---------+----------------+
| record_id   | int(11)      | NO   | PRI | NULL    | auto_increment |
| patients_id | int(11)      | YES  | MUL | NULL    |                |
| doctor_id   | int(11)      | YES  | MUL | NULL    |                |
| diagnosis   | text         | YES  |     | NULL    |                |
| treatment   | text         | YES  |     | NULL    |                |
| visit_date  | date         | YES  |     | NULL    |                |
| notes       | text         | YES  |     | NULL    |                |
| status      | varchar(100) | YES  |     | Pending |                |
+-------------+--------------+------+-----+---------+----------------+
8 rows in set (0.025 sec)

MariaDB [hospital_management]> DESC patients;
+-------------+-----------------------+------+-----+---------------------+----------------+
| Field       | Type                  | Null | Key | Default             | Extra          |
+-------------+-----------------------+------+-----+---------------------+----------------+
| patients_id | int(11)               | NO   | PRI | NULL                | auto_increment |
| full_name   | varchar(100)          | NO   |     | NULL                |                |
| gender      | enum('male','female') | NO   |     | NULL                |                |
| dob         | date                  | YES  |     | NULL                |                |
| phone       | varchar(20)           | YES  |     | NULL                |                |
| email       | varchar(100)          | YES  |     | NULL                |                |
| address     | text                  | NO   |     | NULL                |                |
| created_at  | timestamp             | NO   |     | current_timestamp() |                |
| insures     | varchar(50)           | NO   |     | NULL                |                |
| assigned_to | text                  | NO   |     | NULL                |                |
+-------------+-----------------------+------+-----+---------------------+----------------+
10 rows in set (0.020 sec)

MariaDB [hospital_management]> SELECT * FROM medical_records
    -> SELECT * FROM medical_records;
ERROR 1064 (42000): You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'SELECT * FROM medical_records' at line 2
MariaDB [hospital_management]> SELECT * FROM medical_records;
+-----------+-------------+-----------+-----------+-----------+------------+-------+---------+
| record_id | patients_id | doctor_id | diagnosis | treatment | visit_date | notes | status  |
+-----------+-------------+-----------+-----------+-----------+------------+-------+---------+
|         1 |          29 |        13 |           |           | 2025-04-21 |       | Pending |
|         2 |          31 |        12 |           |           | 2025-04-22 |       | Pending |
|         3 |          32 |        15 |           |           | 2025-04-22 |       | Pending |
|         4 |          33 |        12 |           |           | 2025-04-22 |       | Pending |
+-----------+-------------+-----------+-----------+-----------+------------+-------+---------+
4 rows in set (0.001 sec)

MariaDB [hospital_management]> SELECT * FROM doctors;
+-----------+---------+------------------+------------+----------------------------+
| doctor_id | user_id | specialization   | phone      | full_name                  |
+-----------+---------+------------------+------------+----------------------------+
|        12 |       2 | Dentistry        | 0787778889 | Dr. Alice Umutoni          |
|        13 |       5 | Cardiology       | 0788811123 | Prof. Aime Uwimana         |
|        14 |       2 | Cardiology       | 0786111112 | Prof. Jacques Uwineza      |
|        15 |       3 | Dentistry        | 0788413151 | Prof. Eugene Hakizimana    |
|        16 |       4 | General Medicine | 0788811123 | Prof. Diane Mukamana       |
|        17 |       5 | General Medicine | 0788870051 | Dr. Jean Claude Niyonsenga |
|        18 |       6 | Gynecology       | 0783888901 | Prof. Jean Clement         |
|        19 |       7 | Gynecology       | 0783888900 | Dr. Clarisse Ingabire      |
|        20 |       8 | Orthopedics      | 0785093332 | Dr. Nadia Uwase            |
+-----------+---------+------------------+------------+----------------------------+
9 rows in set (0.000 sec)

MariaDB [hospital_management]> UPDATE medical_records SET visit_date = CURDATE() WHERE record_id = 2;
Query OK, 0 rows affected (0.001 sec)
Rows matched: 1  Changed: 0  Warnings: 0

MariaDB [hospital_management]> SELECT * FROM medical_records
    -> SELECT * FROM medical_records;
ERROR 1064 (42000): You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'SELECT * FROM medical_records' at line 2
MariaDB [hospital_management]> SELECT * FROM medical_records;
+-----------+-------------+-----------+-----------+-----------+------------+-------+---------+
| record_id | patients_id | doctor_id | diagnosis | treatment | visit_date | notes | status  |
+-----------+-------------+-----------+-----------+-----------+------------+-------+---------+
|         1 |          29 |        13 |           |           | 2025-04-21 |       | Pending |
|         2 |          31 |        12 |           |           | 2025-04-22 |       | Pending |
|         3 |          32 |        15 |           |           | 2025-04-22 |       | Pending |
|         4 |          33 |        12 |           |           | 2025-04-22 |       | Pending |
+-----------+-------------+-----------+-----------+-----------+------------+-------+---------+
4 rows in set (0.000 sec)

MariaDB [hospital_management]> SELECT p.patients_id, p.full_name, mr.visit_date
    ->             FROM patients p
    ->             JOIN medical_records mr ON p.patients_id = mr.patients_id
    ->             WHERE mr.doctor_id = ?";
    ">
    ">
    ">
    ">             JOIN medical_records mr ON p.patients_id = mr.patients_id
    ">             FROM patients p
    ">
    "> SELECT p.patients_id, p.full_name, mr.visit_date
    "> SELECT p.patients_id, p.full_name, mr.visit_date
    "> SELECT p.patients_id, p.full_name, mr.visit_date
    ">             JOIN medical_records mr ON p.patients_id = mr.patients_id
    "> SELECT * FROM medical_records;
    "> SELECT * FROM medical_records;
    "> ?>
    "> "
    ->
    -> SELECT * FROM medical_records;
ERROR 1064 (42000): You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near '?";



            JOIN medical_records mr ON p.patients_id = mr.patients_id
...' at line 4
MariaDB [hospital_management]> SELECT p.patients_id, p.full_name, mr.visit_date
    ->             FROM patients p
    ->             JOIN medical_records mr ON p.patients_id = mr.patients_id
    ->             WHERE mr.doctor_id = ?;
ERROR 1064 (42000): You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near '?' at line 4
MariaDB [hospital_management]> SELECT p.patients_id, p.full_name, p.gender, p.phone, p.email, mr.status
    -> FROM patients p
    -> JOIN medical_records mr ON p.patients_id = mr.patients_id
    -> WHERE mr.doctor_id = 12 AND mr.visit_date = '2025-04-22';
+-------------+---------------------+--------+------------+---------------------------+---------+
| patients_id | full_name           | gender | phone      | email                     | status  |
+-------------+---------------------+--------+------------+---------------------------+---------+
|          31 | igitego aime begton | male   | 0791851066 | igitegoaime@gmail.com     | Pending |
|          33 | niyonkuru abdul     | male   | 0791858895 | niyonkuru.abdul@gmail.com | Pending |
+-------------+---------------------+--------+------------+---------------------------+---------+
2 rows in set (0.002 sec)

MariaDB [hospital_management]> SELECT * FROM medical_records;
ERROR 2006 (HY000): MySQL server has gone away
No connection. Trying to reconnect...
Connection id:    429
Current database: hospital_management

+-----------+-------------+-----------+-----------+-----------+------------+-------+---------+
| record_id | patients_id | doctor_id | diagnosis | treatment | visit_date | notes | status  |
+-----------+-------------+-----------+-----------+-----------+------------+-------+---------+
|         1 |          29 |        13 |           |           | 2025-04-21 |       | Pending |
|         2 |          31 |        12 |           |           | 2025-04-22 |       | Pending |
|         3 |          32 |        15 |           |           | 2025-04-22 |       | Pending |
|         4 |          33 |        12 |           |           | 2025-04-22 |       | Pending |
|         5 |          34 |        18 |           |           | 2025-04-23 |       | Pending |
+-----------+-------------+-----------+-----------+-----------+------------+-------+---------+
5 rows in set (0.004 sec)

MariaDB [hospital_management]> DESC doctors;
+----------------+--------------+------+-----+---------+----------------+
| Field          | Type         | Null | Key | Default | Extra          |
+----------------+--------------+------+-----+---------+----------------+
| doctor_id      | int(11)      | NO   | PRI | NULL    | auto_increment |
| user_id        | int(11)      | YES  | MUL | NULL    |                |
| specialization | varchar(100) | NO   |     | NULL    |                |
| phone          | varchar(20)  | YES  |     | NULL    |                |
| full_name      | varchar(100) | NO   |     | NULL    |                |
+----------------+--------------+------+-----+---------+----------------+
5 rows in set (0.016 sec)

MariaDB [hospital_management]> DESC users;
+------------+-------------------------------------------------+------+-----+---------------------+----------------+
| Field      | Type                                            | Null | Key | Default             | Extra          |
+------------+-------------------------------------------------+------+-----+---------------------+----------------+
| user_id    | int(11)                                         | NO   | PRI | NULL                | auto_increment |
| username   | varchar(100)                                    | NO   |     | NULL                |                |
| email      | varchar(100)                                    | NO   | UNI | NULL                |                |
| password   | varchar(255)                                    | NO   |     | NULL                |                |
| role       | enum('admin','doctor','receptionist','labTech') | NO   |     | NULL                |                |
| full_name  | varchar(100)                                    | NO   |     | NULL                |                |
| created_at | timestamp                                       | NO   |     | current_timestamp() |                |
+------------+-------------------------------------------------+------+-----+---------------------+----------------+
7 rows in set (0.074 sec)

MariaDB [hospital_management]> DESC patients;
+-------------+-----------------------+------+-----+---------------------+----------------+
| Field       | Type                  | Null | Key | Default             | Extra          |
+-------------+-----------------------+------+-----+---------------------+----------------+
| patients_id | int(11)               | NO   | PRI | NULL                | auto_increment |
| full_name   | varchar(100)          | NO   |     | NULL                |                |
| gender      | enum('male','female') | NO   |     | NULL                |                |
| dob         | date                  | YES  |     | NULL                |                |
| phone       | varchar(20)           | YES  |     | NULL                |                |
| email       | varchar(100)          | YES  |     | NULL                |                |
| address     | text                  | NO   |     | NULL                |                |
| created_at  | timestamp             | NO   |     | current_timestamp() |                |
| insures     | varchar(50)           | NO   |     | NULL                |                |
| assigned_to | text                  | NO   |     | NULL                |                |
+-------------+-----------------------+------+-----+---------------------+----------------+
10 rows in set (0.035 sec)

MariaDB [hospital_management]> DESC medical_records;
+-------------+--------------+------+-----+---------+----------------+
| Field       | Type         | Null | Key | Default | Extra          |
+-------------+--------------+------+-----+---------+----------------+
| record_id   | int(11)      | NO   | PRI | NULL    | auto_increment |
| patients_id | int(11)      | YES  | MUL | NULL    |                |
| doctor_id   | int(11)      | YES  | MUL | NULL    |                |
| diagnosis   | text         | YES  |     | NULL    |                |
| treatment   | text         | YES  |     | NULL    |                |
| visit_date  | date         | YES  |     | NULL    |                |
| notes       | text         | YES  |     | NULL    |                |
| status      | varchar(100) | YES  |     | Pending |                |
+-------------+--------------+------+-----+---------+----------------+
8 rows in set (0.015 sec)


Setting environment for using XAMPP for Windows.
Am__Begton@DESKTOP-645OJ1G c:\xampp
# mysql -u root
Welcome to the MariaDB monitor.  Commands end with ; or \g.
Your MariaDB connection id is 88
Server version: 10.4.32-MariaDB mariadb.org binary distribution

Copyright (c) 2000, 2018, Oracle, MariaDB Corporation Ab and others.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

MariaDB [(none)]> use hospital_management;
Database changed
MariaDB [hospital_management]> CREATE TABLE notifications (    id INT AUTO_INCREMENT PRIMARY KEY,    role VARCHAR(50),            -- Role-based targeting (e.g., 'doctor', 'receptionist', etc.)    user_id INT DEFAULT NULL,    -- Optional: per-user targeting (can be NULL for role-based)    title VARCHAR(255) NOT NULL,    message TEXT NOT NULL,    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,    is_read TINYINT(1) DEFAULT 0,    CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE);
    ->
    -> CREATE TABLE notifications (    id INT AUTO_INCREMENT PRIMARY KEY,    role VARCHAR(50),            -- Role-based targeting (e.g., 'doctor', 'receptionist', etc.)    user_id INT DEFAULT NULL,    -- Optional: per-user targeting (can be NULL for role-based)    title VARCHAR(255) NOT NULL,    message TEXT NOT NULL,    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,    is_read TINYINT(1) DEFAULT 0,    CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE)
    -> CREATE TABLE notifications (    id INT AUTO_INCREMENT PRIMARY KEY,    role VARCHAR(50),            -- Role-based targeting (e.g., 'doctor', 'receptionist', etc.)    user_id INT DEFAULT NULL,    -- Optional: per-user targeting (can be NULL for role-based)    title VARCHAR(255) NOT NULL,    message TEXT NOT NULL,    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,    is_read TINYINT(1) DEFAULT 0,    CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE);
    -> show tables;
ERROR 1064 (42000): You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'CREATE TABLE notifications (    id INT AUTO_INCREMENT PRIMARY KEY,    role VA...' at line 3
MariaDB [hospital_management]> CREATE TABLE notifications (    id INT AUTO_INCREMENT PRIMARY KEY,    role VARCHAR(50),                user_id INT DEFAULT NULL,        title VARCHAR(255) NOT NULL,    message TEXT NOT NULL,    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,    is_read TINYINT(1) DEFAULT 0,    CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE);
ERROR 1050 (42S01): Table 'notifications' already exists
MariaDB [hospital_management]> desc notification;
ERROR 1146 (42S02): Table 'hospital_management.notification' doesn't exist
MariaDB [hospital_management]> show tables;
+-------------------------------+
| Tables_in_hospital_management |
+-------------------------------+
| appointments                  |
| billing                       |
| doctor_fees                   |
| doctors                       |
| doctors_backup                |
| lab_requests                  |
| lab_tests                     |
| laboratory_tests              |
| medical_records               |
| notifications                 |
| patients                      |
| reports                       |
| services                      |
| tests                         |
| users                         |
+-------------------------------+
15 rows in set (0.001 sec)

MariaDB [hospital_management]> desc notifications;
+-----------------+-----------------------+------+-----+---------------------+----------------+
| Field           | Type                  | Null | Key | Default             | Extra          |
+-----------------+-----------------------+------+-----+---------------------+----------------+
| notification_id | int(11)               | NO   | PRI | NULL                | auto_increment |
| message         | text                  | NO   |     | NULL                |                |
| created_at      | timestamp             | NO   |     | current_timestamp() |                |
| user_id         | int(11)               | NO   | MUL | NULL                |                |
| status          | enum('unread','read') | YES  |     | unread              |                |
| user_type       | varchar(50)           | YES  |     | NULL                |                |
| receiver_type   | varchar(50)           | YES  |     | NULL                |                |
+-----------------+-----------------------+------+-----+---------------------+----------------+
7 rows in set (0.015 sec)

MariaDB [hospital_management]> drop notifications;
ERROR 1064 (42000): You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'notifications' at line 1
MariaDB [hospital_management]> drop table notifications;
Query OK, 0 rows affected (0.219 sec)

MariaDB [hospital_management]> CREATE TABLE notifications (
    ->     notification_id INT(11) NOT NULL AUTO_INCREMENT,
    ->     message TEXT NOT NULL,
    ->     created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ->     user_id INT(11) NOT NULL,
    ->     status ENUM('unread','read') DEFAULT 'unread',
    ->     user_type VARCHAR(50) DEFAULT NULL,
    ->     receiver_type VARCHAR(50) DEFAULT NULL,
    ->     PRIMARY KEY (notification_id),
    ->     KEY (user_id)
    -> );
Query OK, 0 rows affected (0.350 sec)

MariaDB [hospital_management]> drop notifications;
ERROR 1064 (42000): You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'notifications' at line 1
MariaDB [hospital_management]> drop table notifications;
Query OK, 0 rows affected (0.166 sec)

MariaDB [hospital_management]> CREATE TABLE notifications (
    ->     id INT AUTO_INCREMENT PRIMARY KEY,
    ->     user_id INT,
    ->     user_type VARCHAR(50),
    ->     title VARCHAR(255),
    ->     message TEXT,
    ->     status ENUM('unread', 'read') DEFAULT 'unread',
    ->     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    -> );
Query OK, 0 rows affected (0.326 sec)

MariaDB [hospital_management]> select * from notifications;
Empty set (0.001 sec)

MariaDB [hospital_management]> select * from patients where assigned_to = 17;
+-------------+----------------------------+--------+------------+------------+------------------------------+--------------------------------------------+---------------------+---------+-------------+--------+
| patients_id | full_name                  | gender | dob        | phone      | email                        | address                                    | created_at          | insures | assigned_to | status |
+-------------+----------------------------+--------+------------+------------+------------------------------+--------------------------------------------+---------------------+---------+-------------+--------+
|          21 | Kwizera Jean Luck          | male   | 2003-02-18 | 0782351066 | Kwizeraluck@gmail.com        | Rwanda -Kigali- gasabo- kabuga             | 2025-04-17 12:13:19 | MUTUEL  | 17          | NULL   |
|          50 | gira imambe allen          | male   | 2003-06-20 | 0781858561 | allen@gmail.com              | Kigali, Rwanda                             | 2025-04-24 12:26:40 | MUTUEL  | 17          | NULL   |
|          51 | igitego aime begton Begton | male   | 1999-10-21 | 0791851066 | igitegoaimebegton@gmail.com  | KN121ST                                    | 2025-04-24 15:28:14 | RSSB    | 17          | NULL   |
|          53 | akana kiwabo               | female | 1996-12-31 | 0791850002 | kiwabo@gmail.com             | Kigali, Rwanda                             | 2025-04-28 13:30:59 | MUTUEL  | 17          | NULL   |
|          54 | muhorakeye merry           | female | 2000-03-29 | 0782857896 | muhorakeye-merry@gmail.com   | Rwanda-Kigali-nyarugenge-nyamirambo-gitega | 2025-04-29 10:32:05 | RSSB    | 17          | NULL   |
|          56 | nzibarazose pierry         | male   | 1990-05-15 | 0781850984 | nzibarazose-pierry@gmail.com | Rwanda-Kigali-Gasabo-Remera                | 2025-05-04 22:32:48 | MUTUEL  | 17          | NULL   |
+-------------+----------------------------+--------+------------+------------+------------------------------+--------------------------------------------+---------------------+---------+-------------+--------+
6 rows in set (0.000 sec)

MariaDB [hospital_management]> INSERT INTO notifications (title, message, status, user_id, created_at)
    -> VALUES
    -> ('Test Notification 1', 'This is the first test notification.', 'unread', 17, NOW()), ('Test Notification 2', 'This is the second test notification.', 'unread', 16, NOW()), ('Test Notification 3', 'This is the third test notification.', 'unread', 15, NOW());
Query OK, 3 rows affected (0.058 sec)
Records: 3  Duplicates: 0  Warnings: 0

MariaDB [hospital_management]> select * from notifications;
+----+---------+-----------+---------------------+---------------------------------------+--------+---------------------+
| id | user_id | user_type | title               | message                               | status | created_at          |
+----+---------+-----------+---------------------+---------------------------------------+--------+---------------------+
|  1 |      17 | NULL      | Test Notification 1 | This is the first test notification.  | unread | 2025-05-04 22:41:25 |
|  2 |      16 | NULL      | Test Notification 2 | This is the second test notification. | unread | 2025-05-04 22:41:25 |
|  3 |      15 | NULL      | Test Notification 3 | This is the third test notification.  | unread | 2025-05-04 22:41:25 |
+----+---------+-----------+---------------------+---------------------------------------+--------+---------------------+
3 rows in set (0.002 sec)

MariaDB [hospital_management]> show tables;
ERROR 2006 (HY000): MySQL server has gone away
No connection. Trying to reconnect...
Connection id:    217
Current database: hospital_management

+-------------------------------+
| Tables_in_hospital_management |
+-------------------------------+
| appointments                  |
| billing                       |
| doctor_fees                   |
| doctors                       |
| doctors_backup                |
| lab_requests                  |
| lab_tests                     |
| laboratory_tests              |
| medical_records               |
| notifications                 |
| patients                      |
| reports                       |
| services                      |
| tests                         |
| users                         |
+-------------------------------+
15 rows in set (0.085 sec)

MariaDB [hospital_management]> SHOW COLUMNS FROM appointments;
+------------------+-----------------------------------------+------+-----+---------------------+----------------+
| Field            | Type                                    | Null | Key | Default             | Extra          |
+------------------+-----------------------------------------+------+-----+---------------------+----------------+
| appointment_id   | int(11)                                 | NO   | PRI | NULL                | auto_increment |
| patients_id      | int(11)                                 | YES  | MUL | NULL                |                |
| doctor_id        | int(11)                                 | YES  | MUL | NULL                |                |
| appointment_date | datetime                                | YES  |     | NULL                |                |
| appointment_time | time                                    | YES  |     | NULL                |                |
| status           | enum('pending','completed','cancelled') | YES  |     | pending             |                |
| reason           | text                                    | YES  |     | NULL                |                |
| created_at       | timestamp                               | NO   |     | current_timestamp() |                |
| consultation_fee | decimal(10,2)                           | YES  |     | 0.00                |                |
+------------------+-----------------------------------------+------+-----+---------------------+----------------+
9 rows in set (0.039 sec)

MariaDB [hospital_management]> SHOW COLUMNS FROM billing;
+----------------+---------------------------------+------+-----+---------------------+----------------+
| Field          | Type                            | Null | Key | Default             | Extra          |
+----------------+---------------------------------+------+-----+---------------------+----------------+
| bill_id        | int(11)                         | NO   | PRI | NULL                | auto_increment |
| patient_id     | int(11)                         | NO   | MUL | NULL                |                |
| total_amount   | decimal(10,2)                   | YES  |     | NULL                |                |
| paid_amount    | decimal(10,2)                   | YES  |     | 0.00                |                |
| payment_method | varchar(50)                     | YES  |     | NULL                |                |
| status         | enum('unpaid','partial','paid') | YES  |     | unpaid              |                |
| created_at     | timestamp                       | NO   |     | current_timestamp() |                |
+----------------+---------------------------------+------+-----+---------------------+----------------+
7 rows in set (0.091 sec)

MariaDB [hospital_management]> SHOW COLUMNS FROM patients;
+-------------+-----------------------+------+-----+---------------------+----------------+
| Field       | Type                  | Null | Key | Default             | Extra          |
+-------------+-----------------------+------+-----+---------------------+----------------+
| patients_id | int(11)               | NO   | PRI | NULL                | auto_increment |
| full_name   | varchar(100)          | NO   |     | NULL                |                |
| gender      | enum('male','female') | NO   |     | NULL                |                |
| dob         | date                  | YES  |     | NULL                |                |
| phone       | varchar(20)           | YES  |     | NULL                |                |
| email       | varchar(100)          | YES  |     | NULL                |                |
| address     | text                  | NO   |     | NULL                |                |
| created_at  | timestamp             | NO   |     | current_timestamp() |                |
| insures     | varchar(50)           | NO   |     | NULL                |                |
| assigned_to | text                  | NO   |     | NULL                |                |
| status      | varchar(50)           | YES  |     | NULL                |                |
+-------------+-----------------------+------+-----+---------------------+----------------+
11 rows in set (0.023 sec)

MariaDB [hospital_management]> SHOW COLUMNS FROM medical_records;
+-------------+--------------+------+-----+---------+----------------+
| Field       | Type         | Null | Key | Default | Extra          |
+-------------+--------------+------+-----+---------+----------------+
| record_id   | int(11)      | NO   | PRI | NULL    | auto_increment |
| patients_id | int(11)      | YES  | MUL | NULL    |                |
| doctor_id   | int(11)      | YES  | MUL | NULL    |                |
| diagnosis   | text         | YES  |     | NULL    |                |
| treatment   | text         | YES  |     | NULL    |                |
| visit_date  | date         | YES  |     | NULL    |                |
| notes       | text         | YES  |     | NULL    |                |
| status      | varchar(100) | YES  |     | Pending |                |
+-------------+--------------+------+-----+---------+----------------+
8 rows in set (0.020 sec)

MariaDB [hospital_management]> SHOW COLUMNS FROM lab_requests;
+--------------+-----------------------------+------+-----+---------------------+----------------+
| Field        | Type                        | Null | Key | Default             | Extra          |
+--------------+-----------------------------+------+-----+---------------------+----------------+
| request_id   | int(11)                     | NO   | PRI | NULL                | auto_increment |
| patient_id   | int(11)                     | YES  |     | NULL                |                |
| doctor_id    | int(11)                     | YES  |     | NULL                |                |
| test_name    | varchar(255)                | YES  |     | NULL                |                |
| status       | enum('pending','completed') | YES  |     | pending             |                |
| result       | text                        | YES  |     | NULL                |                |
| requested_at | datetime                    | YES  |     | current_timestamp() |                |
| cost         | decimal(10,2)               | YES  |     | 0.00                |                |
| test_id      | int(11)                     | YES  | MUL | NULL                |                |
+--------------+-----------------------------+------+-----+---------------------+----------------+
9 rows in set (0.010 sec)

MariaDB [hospital_management]> select * from users;
+---------+--------------+------------------------------+--------------------------------------------------------------+--------------+----------------------------+---------------------+
| user_id | username     | email                        | password                                                     | role         | full_name                  | created_at          |
+---------+--------------+------------------------------+--------------------------------------------------------------+--------------+----------------------------+---------------------+
|       2 | prof.alice   | alice@gmail.com              | $2y$10$iJO6VUKNjbLSHCdGWVGsteKoWnMgqQbkYM6AJhp36QPftkd34Hdqq | doctor       | Alice mugorakeye           | 2025-04-11 10:33:29 |
|       3 | bob          | bob@gmail.com                | $2y$10$FkvJ4gN13Da2fcxZJY0xQOHz6yjPEqodEvAYYq9TKDZEzhpI9D1SC | receptionist | Bob Smith                  | 2025-04-11 10:33:29 |
|       4 | charlie      | charlie@gmail.com            | $2y$10$nyA4jMNfOHu6AXCijgNTcucSnRFuFt1DrWCV8AR6fkpk7bYW1Aus6 | labTech      | Charlie Brown              | 2025-04-11 10:33:29 |
|       5 | dr.aime      | aime@gmail.com               | $2y$10$NxcjNzw0WKTHu9t3mBBvBu7AtRWQzUq7PYUaNL8zvuvEJ1BfPldQK | doctor       | aime jean                  | 2025-04-11 15:45:13 |
|       6 | begton       | begton@gmail.com             | $2y$10$uuUBxW.JC0emMORINKvhyeske2hhTmK7cNDJYRm3VKwWDXN3tyvn6 | admin        | begton own                 | 2025-04-11 15:53:47 |
|       7 | sum          | sum@gmail.com                | $2y$10$s7tdCwcg3yTzJPweLAjd5.1czq20WD.NPKQ8pL7WJSAh.QIzhN.zi | receptionist | sum bill                   | 2025-04-11 16:11:56 |
|       8 | bill         | bill@gmail.com               | $2y$10$q0Q4.9xMnj8dKKyIuF8breqgHCl4qNIuX0c7b.aofHvfAEV4WWMUy | labTech      | bill gates                 | 2025-04-11 16:16:06 |
|       9 | Dr. Nadia    | Dr.NadiaUwase@gmail.com      | $2y$10$oO.e5XSyFpNr.l5zazKL3.vjxPbcCN0XQCQHC1qrNg7AsMVtvCPVe | doctor       | Dr. Nadia Uwase            | 0000-00-00 00:00:00 |
|      10 | prof.eugene  | eugene.hakizimana@gmail.com  | $2y$10$OZCY4iD6cezVcWoGHkPhq..4c5Ric2zBWD/QVD2pAz0bt3iKpUD3y | doctor       | Prof. Eugene Hakizimana    | 2025-04-16 17:04:08 |
|      11 | prof.diane   | diane.mukamana@gmail.com     | $2y$10$kgMSBgNVZJFyZYSFFXY4geQVC9HwN/Y3neogH27ov/Nlh7hoDqqtS | doctor       | Prof. Diane Mukamana       | 2025-04-16 17:04:08 |
|      12 | prof.clement | jean.clement@gmail.com       | $2y$10$W.etKC5HtnlH4lvYdIM89Or2aZ4bQoTQFUp3yMldCQMVKuHjlExny | doctor       | Prof. Jean Clement         | 2025-04-16 17:04:08 |
|      13 | dr.ingabire  | clarisse.ingabire@gmail.com  | $2y$10$wpJJPZrS57P8ErCb9zzQ7.hU74GywDsmXlK6Vjurmte7iPOIlIPAC | doctor       | Dr. Clarisse Ingabire      | 2025-04-16 17:04:08 |
|      14 | dr.uwase     | nadia.uwase@gmail.com        | $2y$10$g8hMEhl9eVRm2IiKWjQTyeV6ngTB5.cDU.gbuzeQeeSX2c4aRGogS | doctor       | Dr. Nadia Uwase            | 2025-04-16 17:04:08 |
|      15 | dr.claude    | jean.claude@gmail.com        | $2y$10$gU40psZY/15H.8RBWlVDlOqXFmMwa.7q5i8wOKW8N9UMIQDHSFOca | doctor       | Dr. Jean Claude Niyonsenga | 2025-04-16 17:04:08 |
|      16 | prof.uwineza | jacques.uwineza@gmail.com    | $2y$10$7iG.cY.FQmWNA.G0b2myvehuvc0GzIeodV1cXTg5V1uGONpca8Zyi | doctor       | Prof. Jacques Uwineza      | 2025-04-16 17:04:08 |
|      17 | Dr.Alice     |  Dr. Alice_Umutoni@gmail.com | $2y$10$EYUqRYdJRoC.lPXXDwVC/ufXALu56C5HQ64T01bJKB3bSXjgMfbyq | doctor       | Dr.Alice Umutoni           | 2025-04-20 14:39:40 |
+---------+--------------+------------------------------+--------------------------------------------------------------+--------------+----------------------------+---------------------+
16 rows in set (0.001 sec)

MariaDB [hospital_management]> SHOW COLUMNS FROM doctors;
+----------------+--------------+------+-----+---------+-------+
| Field          | Type         | Null | Key | Default | Extra |
+----------------+--------------+------+-----+---------+-------+
| doctor_id      | int(11)      | NO   | PRI | NULL    |       |
| specialization | varchar(100) | NO   |     | NULL    |       |
| phone          | varchar(20)  | YES  |     | NULL    |       |
| full_name      | varchar(100) | NO   |     | NULL    |       |
+----------------+--------------+------+-----+---------+-------+
4 rows in set (0.030 sec)

MariaDB [hospital_management]> SELECT mr.record_id, p.full_name AS patient_name, d.full_name AS doctor_name, mr.diagnosis
    -> FROM medical_records mr
    -> JOIN patients p ON mr.patients_id = p.patients_id
    -> JOIN doctors d ON mr.doctor_id = d.doctor_id;
+-----------+----------------------------+----------------------------+-----------+
| record_id | patient_name               | doctor_name                | diagnosis |
+-----------+----------------------------+----------------------------+-----------+
|         2 | igitego aime begton        | Prof. Jean Clement         |           |
|         4 | niyonkuru abdul            | Prof. Jean Clement         |           |
|         6 | uwase anne                 | Prof. Jean Clement         |           |
|         7 | UTAZIMA Patrick            | Prof. Jean Clement         |           |
|         8 | mugisha paccy              | Prof. Jean Clement         |           |
|        10 | umwangavu justine          | Prof. Jean Clement         |           |
|        11 | UGIRAMANA  Paul            | Prof. Jean Clement         |           |
|        12 | uwamariya ange             | Prof. Jean Clement         | NULL      |
|        13 | merry bella                | Prof. Jean Clement         | NULL      |
|        15 | mugiraneza jean            | Prof. Jean Clement         | NULL      |
|        18 | gitego B theirry           | Prof. Jean Clement         | NULL      |
|        24 | kirabo biancha             | Prof. Jean Clement         | NULL      |
|        29 | uwimana hadjara            | Prof. Jean Clement         | NULL      |
|         1 | kwizera yve                | Dr. Clarisse Ingabire      |           |
|        27 | Habimana kalim abdul       | Dr. Clarisse Ingabire      | NULL      |
|        30 | akayezu uwase christine    | Dr. Clarisse Ingabire      | NULL      |
|         3 | niyomugabo jean god        | Dr. Jean Claude Niyonsenga |           |
|         9 | Kwizera Jean Luck          | Dr. Alice Umutoni          | NULL      |
|        16 | mugiraneza jean            | Dr. Alice Umutoni          | NULL      |
|        17 | dudu mutoni                | Dr. Alice Umutoni          | NULL      |
|        19 | ugira iwabo bein           | Dr. Alice Umutoni          | NULL      |
|        20 | gira imambe allen          | Dr. Alice Umutoni          | NULL      |
|        23 | igitego aime begton Begton | Dr. Alice Umutoni          | NULL      |
|        25 | akana kiwabo               | Dr. Alice Umutoni          | NULL      |
|        26 | muhorakeye merry           | Dr. Alice Umutoni          | NULL      |
|        28 | nzibarazose pierry         | Dr. Alice Umutoni          | NULL      |
|        31 | umwari betty               | Dr. Alice Umutoni          | NULL      |
+-----------+----------------------------+----------------------------+-----------+
27 rows in set (0.001 sec)