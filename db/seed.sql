INSERT INTO roles (name) VALUES ('admin'), ('moderator'), ('user');
INSERT INTO users (name,email,password) VALUES ('admin','admin@leresto.com','$2y$10$fZrsVnaHScn996ZFJ6.n4.MTmXaU/BH3zU/2ai8/Xm2p.bnQgxHHa');
INSERT INTO user_roles (user_id, role_id) VALUES (1,1);

INSERT INTO users(name,email,password) VALUES ('nobody','nobody@leresto.com','$2y$10$zWJfL/qnuRN0EaXe/a7q8u/QTRisjsruY4vj5mLVKCrBHcDM5Mlsy');
INSERT INTO user_roles (user_id, role_id) VALUES (2,3);