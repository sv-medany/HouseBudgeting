drop database if exists house_wallet_system;
create database if not exists house_wallet_system;
use house_wallet_system;

create table `user` (
user_id int primary key auto_increment,					
email varchar(255) unique not null,
password varchar(255)  not null,
name varchar(255) not null,
alert_percent DECIMAL(5,2)
);

create table budget (
budget_month int not null,
initial_budget DECIMAL(10,2) not null,
consumed_budget DECIMAL(10,2),
goal_budget DECIMAL(10,2),
user_id int,
yr int not null,
PRIMARY KEY (user_id,budget_month),
constraint user_id_budget_fk foreign key (user_id) references user(user_id) on delete cascade
);


create table purchase (
purchase_id int primary key auto_increment,
user_id int,
purchase_date date not null,
product_name varchar(50),
product_category varchar(50) not null,
price DECIMAL(10,2) not null,
payment ENUM('visa', 'cash') default 'cash',
constraint user_id_purchase_fk foreign key (user_id) references user(user_id) on delete cascade

);


-- Insert values into the user table
INSERT INTO user (email, password, name, alert_percent)
VALUES
    ('john@mail.com', MD5('john1234'), 'John Doe', 70),
    ('jane@mail.com',  MD5('jane1234'), 'Jane Smith', 80),
    ('bob@mail.com',  MD5('bob1234'), 'Bob Johnson', 75);
    
-- Insert values into the budget table
INSERT INTO budget (budget_month, initial_budget, consumed_budget, goal_budget, user_id, yr)
VALUES
    (3, 10000, 4900, 5000, 1,2023),
    (4, 5000, 3890, 1000, 1,2023),
    (5, 9000, 3000, 1500, 1,2023),
    (4, 12000, 10000, 1000, 2,2023),
    (5, 14000, 5000, 1500, 2,2023),
    (5, 45000, 11000, 6000, 3,2023);

-- Insert values into the purchase table
INSERT INTO purchase (user_id, purchase_date, product_name, product_category, price, payment)
VALUES
    (1, '2023-03-01', 'Lanchon Packet', 'Groceries', 100, 'cash'),
    (1, '2023-04-02','3 Pants', 'Clothing', 2000, 'visa'),
    (1, '2023-05-03','5 Transistors', 'Electronics', 3000, 'visa'),
    (2, '2023-04-04','Plastic Chair','Furniture', 600, 'visa'),
    (2, '2022-05-05', 'Let Us Learn Book','Books', 70, 'cash'),
    (3, '2022-05-06', 'Red Car', 'Toys', 100, 'visa'),
    (3, '2022-05-07','Ring','Jewelry',5500, 'visa'),
    (3, '2022-05-08', 'Green Vase','Home Decor', 200, 'visa'),
    (3, '2022-05-09', 'Yoga Mat','Fitness Equipment', 300, 'visa'),
    (3,'2022-15-10', 'Shampoo','Beauty Products', 150, 'cash');


