CREATE TABLE IF NOT EXISTS Users
(
    Username VARCHAR(30) PRIMARY KEY,
    Passkey VARCHAR(6),
    FirstName VARCHAR(30),
    Surname VARCHAR(30),
    AddressLine1 VARCHAR(30),
    AddressLine2 VARCHAR(30),
    City VARCHAR(30),
    Telephone VARCHAR(15),
    Mobile VARCHAR(15)
);

CREATE TABLE IF NOT EXISTS Categories
(
    CategoryID VARCHAR(3) PRIMARY KEY,
    CategoryDescription VARCHAR(30)
);

CREATE TABLE IF NOT EXISTS Books
(
    ISBN VARCHAR(30) PRIMARY KEY,
    BookTitle VARCHAR(30),
    Author VARCHAR(30),
    Edition INT(2),
    YearPublished YEAR,
    Category VARCHAR(3),
    Reserved CHAR(1),
   
    CONSTRAINT fk_category
    FOREIGN KEY (Category)
    REFERENCES Categories(CategoryID)
);

CREATE TABLE IF NOT EXISTS Reservations
(
    ISBN VARCHAR(30),
    Username VARCHAR(30),
    ReservedDate DATE,
   
    CONSTRAINT fk_username
    FOREIGN KEY (Username)
    REFERENCES Users(Username),
   
    CONSTRAINT fk_isbn
    FOREIGN KEY (ISBN)
    REFERENCES Books(ISBN)
);

INSERT INTO Users (Username, Passkey, FirstName, Surname, AddressLine1, AddressLine2, City, Telephone, Mobile) VALUES
('alanjmckenna', 't1234s', 'Alan', 'McKenna', '38 Cranley Road', 'Fairview', 'Dublin', '9998377', '856625567'),
('joecrotty', 'kj7899', 'Joseph', 'Crotty', 'Apt 5 Clyde Road', 'Donnybrook', 'Dublin', '8887889', '876654456'),
('tommy100', '123456', 'Tom', 'Behan', '14 Hyde Road', 'Dalkey', 'Dublin', '9983747', '876738782');

INSERT INTO Categories (CategoryID, CategoryDescription) VALUES
('001', 'Health'),
('002', 'Business'),
('003', 'Biography'),
('004', 'Technology'),
('005', 'Travel'),
('006', 'Self-Help'),
('007', 'Cookery'),
('008', 'Fiction');

INSERT INTO Books (ISBN, BookTitle, Author, Edition, YearPublished, Category, Reserved) VALUES
('093-403992', 'Computers in Business', 'Alicia Oneill', 3, 1997, '003', 'N'),
('23472-8729', 'Exploring Peru', 'Stephanie Birch', 4, 2005, '005', 'N'),
('237-34823', 'Business Strategy', 'Joe Peppard', 2, 2002, '002', 'N'),
('23u8-923849', 'A guide to nutrition', 'John Thorpe', 2, 1997, '001', 'N'),
('2983-3494', 'Cooking for children', 'Anabelle Sharpe', 1, 2003, '007', 'N'),
('82n8-308', 'computers for idiots', 'Susan ONeill', 5, 1998, '004', 'N'),
('9823-23984', 'My life in picture', 'Kevin Graham', 8, 2004, '001', 'N'),
('9823-2403-0', 'DaVinci Code', 'Dan Brown', 1, 2003, '008', 'N'),
('98234-029384', 'My ranch in Texas', 'George Bush', 1, 2005, '001', 'Y'),
('9823-98345', 'How to cook Italian food', 'Jamie Oliver', 2, 2005, '007', 'Y'),
('9823-98487', 'Optimising your business', 'Cleo Blair', 1, 2001, '002', 'N'),
('988745-234', 'Tara Road', 'Maeve Binchy', 4, 2002, '008', 'N'),
('993-004-00', 'My life in bits', 'John Smith', 1, 2001, '001', 'N'),
('9987-0039882', 'Shooting History', 'Jon Snow', 1, 2003, '001', 'N');

INSERT INTO reservations (ISBN, Username, ReservedDate) VALUES
('9823-98345', 'tommy100', '2008-10-11'),
('98234-029384', 'joecrotty',  '2008-10-11');





-- Adding 10 more books
INSERT INTO Books (ISBN, BookTitle, Author, Edition, YearPublished, Category, Reserved) VALUES
('973-21389', 'AI for Business', 'Sarah Lee', 1, 2020, '002', 'N'),
('2381-2384', 'Hiking Adventures', 'Tom Walker', 2, 2019, '005', 'N'),
('3482-2394', 'The Art of Cooking', 'Nigella Lawson', 3, 2016, '007', 'N'),
('2378-2384', 'The Science of Health', 'Jane Doe', 1, 2021, '001', 'N'),
('2384-2378', 'The Code Breaker', 'Walter Isaacson', 1, 2020, '004', 'N'),
('2394-2394', 'Travelling the World', 'Elena Kova', 2, 2018, '005', 'N'),
('2382-9238', 'Leadership in the 21st Century', 'Tom Hanks', 3, 2017, '002', 'N'),
('2398-2984', 'Mastering Self-Help', 'Mark Twain', 4, 2010, '006', 'N'),
('2384-2387', 'The Secrets of Cooking', 'Gordon Ramsay', 1, 2015, '007', 'N'),
('2984-2387', 'A Journey Through Time', 'J.K. Rowling', 2, 2011, '008', 'N');

-- Adding 3 more reservations
INSERT INTO reservations (ISBN, Username, ReservedDate) VALUES
('973-21389', 'alanjmckenna', '2024-12-01'),
('2384-2378', 'joecrotty', '2024-12-02'),
('2394-2394', 'tommy100', '2024-12-03');
