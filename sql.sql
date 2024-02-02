CREATE TABLE book_ownership (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT,
  book_id INT,
  ownership_status VARCHAR(20) NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (book_id) REFERENCES books(id)
);
