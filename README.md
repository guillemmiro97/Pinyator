## Setup mysql database

Install in Ubuntu 18.04

    sudo apt install mysql-server
    sudo mysql_secure_installation

Set a root password to remember
Add the password in the Connexio.php file

Set root to a normal password (default is auth_socket)

    sudo mysql
    mysql> SELECT user,authentication_string,plugin,host FROM mysql.user;
    mysql> ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'password';
    mysql> FLUSH PRIVILEGES;
    mysql> exit
    systemctl restart mysql.service

Try to connect to verify the login

    mysql -u root -p

Now create the database and an extra necessary user

    mysql> CREATE DATABASE pinyator;
    mysql> USE pinyator;
    mysql> GRANT ALL PRIVILEGES ON *.* TO 'marrecs'@'localhost' IDENTIFIED BY 'password';
    mysql> FLUSH PRIVILEGES;
    mysql> exit

Copy de DB structure (change Connexio.php DB name)

    mysql -u root -p pinyator < Pinyator_BD.sql
    mysql> exit


## PHP server

Install php server

    sudo apt install php7.2-cli php7.2-mysql

Clone and run project

    git clone https://gitlab.com/elputorei/Pinyator pinyator
    php -S 127.0.0.1:8000

## Open webpage

On:

    http://localhost:8000/pinyator/Index.html



    
