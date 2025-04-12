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
-- Rules
-- Days
INSERT INTO days_rules(name, open) VALUES
    ('lundi', false),('mardi',false),('mercredi',true),
    ('jeudi',true),('vendredi',true),('samedi',true),('dimanche',true);
-- Time rules
INSERT INTO time_rules(id_days, time_start, time_end, number_places) VALUES
     (3, '12:00', '14:00', 30),
     (3, '19:00', '22:00', 40),
     (4, '12:00', '14:00', 30),
     (4, '19:00', '22:00', 40),
     (5, '12:00', '14:00', 30),
     (5, '19:00', '22:00', 40),
     (6, '12:00', '14:30', 35),
     (6, '19:00', '23:00', 50),
     (7, '12:00', '15:00', 25);

-- Exceptions rules Test
INSERT INTO exception_rules(date,open,comment) VALUES
    ('2025-04-16',false,'Vacance'),
    ('2025-04-14',true,'Concert');

INSERT INTO exception_time_rules(id_exc,time_start,time_end,number_of_places) VALUES
    (1,'00:00:00','23:59:59',0),
    (2,'12:00:00','15:00:00',100),
    (2,'18:00:00','21:30:00',150)