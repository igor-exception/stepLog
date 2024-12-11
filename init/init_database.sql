
CREATE TABLE users(
	user_id int primary key auto_increment,
    name varchar(150) not null,
    email varchar(150) unique,
    birth date,
    password varchar(150)
);


INSERT INTO users(name, email, birth, password) VALUES ('Teste John Doe', 'teste_john@gmai.com', '1960-10-02', '$2y$10$6676l424J6CgrhGk8bBEHOU9ULQATB/LJmeUj2mJ3GYe6a3xvt3WW');