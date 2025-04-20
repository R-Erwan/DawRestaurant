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