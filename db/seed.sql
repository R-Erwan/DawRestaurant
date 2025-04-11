INSERT INTO roles (name) VALUES ('admin'), ('moderator'), ('user');
INSERT INTO users (name,email,password) VALUES ('admin','admin@leresto.com','$2y$10$fZrsVnaHScn996ZFJ6.n4.MTmXaU/BH3zU/2ai8/Xm2p.bnQgxHHa');
INSERT INTO user_roles (user_id, role_id) VALUES (1,1);

INSERT INTO users(name,email,password) VALUES ('nobody','nobody@leresto.com','$2y$10$zWJfL/qnuRN0EaXe/a7q8u/QTRisjsruY4vj5mLVKCrBHcDM5Mlsy');
INSERT INTO user_roles (user_id, role_id) VALUES (2,3);

INSERT INTO announces(type, position, title, description, image_url)
VALUES (1,
        1,
        'Edward, notre cuisinier distingué',
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec interdum lorem sollicitudin purus mattis semper. Phasellus convallis, velit a placerat tempor, magna velit egestas neque, et placerat metus metus ut justo. Etiam urna metus, euismod rutrum elit eu, feugiat finibus eros. Integer a egestas nulla, vitae varius tellus. Nam feugiat nibh at pellentesque posuere. Nunc ac molestie ex. Nullam sed tempus mi. Sed non ante posuere, placerat ex eget, tempus erat. Maecenas quis nulla in justo aliquam luctus. Etiam tincidunt gravida ligula sit amet accumsan. Aenean in magna sed sem faucibus varius.',
    NULL);
INSERT INTO announces(type, position, title, description, image_url)
VALUES (1,
        3,
        'Le végétarien sublimé',
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec interdum lorem sollicitudin purus mattis semper. Phasellus convallis, velit a placerat tempor, magna velit egestas neque, et placerat metus metus ut justo. Etiam urna metus, euismod rutrum elit eu, feugiat finibus eros. Integer a egestas nulla, vitae varius tellus. Nam feugiat nibh at pellentesque posuere. Nunc ac molestie ex. Nullam sed tempus mi. Sed non ante posuere, placerat ex eget, tempus erat. Maecenas quis nulla in justo aliquam luctus. Etiam tincidunt gravida ligula sit amet accumsan. Aenean in magna sed sem faucibus varius.',
    NULL);
INSERT INTO announces(type, position, title, description, image_url)
VALUES (1,
        5,
        'Des recettes traditionnelles revisitées',
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec interdum lorem sollicitudin purus mattis semper. Phasellus convallis, velit a placerat tempor, magna velit egestas neque, et placerat metus metus ut justo. Etiam urna metus, euismod rutrum elit eu, feugiat finibus eros. Integer a egestas nulla, vitae varius tellus. Nam feugiat nibh at pellentesque posuere. Nunc ac molestie ex. Nullam sed tempus mi. Sed non ante posuere, placerat ex eget, tempus erat. Maecenas quis nulla in justo aliquam luctus. Etiam tincidunt gravida ligula sit amet accumsan. Aenean in magna sed sem faucibus varius.',
    NULL);

INSERT INTO announces(type, position, title, description, image_url)
VALUES (2,
        2,
        NULL,
    NULL,
        '/resources/static/cook.jpg'
        );
INSERT INTO announces(type, position, title, description, image_url)
VALUES (2,
        4,
        NULL,
        NULL,
        '/resources/static/vege.jpg'
       );
INSERT INTO announces(type, position, title, description, image_url)
VALUES (2,
        6,
        NULL,
        NULL,
        '/resources/static/food3.jpg'
       );
