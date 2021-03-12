CREATE TABLE users(
                      username VARCHAR(50) PRIMARY KEY,
                      passwd VARCHAR(100)
);

CREATE TABLE topics(
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       title VARCHAR(100),
                       content TEXT,
                       author VARCHAR(100) NOT NULL,
                       FOREIGN KEY (author) REFERENCES users(username)
);

CREATE TABLE comments(
                         id INT AUTO_INCREMENT PRIMARY KEY,
                         content TEXT,
                         author VARCHAR(100) NOT NULL,
                         topic INT NOT NULL,
                         FOREIGN KEY (author) REFERENCES users(username),
                         FOREIGN KEY (topic) REFERENCES  topics(id)
);
