-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS users
(
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100), -- Optionnel
    phone_number VARCHAR(20), -- Optionnel
    created_at TIMESTAMP DEFAULT NOW()
);

-- Table des rôles
CREATE TABLE IF NOT EXISTS roles
(
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL
);

-- Table de liaison entre utilisateurs et rôles
CREATE TABLE IF NOT EXISTS user_roles
(
    user_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    role_id INT NOT NULL REFERENCES roles(id) ON DELETE CASCADE,
    PRIMARY KEY (user_id, role_id)
);

-- Table des réservations
CREATE TABLE IF NOT EXISTS reservations
(
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    reservation_date DATE NOT NULL,
    reservation_time VARCHAR(255) NOT NULL,
    status VARCHAR(255) DEFAULT 'waiting',
    number_of_people INT NOT NULL
);


-- Table des plats
CREATE TABLE IF NOT EXISTS dishes
(
    dish_id SERIAL PRIMARY KEY,
    dish_name VARCHAR(255) NOT NULL,
    description VARCHAR(255),
    price DECIMAl(10,2) NOT NULL,
    type VARCHAR(255) NOT NULL
);


-- Table des catégories de plats
CREATE TABLE IF NOT EXISTS dish_categories
(
    category_id SERIAL PRIMARY KEY,
    dish_id INT NOT NULL REFERENCES dishes(dish_id) ON DELETE CASCADE,
    category VARCHAR(255) NOT NULL
);
