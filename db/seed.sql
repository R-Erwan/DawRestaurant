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
    (2,'18:00:00','21:30:00',150);


INSERT INTO reservations (user_id, name, email, reservation_date, reservation_time, number_of_people, status)
VALUES
    (1, 'admin', 'admin@leresto.com', '2025-04-15', '19:00', 2, 'confirmed'),
    (1, 'admin', 'admin@leresto.com', '2025-04-17', '20:00', 4, 'waiting'),
    (1, 'admin', 'admin@leresto.com', '2025-04-20', '18:30', 3, 'cancelled'),
    (1, 'admin', 'admin@leresto.com', '2025-04-23', '21:00', 5, 'confirmed'),
    (1, 'admin', 'admin@leresto.com', '2025-04-25', '19:30', 2, 'waiting'),
    (1, 'admin', 'admin@leresto.com', '2025-04-28', '20:15', 6, 'confirmed'),
    (1, 'admin', 'admin@leresto.com', '2025-05-01', '18:00', 2, 'waiting'),
    (1, 'admin', 'admin@leresto.com', '2025-05-03', '19:45', 4, 'confirmed'),
    (1, 'admin', 'admin@leresto.com', '2025-05-06', '20:30', 1, 'waiting'),
    (1, 'admin', 'admin@leresto.com', '2025-05-10', '21:00', 2, 'cancelled');


INSERT INTO reservations (user_id, name, email, reservation_date, reservation_time, number_of_people, status)
VALUES
    (2, 'nobody', 'nobody@leresto.com', '2025-04-16', '19:00', 2, 'waiting'),
    (2, 'nobody', 'nobody@leresto.com', '2025-04-18', '20:15', 3, 'confirmed'),
    (2, 'nobody', 'nobody@leresto.com', '2025-04-21', '18:45', 2, 'cancelled'),
    (2, 'nobody', 'nobody@leresto.com', '2025-04-24', '19:30', 5, 'confirmed'),
    (2, 'nobody', 'nobody@leresto.com', '2025-04-27', '20:00', 4, 'waiting'),
    (2, 'nobody', 'nobody@leresto.com', '2025-04-30', '21:00', 6, 'confirmed'),
    (2, 'nobody', 'nobody@leresto.com', '2025-05-02', '18:30', 1, 'waiting'),
    (2, 'nobody', 'nobody@leresto.com', '2025-05-04', '20:45', 2, 'confirmed'),
    (2, 'nobody', 'nobody@leresto.com', '2025-05-07', '19:15', 3, 'cancelled'),
    (2, 'nobody', 'nobody@leresto.com', '2025-05-09', '20:00', 2, 'confirmed');

--insertions des plats du menu

INSERT INTO categories(name) VALUES ('starters');
INSERT INTO categories(name) VALUES ('mainFood');
INSERT INTO categories(name) VALUES ('desserts');
INSERT INTO categories(name) VALUES ('drinks');

INSERT INTO subcategories(name, category_id) VALUES ('Aperitifs', 1);
INSERT INTO subcategories(name, category_id) VALUES ('Salades', 1);
INSERT INTO subcategories(name, category_id) VALUES ('Poissons', 2);
INSERT INTO subcategories(name, category_id) VALUES ('Viandes', 2);
INSERT INTO subcategories(name, category_id) VALUES ('Pâtes', 2);
INSERT INTO subcategories(name, category_id) VALUES ('Glaces', 3);
INSERT INTO subcategories(name, category_id) VALUES ('Patisseries', 3);
INSERT INTO subcategories(name, category_id) VALUES ('Softs', 4);
INSERT INTO subcategories(name, category_id) VALUES ('Alcools', 4);

INSERT INTO dishes(name, description, price, subcategory_id)VALUES ('Bruschetta', 'Pain grillé avec tomates fraîches, ail, basilic et huile d''olive', 8, 1);
INSERT INTO dishes(name, description, price, subcategory_id) VALUES ('Tapenade', 'Purée d''olives noires servie avec du pain croustillant', 7, 1);
INSERT INTO dishes(name, description, price, subcategory_id) VALUES ('Salade César', 'Laitue romaine, poulet grillé, croûtons, parmesan et sauce César', 12, 2);
INSERT INTO dishes(name, description, price, subcategory_id) VALUES ('Salade Caprese', 'Tomates fraîches, mozzarella di bufala, basilic et huile d''olive', 11, 2);
INSERT INTO dishes(name, description, price, subcategory_id) VALUES ('Saumon grillé', 'Filet de saumon grillé servi avec une sauce citronnée et légumes vapeur', 18, 3);
INSERT INTO dishes(name, description, price, subcategory_id) VALUES ('Cabillaud à la provençale', 'Cabillaud rôti avec une sauce tomate, olives et herbes de Provence', 20, 3);
INSERT INTO dishes(name, description, price, subcategory_id) VALUES ('Entrecôte grillée', 'Viande de bœuf tendre servie avec frites maison et sauce au poivre', 22, 4);
INSERT INTO dishes(name, description, price, subcategory_id) VALUES ('Magret de canard', 'Magret rôti avec sauce au miel et purée de patates douces', 21, 4);
INSERT INTO dishes(name, description, price, subcategory_id) VALUES ('Tagliatelles aux truffes', 'Pâtes fraîches avec crème aux truffes et parmesan', 19, 5);
INSERT INTO dishes(name, description, price, subcategory_id) VALUES ('Lasagnes maison', 'Lasagnes bolognaises gratinées au four', 16, 5);
INSERT INTO dishes(name, description, price, subcategory_id) VALUES ('Coupe trois parfums', 'Vanille, chocolat, fraise avec chantilly', 7, 6);
INSERT INTO dishes(name, description, price, subcategory_id) VALUES ('Sorbet exotique', 'Mangue, passion et citron vert', 6, 6);
INSERT INTO dishes(name, description, price, subcategory_id) VALUES ('Tarte Tatin', 'Tarte aux pommes caramélisées, servie tiède', 8, 7);
INSERT INTO dishes(name, description, price, subcategory_id) VALUES ('Mille-feuille', 'Feuilletage croustillant et crème pâtissière à la vanille', 9, 7);
INSERT INTO dishes(name, description, price, subcategory_id) VALUES ('Coca-Cola', 'Boisson gazeuse rafraîchissante', 4, 8);
INSERT INTO dishes(name, description, price, subcategory_id) VALUES ('Jus d''orange pressé', 'Orange fraîchement pressée', 5, 8);
INSERT INTO dishes(name, description, price, subcategory_id) VALUES ('Vin rouge Bordeaux', 'Grand cru AOC, 12 cl', 6, 9);
INSERT INTO dishes(name, description, price, subcategory_id) VALUES ('Champagne', 'Brut prestige, 12 cl', 10, 9);