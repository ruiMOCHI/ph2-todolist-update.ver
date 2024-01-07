DROP DATABASE IF EXISTS posse;  /*既にデータベースが存在する場合は削除 mysql -u root -p 入るとき使う passwordはroot*/
CREATE DATABASE posse;  /*Mysqlのデータベースを作成*/
USE posse;  /*作成したデータベースを選択*/ 

DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255),
    password VARCHAR(255)
) CHARSET=utf8;

INSERT INTO users (email, password) VALUES
('admin@example.com', '$2y$10$aM4/AnwPCu4Jdm07v8fChOTqySH.ObzN2IyyVC0w.PeKLO1AGUp6K');


DROP TABLE IF EXISTS todos;
CREATE TABLE todos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    text VARCHAR(255),
    completed BOOLEAN DEFAULT FALSE,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id) -- 外部キー制約を追加
) CHARSET=utf8;

INSERT INTO todos (text, user_id) VALUES
('表見代理', 1),
('民法109条、代理権授与の表示による表見代理', 1),
('民法110条、権限外の代理行為の表見代理', 1);


/*mysql -u root -proot posse < /docker-entrypoint-initdb.d/init.sql*/