INSERT INTO roles (name) VALUES ('admin'), ('moderator'), ('user');
INSERT INTO users (name,email,password) VALUES ('admin','admin@leresto.com','admin');
INSERT INTO user_roles (user_id, role_id) VALUES (1,1);