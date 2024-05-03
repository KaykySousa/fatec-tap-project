DROP DATABASE IF EXISTS projetophp;

CREATE DATABASE projetophp;

USE projetophp;

CREATE TABLE usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    birthdate DATE NOT NULL
);

CREATE TABLE log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    operation VARCHAR(100) NOT NULL,
    usuario_id INT NOT NULL,
    createdAt DATETIME NOT NULL DEFAULT NOW()
);

DELIMITER //
CREATE TRIGGER log_insert_usuario AFTER INSERT ON usuario
FOR EACH ROW
BEGIN
    INSERT INTO log (operation, usuario_id) VALUES (CONCAT('INSERT: ' , CONCAT_WS(' ', NEW.name, NEW.email, NEW.birthdate)), NEW.id);
END//

CREATE TRIGGER log_update_usuario AFTER UPDATE ON usuario
FOR EACH ROW
BEGIN
    INSERT INTO log (operation, usuario_id) VALUES (CONCAT('UPDATE: ' , CONCAT_WS(' ', NEW.name, NEW.email, NEW.birthdate)), NEW.id);
END//

CREATE TRIGGER log_delete_usuario AFTER DELETE ON usuario
FOR EACH ROW
BEGIN
    INSERT INTO log (operation, usuario_id) VALUES (CONCAT('DELETE: ' , CONCAT_WS(' ', OLD.name, OLD.email, OLD.birthdate)), OLD.id);
END//

DELIMITER ;


DELIMITER //

CREATE FUNCTION format_email(email VARCHAR(100)) 
RETURNS VARCHAR(100)
BEGIN
    SET @atIndex = LOCATE('@', email);
    SET @domain = SUBSTRING(email, @atIndex + 1);
    SET @domainLength = LENGTH(@domain);
    SET @username = SUBSTRING(email, 1, @atIndex - 1);

    RETURN CONCAT(@username, '@', REPEAT('*', @domainLength)); 
END //

CREATE PROCEDURE insert_usuario(
    IN name VARCHAR(100),
    IN email VARCHAR(100),
    IN birthdate DATE
) 
BEGIN
    INSERT INTO usuario (name, email, birthdate) VALUES (name, email, birthdate);
END //

CREATE PROCEDURE update_usuario(
    IN u_id INT,
    IN name VARCHAR(100),
    IN email VARCHAR(100),
    IN birthdate DATE
)

BEGIN
    UPDATE usuario SET name = name, email = email, birthdate = birthdate WHERE u_id = id;
END //

CREATE PROCEDURE delete_usuario(
    IN u_id INT
)
BEGIN
    DELETE FROM usuario WHERE u_id = id;
END //




CREATE PROCEDURE select_usuario(
    IN u_id INT
)
BEGIN
    SELECT id, name, format_email(email) as email, DATE_FORMAT(birthdate, "%d/%m/%Y") as birthdate FROM usuario WHERE u_id = id;
END //

CREATE PROCEDURE select_all_usuarios()
BEGIN
    SELECT id, name, format_email(email) as email, DATE_FORMAT(birthdate, "%d/%m/%Y") as birthdate FROM usuario;
END //

DELIMITER ;